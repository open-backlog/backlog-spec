# schema/

JSON Schema for Open Backlog files, one folder per spec version. Use it to check files
without any Open Backlog tool, and to get autocomplete and inline errors in an editor.

| File | Checks | Address |
|---|---|---|
| [0.1/item.schema.json](0.1/item.schema.json) | the front matter of a capability, epic, feature or story-level file | `https://openbacklog.org/schema/0.1/item.schema.json` |
| [0.1/config.schema.json](0.1/config.schema.json) | `config.yml` | `https://openbacklog.org/schema/0.1/config.schema.json` |

The schemas are JSON Schema draft-07, the version editors and linters support best. A
released version's schema never changes ([VERSIONING.md](../VERSIONING.md)).

## What the schema checks, and what it can't

A schema sees one file at a time. It checks the rules a single file can break:

- [front-matter], [type-known], [required-fields], [title-one-line], [field-format],
  [id-form], [no-children], [no-stored-status], [no-email] (in fields), [config-required];
- the *form* of a `parent` for each type, which catches [parent-feature], [parent-epic],
  [parent-capability] and [parent-none] when the parent is the wrong kind of ID.

Rules that need more than one file (whether a parent exists, unique IDs, filenames and
folders, sprints, people, `repo` against `repos:`, unplanned-work months, comment lines) are
left to a validator such as `ob validate`.

**IDs are text.** YAML reads an unquoted `2.10` as the number 2.1, so the schema accepts a
number for `id`, `parent`, `release` and `spec`: otherwise every hand-written `id: 2.3` would
show an error in an editor. A tool reads them as written text ([read-as-text]); the tests here
do.

## In an editor

### config.yml

With the [YAML extension](https://marketplace.visualstudio.com/items?itemName=redhat.vscode-yaml)
for VS Code (or any editor using `yaml-language-server`), add this line at the top of
`config.yml`:

```yaml
# yaml-language-server: $schema=https://openbacklog.org/schema/0.1/config.schema.json
spec: 0.1
name: my-app
```

or map it once in your settings:

```json
"yaml.schemas": {
  "https://openbacklog.org/schema/0.1/config.schema.json": "backlog/config.yml"
}
```

### Item front matter

YAML extensions don't look inside Markdown, so item files use
[remark-lint-frontmatter-schema](https://github.com/JulianCataldo/remark-lint-frontmatter-schema),
which runs in VS Code through the [remark extension](https://marketplace.visualstudio.com/items?itemName=unifiedjs.vscode-remark)
and on the command line. In the repository that holds your backlog:

```sh
npm install --save-dev remark-cli remark-frontmatter remark-lint-frontmatter-schema
```

Save a copy of `item.schema.json` in the repository (here as `.openbacklog/item.schema.json`)
and add `.remarkrc.mjs`:

```js
import remarkFrontmatter from 'remark-frontmatter';
import remarkLintFrontmatterSchema from 'remark-lint-frontmatter-schema';

export default {
  plugins: [
    remarkFrontmatter,
    [remarkLintFrontmatterSchema, {
      schemas: {
        './.openbacklog/item.schema.json': [
          'backlog/capabilities/*.md', 'backlog/epics/*.md',
          'backlog/features/*.md', 'backlog/stories/**/*.md',
        ],
      },
    }],
  ],
};
```

The editor then marks a wrong field on its line, such as `estimate: big` ("Must be number")
or a `status:` field ("Must NOT be valid"). The same check runs in a terminal or CI:
`npx remark backlog --frail`.

## Tests

`npm test` (run by CI on every push) validates every file in [examples/](../examples/)
against the schema, and fails when the two disagree: a valid example the schema rejects, an
invalid example it should catch but doesn't, or one it catches that only a validator should.
