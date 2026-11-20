# Design principles

Six principles shape the specification [@openbacklog-spec]. Every rule serves one of them; the
table at the end shows which.

1. **Simple first.** One file per item, three required fields, one link between items, and a
   status that is a folder. When a rule could be easier to follow or more expressive, the easier
   one won: version 0.1 went from 35 rules in its first draft to 20.
2. **Plain text.** Markdown [@commonmark] with a short YAML header [@yaml122]: readable by every
   editor, diff and AI model, now and in twenty years. Values are read as written, so `2.10` is
   never mistaken for `2.1`.
3. **No tool required.** Every rule can be followed with a text editor and git, and the result is
   what a tool would write. A tool that rewrites a file keeps what it does not understand.
4. **Git as the database.** A move is a commit, so git records who and when [@progit]. Review is a
   pull request, undo is a revert, backup is a clone. Nothing that can be worked out is stored,
   because a stored copy is a second place to be wrong.
5. **Start small, scale later.** Two lines of `config.yml` and one story is a valid backlog. Every
   feature for larger teams is optional and added by configuration, without converting any file.
6. **AI welcome, never required.** Agents get plain files they can read, change and commit, and
   nothing else: no agent-only format or API. This principle adds no rule; it is why the other
   five are enough.

## From principle to rule

| Rule | In short | Principle |
|---|---|---|
| [parent](https://openbacklog.org/spec#rule-parent) | each item points up at one parent of the right type | 1 |
| [parent-exists](https://openbacklog.org/spec#rule-parent-exists) | a parent is an item in the backlog | 1 |
| [id-form](https://openbacklog.org/spec#rule-id-form) | the ID's depth gives its type | 3 |
| [id-unique](https://openbacklog.org/spec#rule-id-unique) | no two items share an ID | 4 |
| [epic-zero](https://openbacklog.org/spec#rule-epic-zero) | unplanned work goes to a feature per month in epic 0 | 5 |
| [filename](https://openbacklog.org/spec#rule-filename) | a file is named for its ID and a slug | 3 |
| [front-matter](https://openbacklog.org/spec#rule-front-matter) | an item starts with a YAML mapping | 2 |
| [fields](https://openbacklog.org/spec#rule-fields) | three required fields, each in its format | 1 |
| [repo-listed](https://openbacklog.org/spec#rule-repo-listed) | a repository an item names is in config | 5 |
| [not-stored](https://openbacklog.org/spec#rule-not-stored) | children, status and progress are never written | 4 |
| [no-email](https://openbacklog.org/spec#rule-no-email) | people are named by handle, never by email | 2 |
| [comment-line](https://openbacklog.org/spec#rule-comment-line) | each comment starts with a date and a handle | 3 |
| [sprints](https://openbacklog.org/spec#rule-sprints) | a named sprint has a file with its dates | 5 |
| [people](https://openbacklog.org/spec#rule-people) | `people.yml` maps handles to names | 5 |
| [item-folder](https://openbacklog.org/spec#rule-item-folder) | a story's folder is its status | 4 |
| [config](https://openbacklog.org/spec#rule-config) | `config.yml` needs only `spec` and `name` | 5 |
| [read-as-text](https://openbacklog.org/spec#rule-read-as-text) | IDs and versions are read as written | 2 |
| [keep-unknown](https://openbacklog.org/spec#rule-keep-unknown) | a tool keeps what it does not know | 3 |
| [id-numbers](https://openbacklog.org/spec#rule-id-numbers) | a new ID is the next one in git history | 4 |
| [local-clones](https://openbacklog.org/spec#rule-local-clones) | other repositories are read from local clones | 4 |
