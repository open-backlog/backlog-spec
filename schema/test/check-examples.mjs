// Validates every example backlog's files against the JSON Schema, and fails when the schema
// and the examples disagree. Run with `npm test`.
//
// What must hold:
// - every file in a valid backlog (t1-minimal, t3-application, t4-portfolio, tools) passes;
// - each invalid/<rule>/ whose rule one file can show (SCHEMA_RULES) has a file that fails;
// - each other invalid/<rule>/ (rules that span files) passes, so the schema never rejects
//   something only a validator should.
import { readFileSync, readdirSync, statSync, existsSync } from 'node:fs';
import { join, relative, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';
import Ajv from 'ajv';
import { parseDocument } from 'yaml';

const root = join(dirname(fileURLToPath(import.meta.url)), '..', '..');
const examples = join(root, 'examples');
const SCHEMA_RULES = new Set([
  'front-matter', 'type-known', 'required-fields', 'title-one-line', 'field-format', 'id-form',
  'parent-feature', 'parent-epic', 'parent-capability', 'parent-none', 'no-children',
  'no-stored-status', 'no-email', 'config-required',
]);
// Values the spec says to read as written text, never as numbers [read-as-text].
const AS_TEXT = { item: ['id', 'parent', 'sprint', 'release'], config: ['spec'] };

// draft-07: what editors and linters understand best.
const ajv = new Ajv({ allErrors: true, strict: false });
const load = (n) => JSON.parse(readFileSync(join(root, 'schema', '0.1', n), 'utf8'));
const itemSchema = load('item.schema.json'), configSchema = load('config.schema.json');
for (const s of [itemSchema, configSchema]) {
  if (!s.$id.startsWith('https://openbacklog.org/schema/0.1/')) throw new Error(`bad $id ${s.$id}`);
}
const validateItem = ajv.compile(itemSchema), validateConfig = ajv.compile(configSchema);

function parseYaml(text, keys) {
  const doc = parseDocument(text);
  if (doc.errors.length) return { error: doc.errors[0].message };
  const value = doc.toJS();
  if (value && typeof value === 'object') {
    for (const k of keys) {
      const node = doc.get(k, true);
      if (node && node.source !== undefined) value[k] = String(node.source);
    }
  }
  return { value };
}

const walk = (d) => readdirSync(d).flatMap((n) => {
  const p = join(d, n);
  return statSync(p).isDirectory() ? walk(p) : [p];
});

// Every backlog folder under examples/.
// Matched on the path inside examples/, so folders above it (such as a checkout in a folder
// called backlog) can't be mistaken for one.
const backlogs = [...new Set(walk(examples).map((p) => relative(examples, p))
  .filter((r) => r.includes('backlog/'))
  .map((r) => r.slice(0, r.lastIndexOf('backlog/') + 'backlog'.length)))]
  .filter((r) => !r.includes('backlog/') || r.endsWith('backlog'))
  .map((r) => join(examples, r)).sort();

function check(backlog) {
  const failures = [];
  const cfg = join(backlog, 'config.yml');
  if (existsSync(cfg)) {
    const { value, error } = parseYaml(readFileSync(cfg, 'utf8'), AS_TEXT.config);
    if (error || !validateConfig(value)) failures.push(`config.yml: ${error ?? ajv.errorsText(validateConfig.errors)}`);
  }
  for (const folder of ['capabilities', 'epics', 'features', 'stories']) {
    if (!existsSync(join(backlog, folder))) continue;
    for (const f of walk(join(backlog, folder)).filter((p) => p.endsWith('.md'))) {
      const m = readFileSync(f, 'utf8').match(/^---\n([\s\S]*?)\n---\n?/);
      const { value, error } = m ? parseYaml(m[1], AS_TEXT.item) : { error: 'no front matter' };
      if (error || !validateItem(value)) failures.push(`${relative(backlog, f)}: ${error ?? ajv.errorsText(validateItem.errors)}`);
    }
  }
  return failures;
}

let wrong = 0, checked = 0;
for (const b of backlogs) {
  const name = relative(examples, b);
  const rule = name.startsWith('invalid/') ? name.split('/')[1] : null;
  const failures = check(b); checked++;
  const shouldFail = rule !== null && SCHEMA_RULES.has(rule);
  if (shouldFail !== failures.length > 0) {
    wrong++;
    console.log(`DISAGREE  ${name}: expected ${shouldFail ? 'a schema failure' : 'to pass'}, got ${failures.length ? failures.join('; ') : 'a pass'}`);
  } else {
    console.log(`ok        ${name}${failures.length ? `  (fails as expected: ${failures[0]})` : ''}`);
  }
}
console.log(`\n${checked} backlogs checked, ${wrong} disagreements`);
process.exit(wrong ? 1 : 0);
