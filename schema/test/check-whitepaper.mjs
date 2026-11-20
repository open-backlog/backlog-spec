// The whitepaper's appendix B shows the JSON Schema in full. This fails when either copy there
// differs from schema/0.1/, so the paper can never drift from the published schema. Run with
// `npm test`.
import { readFileSync } from 'node:fs';
import { join, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..', '..');
const appendix = readFileSync(join(root, 'whitepaper', '18-appendices.md'), 'utf8');
const blocks = [...appendix.matchAll(/^```json\n([\s\S]*?)\n```$/gm)].map(m => m[1]);
let failed = 0;
['item.schema.json', 'config.schema.json'].forEach((name, i) => {
  const file = readFileSync(join(root, 'schema', '0.1', name), 'utf8').replace(/\n$/, '');
  if (blocks[i] === file) console.log(`ok   whitepaper appendix B: ${name}`);
  else { console.log(`FAIL whitepaper appendix B: ${name} differs from schema/0.1/${name}`); failed++; }
});
if (blocks.length !== 2) { console.log(`FAIL whitepaper appendix B: expected 2 json blocks, found ${blocks.length}`); failed++; }
process.exit(failed ? 1 : 0);
