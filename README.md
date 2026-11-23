# Open Backlog

Open Backlog is an open convention for keeping a backlog as plain Markdown files in
a git repository. Each epic, feature and story is a file with a little YAML at the top; a
story's status is the folder it sits in; and moving it is a `git mv`, so git history is the
audit trail. There is no server, account or API: people and AI agents read and change the
backlog the same way, with a text editor and git.

```
backlog/
├── config.yml                  spec version and backlog name
├── epics/
│   └── 2-build.md
├── features/
│   └── 2.3-cli.md              parent: 2
└── stories/
    ├── todo/
    │   └── 2.3.5-ob-list.md    parent: 2.3
    ├── doing/
    │   └── 2.3.4-ob-move.md
    └── done/
        └── 2.3.3-ob-validate.md
```

Starting `2.3.4` is `git mv backlog/stories/todo/2.3.4-ob-move.md backlog/stories/doing/`,
then a commit.

## Status

**0.1.0, released 2026-11-23** as the tag `spec-v0.1.0`
([release](https://github.com/open-backlog/backlog-spec/releases/tag/spec-v0.1.0),
[VERSIONING.md](VERSIONING.md)). Read it at [spec/](spec/) or https://openbacklog.org/spec; the
whitepaper's PDF is at https://openbacklog.org/whitepaper. To cite it, use
[CITATION.cff](CITATION.cff) (GitHub's "Cite this repository").

## What's in this repository

| Folder | What it holds |
|---|---|
| [spec/](spec/) | The specification: the normative rules |
| [whitepaper/](whitepaper/) | The whitepaper: why the convention exists and how it scales, built to PDF |
| [examples/](examples/) | Example backlogs, valid and deliberately invalid |
| [schema/](schema/) | JSON Schema for item front matter and `config.yml` |

The `ob` command line and the local board live in their own repositories.

## Publishing to openbacklog.org

`php script/buildmerge-ui.php` hands the spec, schema, examples and whitepaper (its pages and
PDFs) to the site's source, `backlog-ui` (beside this repo). Uncommitted changes can be tried in its `ng serve`; only committed ones
can be deployed. backlog-ui's README has the rest.

## License

- The specification and the whitepaper (`spec/`, `whitepaper/`) and every other piece of
  prose in this repository: [CC-BY-4.0](LICENSE). Share and adapt them, with attribution.
- Code and machine-readable files (`schema/`, scripts, build files) and the `examples/`
  backlogs: [MIT](LICENSE-CODE), so they can be copied into any project.

## Contributing

Open an issue (spec change, bug or idea), then a pull request: [CONTRIBUTING.md](CONTRIBUTING.md).
Every rule change is in [CHANGELOG.md](CHANGELOG.md). Everyone taking part follows the
[code of conduct](CODE_OF_CONDUCT.md).
