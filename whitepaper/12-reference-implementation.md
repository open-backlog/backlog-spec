# The reference implementation

The specification needs no tool, but tools make common work faster and catch mistakes early.
Open Backlog's reference implementation shows one way to build them. **At the time of writing it
is designed and planned but not yet built**: this section describes it as designed, and the
specification, not the implementation, remains the definition of a valid backlog.

## Three parts, one set of rules

| Part | Package | What it is |
|---|---|---|
| Core library | `open-backlog` (library entry) | reads, checks and changes a backlog; no command-line code |
| `ob` | `open-backlog` (the `ob` command) | the command line, built on the core |
| Board | `open-backlog-board` | a local web board, served by `ob serve`, built on the same core |

All three are TypeScript on Node, MIT-licensed, published to npm. The board depends on the
published core rather than having its own, so the command line and the board cannot disagree
about the rules. One install, `npm i -g open-backlog`, gives both: `ob serve` uses an installed
board, or fetches the matching version the first time it runs.

## The core library

The core does what any implementation of the specification has to do, and is the part a tool
builder can most easily reuse or copy:

- **Find and read a backlog.** Walk up from the working folder to `backlog/config.yml`; load the
  config, every item, sprints and people; work out each story's status from its folder,
  including subfolders of `done/`. Read IDs and versions as text ([read-as-text]).
- **Work out the hierarchy.** Resolve each `parent`, build each item's children, compute
  progress, and sort by ID with each part compared as a number, then by `rank`.
- **Check every rule.** Each rule of the specification is a check with a stable code, a severity,
  the file and a message saying how to fix it. Single-file checks use the specification's JSON
  Schema [@jsonschema07], the same schema the site's file checker uses, so any tool built on that
  schema agrees with this one; checks across files (parents exist, IDs unique) are code. A link
  into a backlog that is not cloned is a warning ([local-clones]).
- **Write without losing anything.** Change one field in place with a YAML library that keeps
  comments, order and formatting, and leave the body and unknown fields exactly as they were
  ([keep-unknown]). A read and write with no changes leaves a file byte for byte identical, so a
  tool's edit looks like a careful hand edit in `git diff`.
- **Number new items from history.** The next ID under a parent is one more than the highest in
  the files *and* in git history, so a pruned number is never reused ([id-numbers]).
- **Use the user's own git.** See below.

The specification's example backlogs are the core's test fixtures: every valid example must
load and pass, and each `examples/invalid/<rule>/` backlog must fail with exactly its own rule.

## Your own git

The tools run the `git` command already installed, never a git library of their own and never a
server. That one choice gives them the user's identity, SSH keys, credential helper, hooks and
configuration with no set-up, and it keeps the promise of no account and no API key: if a person
can push, the tools can, and if they cannot, neither can the tools.

Each change is one `git mv` or edit plus one commit, staging only the files that change touched,
so unrelated work in progress is never swept in. Every command that commits also has
`--no-commit`, which leaves committing to the user. Outside a git repository the tools still read,
check and write files; only committing needs git.

## `ob`, the command line

| Command | What it does |
|---|---|
| `ob init` | creates `backlog/config.yml` and the folders |
| `ob new <type> "Title" --parent <id>` | creates the next numbered item from a template; a bug with no parent is filed in this month's feature of epic `0` |
| `ob validate` | prints errors and warnings, exits non-zero on errors; `--fix` renumbers duplicate IDs; `--json`; works as a pre-commit hook and in CI |
| `ob move <id> <status>`, `ob start`, `ob done` | `git mv` plus a commit; `ob done` files by month when configured |
| `ob list` | story-level items in build order, with filters; recent finished work only, unless `--all` |
| `ob show <id>`, `ob tree` | one item with its parent chain; the whole plan with progress |
| `ob comment <id> "text"` | appends `- YYYY-MM-DD @handle: text`, the handle from git config, never the email |
| `ob serve` | starts the board |

Every command's result is identical to doing the same thing by hand, and is tested to be.

## The board

`ob serve` starts a web server bound to `127.0.0.1` only, with a JSON API over the core, and
opens an Angular app in the browser: columns for `todo`, `doing` and `done`, drag a card to move
it or to reorder it (which sets `rank`), an item view and editor, and a tree with progress bars.
It watches the backlog folder, so an edit made in a text editor appears on the board within a
second.

Each board action is one commit, with a message like `ob: move 2.3.4 to doing`. The board never
pushes on its own: a **Sync** button pulls, then pushes, shows how many commits are waiting, and
on a conflict says which files and what to do, leaving the repository in a state a person can
resolve by hand. There is no login, because there is no server beyond the user's own machine.

## For tool builders

A tool that implements the specification needs, at minimum, the first four points of the core
above: find and read, work out the hierarchy, check the rules, and write without losing anything.
The JSON Schema covers the rules a single file can break; the example backlogs, valid and
invalid, are a ready-made test suite; and the `tools/` examples check the three places careless
tools go wrong (numbers read as text, unknown fields kept, missing clones). Nothing in the
reference implementation is required to read or write an Open Backlog, and a tool in any
language, an editor extension, or a bridge to another tracker is as much an implementation as
`ob`.
