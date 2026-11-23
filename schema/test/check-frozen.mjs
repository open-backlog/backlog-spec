// A released schema never changes (VERSIONING.md): schema/0.1/ was frozen at spec-v0.1.0, and
// schema/0.1.sha256 holds its files' SHA-256. This fails if a file there changes, is removed or
// is added. A change goes into a new version folder, such as schema/0.2/. Run with `npm test`.
import { createHash } from 'node:crypto';
import { readdirSync, readFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const schema = join(dirname(fileURLToPath(import.meta.url)), '..');
let failed = 0;
for (const sums of readdirSync(schema).filter(f => /^\d+\.\d+\.sha256$/.test(f))) {
  const version = sums.replace(/\.sha256$/, '');
  const frozen = new Map(readFileSync(join(schema, sums), 'utf8').trim().split('\n').map(l => l.split(/\s+/)).map(([hash, file]) => [file, hash]));
  const files = readdirSync(join(schema, version)).filter(f => f.endsWith('.json')).sort();
  for (const file of new Set([...frozen.keys(), ...files])) {
    const want = frozen.get(file);
    let have = null;
    try { have = createHash('sha256').update(readFileSync(join(schema, version, file))).digest('hex'); } catch { /* removed */ }
    if (want === have) { console.log(`ok   schema/${version}/${file} is as released`); continue; }
    failed++;
    console.error(`FAIL schema/${version}/${file} ${!want ? 'was added' : !have ? 'was removed' : 'changed'} after its release: schema/${version}/ is frozen; put the change in a new version folder`);
  }
}
process.exit(failed ? 1 : 0);
