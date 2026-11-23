# Open Backlog specification

**Version 0.1.0**, released 2026-11-23 as `spec-v0.1.0`; see [VERSIONING.md](../VERSIONING.md).

An Open Backlog is a folder of Markdown files in a git repository. Each file is one *item*: a
capability, an epic, a feature, or a piece of work such as a story. A story's status is the
folder it is in. This document says what a valid backlog is, so that people and tools read and
write it the same way.

## Contents

1. [Conventions](#1-conventions)
2. [Items](#2-items)
3. [Files](#3-files)
4. [Folders and status](#4-folders-and-status)
5. [Configuration and scale](#5-configuration-and-scale)
6. [Rules for tools](#6-rules-for-tools)

## 1. Conventions

**MUST**, **MUST NOT**, **SHOULD** and **MAY** are used as in
[BCP 14](https://www.rfc-editor.org/info/bcp14) ([RFC 2119](https://www.rfc-editor.org/rfc/rfc2119),
[RFC 8174](https://www.rfc-editor.org/rfc/rfc8174)) when they appear in capitals.

Each rule is one sentence with a name, such as **[parent]**. A backlog is *valid* when it breaks
none of the rules in sections 2 to 5. Section 6 is for tools; someone working by hand can skip it.
`examples/invalid/` has a backlog that breaks each rule.

## 2. Items

### Types

| Type | What it is |
|---|---|
| `capability` | A lasting ability of the product, such as identity or billing. Optional. |
| `epic` | A large goal, delivered by its features. |
| `feature` | Something a user can do or see, delivered by its stories. |
| `story` | Work that delivers value to someone. |
| `bug` | Work that fixes something broken. |
| `spike` | Time-boxed research; its result is an answer. |
| `task` | Any other work, such as a chore. |

`story`, `bug`, `spike` and `task` are the **story-level** types: the work itself.
Capabilities, epics and features organise it, and their progress is worked out from it.

### Parents

Each item points up at its parent: `parent` holds the parent's `id`.

```
capability            (optional)
  └── epic
        └── feature
              └── story · bug · spike · task
```

| Item | Its `parent` |
|---|---|
| story-level | a feature, required |
| feature | an epic, required |
| epic | a capability, optional |
| capability | none |

- **[parent]** An item MUST have a `parent` where the table requires one, MUST NOT have one
  where it allows none, and its parent MUST be of the type the table gives.
- **[parent-exists]** A `parent` MUST be the `id` of an item in the backlog.

Because a parent is always higher up, a loop is impossible. Children are never written down:
an item's children are the items whose `parent` is its `id`. To move a story to another
feature, change its `parent`, and nothing else.

### IDs

The number of parts in an ID gives its type:

| Type | ID | Example |
|---|---|---|
| epic | a number | `2` |
| feature | its epic's number, a dot, a number | `2.3` |
| story-level | its feature's ID, a dot, a number | `2.3.4` |
| capability | `C` and a number | `C1` |

Numbers are whole, with no leading zeros, and start at `1`; epic `0` is kept for unplanned work.

- **[id-form]** An item's `id` MUST have the form the table gives for its type.
- **[id-unique]** An item's `id` MUST be different from every other `id` in the backlog.

A new item takes the next number under the item it is filed under, counting items since
deleted, so IDs read in order are the order the work was planned in ([id-numbers]). Dropping
an ID's last number gives the item it was filed under, and the filenames spell it out:
`2-build › 2.3-cli › 2.3.4-ob-move`. An ID never changes: if an item later moves, its `parent`
changes and its ID still shows where it was filed.

Refer to an item by its ID: `2.3.4`. For an item in another backlog, put that backlog's name in
front: `notes/2.3.4`.

### Unplanned work

Work that turns up outside the plan goes to epic `0`, which has one feature per month:
`0.1-2026-11`, `0.2-2026-12`, and so on. Create the epic and the month's feature the first time
they are needed. When a stray turns out to belong to a planned feature, change its `parent`.

- **[epic-zero]** A feature in epic `0` MUST be named for its month: its slug is `YYYY-MM`.

## 3. Files

### Filenames

An item's file is named for its ID and a slug, a few words saying what it is:
`2.3.4-ob-move.md`, `2-build.md`, `C1-identity.md`. The slug can be edited; the ID cannot.

- **[filename]** An item's file MUST be named `<id>-<slug>.md`, with its `id` exactly as in its
  front matter and a slug of lowercase letters and digits joined by single dashes.

### Front matter

An item file starts with YAML front matter: a mapping between two `---` lines.

- **[front-matter]** An item file MUST start with front matter that is a YAML mapping.

| Field | Required | Format |
|---|---|---|
| `id` | yes | the item's ID ([section 2](#ids)) |
| `type` | yes | one of the seven types |
| `title` | yes | text on one line, not empty |
| `parent` | see [Parents](#parents) | an `id` |
| `rank` | no | a number; lowest first within its folder |
| `sprint` | no | the name of a sprint file |
| `estimate` | no | a number, zero or more, in the backlog's unit |
| `assignee` | no | a handle |
| `release` | no | text, such as `0.1.0` |
| `due` | no | a date, `YYYY-MM-DD` |
| `repo` | no | a repository name, or a list of them |

A *handle* names a person: lowercase letters, digits, `-`, `_` and `.`, such as `jo-smith`.
Any other field MAY be added, such as `customer: acme`.

- **[fields]** An item MUST have `id`, `type` and `title`, and each field in the table MUST have
  the format it gives.
- **[repo-listed]** Each name in `repo` MUST be a key of `repos:` in `config.yml`.
- **[not-stored]** An item MUST NOT have a `children`, `status` or `progress` field, and a sprint
  file MUST NOT list its items; these are worked out.
- **[no-email]** Front matter, comment lines and `people.yml` MUST NOT contain an email address.

### Body

The body is Markdown, written freely, and MAY be empty. For story-level items this shape is
recommended:

```markdown
**As a** <who>, **I want to:**
- <what they want>

**So that:**
- <why, written so it can be checked>

## Summary
<a short summary>

## As built
<what was done, written when it is finished>

## Comments
- <YYYY-MM-DD> @<handle>: <comment>
```

`## Comments` comes last. Each comment is one list item, oldest first, and may continue on
indented lines.

- **[comment-line]** Each top-level list item under `## Comments` MUST start with
  `- YYYY-MM-DD @handle: `.

A complete story:

```markdown
---
id: 2.3.4
type: story
title: Register a passkey
parent: 2.3
estimate: 3
assignee: jo-smith
sprint: 2026-S24
---

**As a** returning user, **I want to:**
- register a passkey from my account page

**So that:**
- Verify a registered passkey signs me in on the same device

## Comments
- 2026-11-20 @jo-smith: started; testing on beta, which has HTTPS.
```

### Sprints and people

Both are optional. A sprint is a file in `sprints/`, such as `sprints/2026-S24.md`; items name
it in `sprint`, and it never lists them. Its front matter has `start`, `end` and an optional
`goal`:

```yaml
---
start: 2026-11-16
end: 2026-11-27
goal: Passkeys on beta
---
```

`people.yml` maps handles to names, with an optional `forge`, the person's username on the git
host. A handle need not be listed; a tool then shows the handle itself.

```yaml
jo-smith:
  name: Jo Smith
  forge: josmith
```

- **[sprints]** A sprint an item names MUST have a file, and every sprint file MUST have `start`
  and `end` dates (`YYYY-MM-DD`), with `end` not before `start`.
- **[people]** Each key in `people.yml` MUST be a handle, with a `name`.

## 4. Folders and status

A backlog is one folder, usually `backlog/` at the top of a repository:

```
backlog/
├── config.yml          required
├── people.yml          optional
├── capabilities/       C1-identity.md
├── epics/              0-unplanned.md, 2-build.md
├── features/           0.1-2026-11.md, 2.3-cli.md
├── sprints/            optional: 2026-S24.md
└── stories/            story, bug, spike and task files, by status:
    ├── todo/           2.3.5-ob-list.md
    ├── doing/          2.3.4-ob-move.md
    └── done/           2.3.3-ob-validate.md, or done/2026-11/2.3.3-ob-validate.md
```

Empty folders MAY be left out.

- **[item-folder]** Capabilities, epics and features MUST be in `capabilities/`, `epics/` and
  `features/`; story-level items MUST be directly in `stories/todo/` or `stories/doing/`, or
  anywhere under `stories/done/`.

### Status is the folder

A story-level item is not started in `todo/`, in progress in `doing/` and finished in `done/`.
Changing its status is moving the file, and nothing else:

```sh
git mv backlog/stories/todo/2.3.4-ob-move.md backlog/stories/doing/
git commit -m "2.3.4 doing"
```

The commit records who moved it and when, so git history is the item's timeline.

Capabilities, epics and features never move. A feature is done when all its story-level items
are in `done/`, and in progress when any is in `doing/` or `done/`; an epic is done when all its
features are, and a capability when all its epics are. A tool may count items or weight them
by `estimate`. Nothing that can be worked out is stored, so there is nothing to keep in sync.

### Order

Items are listed by ID, each part compared as a number: `2.9` before `2.10`. Within a folder,
items with a `rank` come first, lowest first.

### Done subfolders and pruning

`stories/done/` MAY have subfolders with any names, at any depth, such as `done/2026-11/`;
moving between them does not change status. Old items MAY be removed with `git rm`: git history
keeps them, and their IDs stay used. Remove an item's children before the item itself, or they
break [parent-exists].

## 5. Configuration and scale

`config.yml` sits at the top of the backlog. Two lines are enough:

```yaml
spec: 0.1
name: my-app
```

| Key | Required | Format |
|---|---|---|
| `spec` | yes | the spec version, `MAJOR.MINOR` |
| `name` | yes | lowercase letters, digits and dashes; other backlogs write `my-app/2.3.4` |
| `estimate` | no | `points` (the default), `hours` or `days`: the unit of every `estimate` |
| `done-folders` | no | `month`: tools file finished items into `stories/done/YYYY-MM/` |
| `repos` | no | repository name → clone URL (T3) |
| `backlogs` | no | backlog name → clone URL (T4) |

- **[config]** A backlog MUST have a `config.yml` with `spec` and `name`, and each key in the
  table MUST have the format it gives.

A backlog grows through four tiers without changing any item file:

| Tier | What it is | What changes |
|---|---|---|
| T1 | A backlog in the code repository it plans | Nothing more: two lines of config. |
| T2 | A backlog in its own repository | The folder moves. |
| T3 | One backlog for an application in several repositories | `repos:` in config, and `repo` on items. |
| T4 | A portfolio of backlogs | `backlogs:` in config; their items are named `name/2.3.4`. |

```yaml
spec: 0.1
name: shop
repos:
  shop-api: git@github.com:example/shop-api.git
  shop-web: git@github.com:example/shop-web.git
```

An item then names its repository with `repo: shop-api`, or `repo: [shop-api, shop-web]` for
work in both. A key in `backlogs:` is the name used for that backlog's items.

Repositories and backlogs are found as local clones: a folder with the key's name beside the
repository holding this backlog, such as `../shop-api/`, with a listed backlog in its
`backlog/` folder. The clone URL tells people where to clone from.

## 6. Rules for tools

These rules are for programs that read or write a backlog.

- **[read-as-text]** A tool MUST read `id`, `parent`, `sprint`, `release` and `spec` as the text
  written, never as numbers: YAML reads `2.10` as 2.1, but it is the tenth feature of epic 2.
- **[keep-unknown]** A tool that rewrites a file MUST keep every field and key it does not
  know, and every part of the body it was not asked to change, and SHOULD keep the field order.
- **[id-numbers]** A new item's ID MUST take the next number under the item it is filed under
  (an epic or capability, the next in the backlog), one more than the highest in the backlog's
  git history, and an ID MUST NOT change or be used again, except to fix a duplicate.
- **[local-clones]** A tool MUST read other repositories and backlogs only from local clones,
  never from the network or git submodules, and MUST warn, not fail, when one is missing.

**Duplicates.** Two people on separate branches can both file `2.3.5`; after the merge the
backlog breaks [id-unique]. The item added later in git history takes the next free number, and
its file is renamed. A tool SHOULD offer to do this.
