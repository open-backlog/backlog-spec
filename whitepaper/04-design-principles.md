# Design principles

Open Backlog is shaped by six principles. Each answers one of the costs in section 2, and every
rule in the specification exists because of one of them. This section gives the reasons; the
rules themselves are in the specification [@openbacklog-spec], and the table at the end traces
each rule to the principle behind it.

## 1. Simple first

A backlog that needs a manual to edit will be edited by whoever has read the manual, and nobody
else. So the format is as small as it can be and still describe real agile work: one file per
item, three required fields, one link (`parent`) between items, and a status that is a folder.
Anything that can be worked out from the files is worked out, never written down, because every
stored copy of a fact is a second place for it to be wrong. When a choice was between a rule that
is easy to follow and one that is more expressive, the easy one won; version 0.1 went from 35
rules in its first draft to the 20 it has now.

## 2. Plain text

Every item is a Markdown file [@commonmark] with a short YAML header [@yaml122]. Plain text is the
one format that every editor, every operating system, every diff and every AI model already
handles, and it will still be readable in twenty years. The header holds only what a program
needs to find and order an item; everything a person needs to understand it goes in the body, in
whatever words fit. Values are read as the text written, so `2.10` is never mistaken for `2.1`.

## 3. No tool required

Every rule can be followed with a text editor and git. A person can create a story, start it,
finish it and move it to another feature without installing anything, and the result is exactly
what a tool would have written. Tools are welcome, and the reference implementation (section 12)
makes common work faster, but no tool owns the files: a tool that rewrites one must keep what it
does not understand. A backlog that can be maintained by hand can outlive every tool written for
it.

## 4. Git as the database

The backlog keeps no history of its own, because git already keeps one [@progit]. A status
change is a `git mv`, so the commit records who changed it and when. Review is a pull request,
an undo is a revert, a proposal is a branch, and backup is a clone. Nothing about the backlog
needs a server: identity comes from commit authors, permissions from the git host, and
concurrency from merges. Where git already answers a question, the specification does not ask it
again.

## 5. Start small, scale later

The smallest valid backlog is a `config.yml` of two lines and one story. A team should be able to
start there, inside its code repository, and grow to a portfolio of backlogs across many
repositories without converting a single file. Every feature for larger teams (repositories,
other backlogs, sprints, people, estimates) is optional, added by configuration, and invisible
to a team that does not use it.

## 6. AI welcome, never required

AI coding agents work best with plain files they can read, change and commit. Open Backlog gives
them that, and nothing more: there is no agent-only format, no special API and no rule that
exists for agents alone. An agent reads a story the way a person does and moves it the way a
person does. This principle adds no rule of its own; it is why the other five are enough. A team
that uses no AI loses nothing, and a team that uses it gains a backlog its agents can already
work with.

## From principle to rule

Each rule of version 0.1, linked to its text in the specification, and the principle it serves.

| Rule | What it says, in short | Principle |
|---|---|---|
| [parent](https://openbacklog.org/spec#rule-parent) | each item points up at one parent of the right type | 1 simple first |
| [parent-exists](https://openbacklog.org/spec#rule-parent-exists) | a parent is an item in the backlog | 1 simple first |
| [id-form](https://openbacklog.org/spec#rule-id-form) | the ID's depth gives its type | 3 no tool required |
| [id-unique](https://openbacklog.org/spec#rule-id-unique) | no two items share an ID | 4 git as the database |
| [epic-zero](https://openbacklog.org/spec#rule-epic-zero) | unplanned work goes to a feature per month in epic 0 | 5 start small |
| [filename](https://openbacklog.org/spec#rule-filename) | a file is named for its ID and a slug | 3 no tool required |
| [front-matter](https://openbacklog.org/spec#rule-front-matter) | an item starts with a YAML mapping | 2 plain text |
| [fields](https://openbacklog.org/spec#rule-fields) | three required fields, each field in its format | 1 simple first |
| [repo-listed](https://openbacklog.org/spec#rule-repo-listed) | a repository an item names is in config | 5 scale later |
| [not-stored](https://openbacklog.org/spec#rule-not-stored) | children, status and progress are never written | 1 simple first |
| [no-email](https://openbacklog.org/spec#rule-no-email) | people are named by handle, never by email | 2 plain text, read by anyone with a clone |
| [comment-line](https://openbacklog.org/spec#rule-comment-line) | each comment starts with a date and a handle | 3 no tool required |
| [sprints](https://openbacklog.org/spec#rule-sprints) | a named sprint has a file with its dates | 5 start small |
| [people](https://openbacklog.org/spec#rule-people) | `people.yml` maps handles to names | 5 start small |
| [item-folder](https://openbacklog.org/spec#rule-item-folder) | a story's folder is its status | 4 git as the database |
| [config](https://openbacklog.org/spec#rule-config) | `config.yml` needs only `spec` and `name` | 5 start small |
| [read-as-text](https://openbacklog.org/spec#rule-read-as-text) | IDs and versions are read as written | 2 plain text |
| [keep-unknown](https://openbacklog.org/spec#rule-keep-unknown) | a tool keeps what it does not know | 3 no tool required |
| [id-numbers](https://openbacklog.org/spec#rule-id-numbers) | a new ID is the next one in git history | 4 git as the database |
| [local-clones](https://openbacklog.org/spec#rule-local-clones) | other repositories are read from local clones | 4 git as the database |
