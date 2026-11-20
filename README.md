# Open Backlog

Open Backlog is an open convention for keeping an agile backlog as plain Markdown files in
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

**Draft.** Spec 0.1 is being written; nothing here is released yet. See
[VERSIONING.md](VERSIONING.md) for what a release is.

## What's in this repository

| Folder | What it holds |
|---|---|
| [spec/](spec/) | The specification: the normative rules |
| [whitepaper/](whitepaper/) | The whitepaper: why the convention exists and how it scales, built to PDF |
| [examples/](examples/) | Example backlogs, valid and deliberately invalid |
| [schema/](schema/) | JSON Schema for item front matter and `config.yml` |

The tools that implement the convention (the `ob` command line and the local board) live
in their own repositories. The site is https://openbacklog.org.

## Publishing to openbacklog.org

The site (`backlog-ui`, beside this repo) renders `spec/README.md` and serves `schema/`. To hand
it a change, run `php script/buildmerge-ui.php` here; it replaces `backlog-ui/src/content/backlog-spec/`.
Uncommitted changes can be tried in backlog-ui's `ng serve`, but only committed ones can be
deployed. backlog-ui's README has the rest.

## License

- The specification and the whitepaper (`spec/`, `whitepaper/`) and every other piece of
  prose in this repository: [CC-BY-4.0](LICENSE). Share and adapt them, with attribution.
- Code and machine-readable files (`schema/`, scripts, build files) and the `examples/`
  backlogs: [MIT](LICENSE-CODE), so you can copy them into your own projects freely.

## Contributing

Open an issue first, then a pull request. [CONTRIBUTING.md](CONTRIBUTING.md) explains how
changes are proposed and decided, and [CHANGELOG.md](CHANGELOG.md) records every change to a
normative rule.
