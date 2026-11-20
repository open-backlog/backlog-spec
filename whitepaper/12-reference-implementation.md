# The reference implementation

**Designed, not yet built.** This section describes the tools as designed. The specification, not
the tools, defines a valid backlog.

| Part | npm package | What it is |
|---|---|---|
| Core library | `open-backlog` | reads, checks and changes a backlog |
| `ob` | `open-backlog` | the command line, on the core |
| Board | `open-backlog-board` | a local web board, started by `ob serve`, on the same core |

All are TypeScript on Node and MIT-licensed. The board uses the published core, so the command
line and the board cannot disagree about the rules. `npm i -g open-backlog` installs both; the
board is fetched the first time `ob serve` runs.

## The core

- **Read.** Walk up from the working folder to `backlog/config.yml`; load every item; take each
  story's status from its folder; read IDs as text.
- **Work out.** Resolve parents and children, compute progress, and sort by ID part by part.
- **Check.** Every rule has a stable code and a message saying how to fix it. Single-file rules
  use the specification's JSON Schema [@jsonschema07], the same one the site's file checker uses;
  rules across files are code. A missing clone is a warning.
- **Write without loss.** Change one field in place and keep comments, order, unknown fields and
  the body exactly as they were, so a tool's edit looks like a careful hand edit in `git diff`.
- **Number from history.** A new ID is one more than the highest in the files *and* in git
  history, so a pruned number is never reused.

The specification's example backlogs are the core's tests: each valid one must pass, and each
`examples/invalid/<rule>/` must fail with exactly its rule.

**Your own git.** The tools run the installed `git`, never a library or a server. They get the
user's identity, SSH keys, credential helper and hooks for free, and need no account or API key.
Each change is one commit of only the files it touched. Moves, comments and the board take
`--no-commit` to leave committing to the user.

## `ob`

| Command | What it does | npm |
|---|---|---|
| `ob init` | creates `config.yml` and the folders | 0.1.0 |
| `ob new <type> "Title" --parent <id>` | creates the next numbered item; a bug with no parent goes to this month's feature in epic `0` | 0.1.0 |
| `ob validate` | reports errors and warnings; `--fix` renumbers duplicate IDs; works in CI and as a pre-commit hook | 0.1.0 |
| `ob move`, `ob start`, `ob done` | `git mv` plus a commit; files by month when configured | 0.1.0 |
| `ob list`, `ob show`, `ob tree` | items in build order; one item; the plan with progress | 0.1.0 |
| `ob comment <id> "text"` | adds a dated comment under the handle from git config | 0.2.0 |
| `ob serve` | starts the board | 0.3.0 |

Each command's result is to be identical to doing the same thing by hand.

## The board

`ob serve` listens on `127.0.0.1` only and opens a board: `todo`, `doing` and `done` columns, drag
to move or reorder (setting `rank`), an item editor, and a tree with progress. An edit made in a
text editor appears within a second. Each action is one commit, such as
`ob: move 2.3.4 to doing`. The board never pushes on its own: a **Sync** button pulls and pushes,
and on a conflict says which files and what to do. There is no login.

## For tool builders

A tool needs the first four parts of the core: read, work out, check and write without loss. The
JSON Schema covers single-file rules, the example backlogs are a test suite, and the `tools/`
examples cover the three classic mistakes: numbers read as text, unknown fields dropped, and
missing clones. An editor extension or a tracker bridge is as much an implementation as `ob`.
