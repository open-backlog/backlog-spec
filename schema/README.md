# schema/

JSON Schema for Open Backlog files, one folder per spec version: for checking files without
any Open Backlog tool, and for autocomplete and inline errors in an editor.

| File | Checks | Address |
|---|---|---|
| [0.1/item.schema.json](0.1/item.schema.json) | the front matter of a capability, epic, feature or story-level file | `https://openbacklog.org/schema/0.1/item.schema.json` |
| [0.1/config.schema.json](0.1/config.schema.json) | `config.yml` | `https://openbacklog.org/schema/0.1/config.schema.json` |

They are JSON Schema draft-07, which editors support best, and never change after release.

## What it checks

A schema sees one file at a time, so it checks the rules one file can break: [front-matter],
[fields], [id-form], [parent] (by the form of the parent's ID), [not-stored], [no-email] and
[config]. The rest need the whole backlog and are for a validator such as `ob validate`.

The schema accepts a number for `id`, `parent`, `release` and `spec`, because editors read
`2.3` as a number; tools read them as text ([read-as-text]).

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

`npm test`, run by CI on every push, checks every file in [examples/](../examples/) against the
schema and fails if they disagree.
