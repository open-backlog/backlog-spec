# Open Backlog specification

**Version 0.1, draft.** Nothing here is released yet; see [VERSIONING.md](../VERSIONING.md).

An Open Backlog is a folder of Markdown files in a git repository. Each file is one *item*:
a capability, an epic, a feature, or a piece of work such as a story. This document says
what a valid backlog is, so that a person with a text editor, and any tool, read and write
it the same way.

## Contents

1. [Conventions](#1-conventions)
2. [Items and types](#2-items-and-types)
3. [The hierarchy](#3-the-hierarchy)
4. [Fields](#4-fields)
5. [The body](#5-the-body)
6. [Sprints and people](#6-sprints-and-people)
7. [IDs and filenames](#7-ids-and-filenames)
8. [Layout and lifecycle](#8-layout-and-lifecycle)
9. [Configuration and scale](#9-configuration-and-scale)

## 1. Conventions

The key words **MUST**, **MUST NOT**, **SHOULD**, **SHOULD NOT** and **MAY** are to be
interpreted as described in [BCP 14](https://www.rfc-editor.org/info/bcp14) ([RFC
2119](https://www.rfc-editor.org/rfc/rfc2119), [RFC 8174](https://www.rfc-editor.org/rfc/rfc8174))
when, and only when, they appear in all capitals, as shown here.

Each rule is one sentence with a short name in brackets, such as **[parent-feature]**. A
backlog is *valid* when it breaks none of the rules. The names are stable: tools use them in
their messages, and `examples/invalid/` has one backlog per rule.

An item's properties are written as YAML front matter at the top of its file, between two
`---` lines; [section 4](#4-fields) lists them.

## 2. Items and types

There are seven types. Three of them organise work, and four of them *are* the work:

| Type | What it is |
|---|---|
| `capability` | A lasting ability the product has or will have, such as identity or billing. Optional: many backlogs have none. |
| `epic` | A large goal, delivered by its features. |
| `feature` | Something a user can do or see, delivered by its story-level items. |
| `story` | A piece of work that delivers value to someone. |
| `bug` | A piece of work that fixes something broken. |
| `spike` | A time-boxed piece of research, whose result is an answer rather than shipped code. |
| `task` | A piece of work that is none of the above, such as a chore or a release step. |

`story`, `bug`, `spike` and `task` are the **story-level** types. Only story-level items are
planned, worked on and finished; capabilities, epics and features show progress made up from
their story-level items.

- **[type-known]** Every item MUST have a `type`, and it MUST be one of the seven types above.

## 3. The hierarchy

Items form a tree by pointing *up* at their parent, with the `parent` field holding the
parent's `id`:

```
capability            (optional)
  └── epic
        └── feature
              └── story · bug · spike · task
```

### Which parents are allowed

- **[parent-feature]** A story-level item MUST have a `parent`, and it MUST be a feature.
- **[parent-epic]** A feature MUST have a `parent`, and it MUST be an epic.
- **[parent-capability]** An epic MAY have a `parent`, and if it has one it MUST be a capability.
- **[parent-none]** A capability MUST NOT have a `parent`.
- **[parent-exists]** A `parent` MUST be the `id` of an item that exists in the backlog.

Every parent is a type higher up the tree than its child, so following `parent` from any
item always ends at an epic or a capability, after at most three steps. A loop cannot be
written without breaking one of the rules above.

### Children are worked out, never listed

- **[no-children]** An item MUST NOT have a `children` field.

An item's children are exactly the items whose `parent` is its `id`, and tools work them out
by reading the backlog. So each link is written in one place only, on the child: to move a
story to another feature, change the story's `parent` and nothing else.

### Example

A capability, an epic that serves it, one of the epic's features, and one story in that
feature. These four files are a valid hierarchy:

```yaml
---
id: C1
type: capability
title: Identity
---
```

```yaml
---
id: 2
type: epic
title: Sign-in
parent: C1
---
```

```yaml
---
id: 2.3
type: feature
title: Passkeys
parent: 2
---
```

```yaml
---
id: 2.3.4
type: story
title: Register a passkey
parent: 2.3
---
```

Without the capability, the epic simply has no `parent`. A bug in the same feature is the
story's file with `type: bug`, its own `id` and the same `parent: 2.3`.

## 4. Fields

An item file starts with its front matter: a YAML mapping between a first line `---` and the
next line `---`. Everything after it is the body ([section 5](#5-the-body)).

- **[front-matter]** An item file MUST start with front matter, and the front matter MUST be a
  YAML mapping.

### Required fields

| Field | Format |
|---|---|
| `id` | The item's ID, such as `2.3.4` or `C1`, in the form [section 7](#7-ids-and-filenames) sets for its type. |
| `type` | One of the seven types in [section 2](#2-items-and-types). |
| `title` | A short name for the item, as text on one line. |
| `parent` | The `id` of the item above it, as [section 3](#3-the-hierarchy) requires. |

- **[required-fields]** Every item MUST have `id`, `type` and `title`, and MUST have
  `parent` where section 3 requires one.
- **[title-one-line]** `title` MUST be text on one line, and MUST NOT be empty.

An ID looks like a number but is text: `2.10`, the tenth feature of epic `2`, is not `2.1`.
YAML reads an unquoted `2.10` as the number 2.1, so tools must not let it.

- **[read-as-text]** A tool MUST read `id`, `parent`, `sprint` and `release`, and `spec` in
  `config.yml`, as the text written in the file, never as numbers.

Writers MAY quote these values (`id: "2.10"`); tools SHOULD write them unquoted, as people
do.

### Optional fields

Any item MAY have any of these. Tools use them for planning and views; a backlog is valid
without them.

| Field | Format | Meaning |
|---|---|---|
| `rank` | A number, such as `200` or `150.5` | Order within a status folder: lowest first. Items without a `rank` follow, in ID order. |
| `sprint` | The name of a sprint file, such as `2026-S24` | The sprint the item is planned for ([section 6](#6-sprints-and-people)). |
| `estimate` | A number, zero or more | The size of the work, in the backlog's unit: points unless configured as hours or days. |
| `assignee` | A handle, such as `ana-silva` | Who is doing it ([section 6](#6-sprints-and-people)). |
| `release` | Text, such as `0.1.0` | The release the item ships in. |
| `due` | A date, `YYYY-MM-DD` | When it is due. |
| `repo` | A repository name, such as `backlog-cli`, or a list of them | The repository the work happens in, when a backlog serves several ([section 9](#9-configuration-and-scale)). |

A *handle* is a short name for a person: lowercase letters, digits, `-`, `_` and `.`,
starting with a letter or digit, such as `ana-silva` or `jo-smith`.

- **[field-format]** An optional field in the table, when present, MUST have the format the
  table gives it.
- **[sprint-exists]** A `sprint` MUST name a sprint file that exists in the backlog.
- **[no-email]** Front matter and comment lines MUST NOT contain an email address; people
  are named by handle.

### Fields the spec doesn't know

A backlog MAY add fields of its own, such as `customer: acme` or `risk: high`.

- **[keep-unknown]** A tool that rewrites an item MUST keep every field it does not know,
  with its value unchanged.

A tool SHOULD also keep the order of the fields, and it MUST NOT change the parts of the body
it was not asked to change.

## 5. The body

The body is Markdown, written freely. Nothing in it is required, and a body MAY be empty.

For story-level items, this shape is RECOMMENDED, because it reads the same in every backlog
and tools can find its parts:

```markdown
**As a** <who>, **I want to:**
- <what they want>

**So that:**
- <why, written so it can be checked>

## Summary
<a short summary, for someone who will not read the rest>

## As built
<what was actually done, written when the work is finished>

## Comments
- <YYYY-MM-DD> @<handle>: <comment>
```

`## Comments` comes last. Each comment is one list item, added at the end, oldest first, and
MAY continue on indented lines below it.

- **[comment-line]** Each top-level list item in a `## Comments` section MUST start with a
  date and a handle, as `- YYYY-MM-DD @handle: `.

### A complete story

Every field and section below is defined above. This is one valid file:

```markdown
---
id: 2.3.4
type: story
title: Register a passkey
parent: 2.3
rank: 200
sprint: 2026-S24
estimate: 3
assignee: jo-smith
release: 0.2.0
due: 2026-12-01
---

**As a** returning user, **I want to:**
- register a passkey from my account page

**So that:**
- I can sign in without a password
- Verify a registered passkey signs me in on the same device

## Summary
Adds passkey registration to the account page.

## As built

## Comments
- 2026-11-20 @jo-smith: started; the browser API needs HTTPS, so testing is on beta.
- 2026-11-21 @ana-silva: reviewed.
```

## 6. Sprints and people

Both are optional. A backlog that uses neither is complete.

### Sprints

A sprint is a file in the backlog's `sprints/` folder, named for the sprint, such as
`sprints/2026-S24.md`. Its name is the filename without `.md`, and items name it in their
`sprint` field. The front matter holds its dates and goal; the body is free.

```markdown
---
start: 2026-11-16
end: 2026-11-27
goal: Passkeys on beta
---
```

- **[sprint-dates]** A sprint file MUST have `start` and `end`, each a date `YYYY-MM-DD`, and
  `end` MUST NOT be before `start`.
- **[sprint-no-list]** A sprint file MUST NOT list its items; an item names its sprint, and
  that is the only link.

`goal` is optional text.

### People

`people.yml`, at the top of the backlog, MAY map handles to the people they name. Every key
is a handle, and each entry has a display `name` and, optionally, the person's username on
the git host as `forge`:

```yaml
ana-silva:
  name: Brandon Castagna
  forge: brandon-castagna
jo-smith:
  name: Jo Smith
```

- **[people-format]** Each key in `people.yml` MUST be a handle, and each entry MUST have a
  `name`.
- **[people-no-email]** `people.yml` MUST NOT contain an email address.

An `assignee` or a comment's handle need not appear in `people.yml`; a tool shows the handle
itself when it has no name for it.

## 7. IDs and filenames

### The form of an ID

IDs are numbers, and the number of parts gives the type:

| Type | ID | Example |
|---|---|---|
| epic | one number | `2` |
| feature | its epic's number, a dot, a number | `2.3` |
| story-level | its feature's ID, a dot, a number | `2.3.4` |
| capability | `C` and a number | `C1` |

Each number is a whole number written without leading zeros. Epic `0` is kept for unplanned
work (below); every other number starts at `1`.

- **[id-form]** An item's `id` MUST have the form the table gives for its type.
- **[id-unique]** Each item's `id` MUST be different from every other item's `id` in the backlog.

### Numbering is the build order

A new item takes the next number under the item it is filed under: one more than the highest
number ever used there, counting items since deleted. So `2.3.5` is filed after `2.3.4`, and
reading the IDs in order (`1`, `1.1`, `1.1.1`, …, `2`, `2.1`, …) is the order the work was
planned in. A capability takes the next `C` number in the backlog.

- **[id-new]** A new item's ID MUST be the ID of the item it is filed under, a dot, and the
  next number there; a new epic or capability takes the next number in the backlog.
- **[id-forever]** An item's ID MUST NOT change once the item exists, and a number MUST NOT be
  used again after its item is deleted.

Git history keeps deleted items, so the highest number ever used is the highest in the
backlog's history. The one exception to [id-forever] is a duplicate (below).

### What an ID tells you

Dropping the last number of an ID gives the item it was filed under, and the filenames along
the way spell out what it is: `2.3.4` is in `2-build › 2.3-cli › 2.3.4-ob-move`.

An ID records where an item was **filed**; its `parent` says where it **is**. The two agree
until the item is moved to another parent. Then the `parent` field is the link, the ID keeps
its number, and the ID's first numbers show where the item came from. Section 3 still holds:
a link is written in one place, the `parent` field.

### Filenames

An item's file is named for its ID and a slug, a few words saying what it is:
`<id>-<slug>.md`, such as `2.3.4-ob-move.md`, `2-build.md` or `C1-identity.md`.

- **[filename]** An item's file MUST be named `<id>-<slug>.md`, with its `id` exactly as in
  its front matter.
- **[slug-form]** A slug MUST be lowercase letters and digits, in words joined by single dashes.

The slug MAY be edited at any time (a `git mv` renames the file); the number does not change.

### Referring to an item

Refer to an item by its ID alone: `2.3.4`, not its filename, since the slug can change. To
refer to an item in another backlog, put that backlog's `name` (from its configuration) in
front with a slash: `notes/2.3.4`.

### Unplanned work: epic `0`

A bug or task that turns up outside the plan still belongs to a feature. It goes to epic `0`,
*unplanned work*, which has one feature per month, numbered in month order and slugged by the
month: `0.1-2026-11`, `0.2-2026-12`, and so on.

- **[epic-zero]** Epic `0` MUST hold only monthly features, each slugged `YYYY-MM`, with their
  numbers in the order of their months.

To file a stray, create its file in this month's feature: for example `0.2.1-login-loop.md`
with `parent: 0.2`. If this is the first stray of the month, create the month's feature first,
and the first time ever, epic `0` too:

```yaml
---
id: 0
type: epic
title: Unplanned work
---
```

```yaml
---
id: 0.2
type: feature
title: Unplanned, 2026-12
parent: 0
---
```

When the stray turns out to belong to a planned feature, change its `parent` to that
feature. It keeps its ID, such as `0.2.1`, which records that it arrived as unplanned work.

### Duplicates

Two people working on separate branches can both file the next number, such as `2.3.5`. When
the branches merge, the backlog has two items with that ID and breaks [id-unique]. The fix:
the item added later in git history takes the next free number under the same item, and its
file is renamed to match. A tool SHOULD offer to do this; it is the only time an ID changes.

## 8. Layout and lifecycle

### The folders

A backlog is one folder, usually `backlog/` at the top of a repository. Inside it, every file
has one place:

```
backlog/
├── config.yml          required: the backlog's settings
├── people.yml          optional: handles and names (section 6)
├── capabilities/       C1-identity.md
├── epics/              0-unplanned.md, 2-build.md
├── features/           0.1-2026-11.md, 2.3-cli.md
├── sprints/            optional: 2026-S24.md (section 6)
└── stories/            story, bug, spike and task files, by status:
    ├── todo/           2.3.5-ob-list.md
    ├── doing/          2.3.4-ob-move.md
    └── done/           2.3.3-ob-validate.md, or done/2026-11/2.3.3-ob-validate.md
```

Folders that would be empty MAY be left out.

- **[config-file]** A backlog MUST have a `config.yml` at its top.
- **[item-folder]** Capabilities MUST be in `capabilities/`, epics in `epics/` and features in
  `features/`; story-level items MUST be directly in `stories/todo/` or `stories/doing/`, or
  anywhere under `stories/done/`.

### Status is the folder

A story-level item's status is the folder it is in:

| Folder | Status |
|---|---|
| `stories/todo/` | not started |
| `stories/doing/` | in progress |
| `stories/done/` | finished |

Changing status is moving the file, and nothing else: no field changes, and no other file is
touched. With git:

```sh
git mv backlog/stories/todo/2.3.4-ob-move.md backlog/stories/doing/
git commit -m "2.3.4 doing"
```

The commit records who moved it and when, so git history is the item's timeline.

- **[no-stored-status]** An item MUST NOT have a `status` or `progress` field; both are worked
  out from the folders.

### Only story-level items move

Capabilities, epics and features stay in their folders for good. Their progress is worked out
from the story-level items below them:

- a **feature** is done when it has story-level items and all of them are in `done/`, and in
  progress when any of them is in `doing/` or `done/`;
- an **epic** is done when all its features are done, and a **capability** when all its epics
  are;
- a tool MAY show progress as a count (`3 of 5 done`), or weighted by `estimate`.

Nothing is stored that can be worked out: not progress, not a list of children, not an index
of the backlog. A backlog is always up to date because there is nothing to keep in sync.

### Order

Items are listed by ID, comparing each part as a number: `2.9` before `2.10`, and `2.3.4`
before `2.3.10`. Within a status folder, items with a `rank` come first, lowest rank first,
and the rest follow in ID order ([section 4](#4-fields)).

### Done subfolders

`stories/done/` grows forever, so it MAY be split into subfolders with any names, at any
depth: by month, by release, or by sprint. A backlog can set `done-folders: month` in its
configuration so tools file finished items into `done/YYYY-MM/`, by the month they were
finished. Moving an item between `done/` subfolders does not change its status.

### Pruning

Old items MAY be removed with `git rm` to keep the backlog small. Git history still has them:
a tool can show a pruned item from its last commit, and its ID stays used for good
([id-forever]), so the next number skips it.

- **[prune-children]** An item MUST NOT be pruned while items that name it as `parent` remain.

So prune finished stories first, and a feature or epic only after everything under it.

## 9. Configuration and scale

### config.yml

`config.yml` sits at the top of the backlog. Two lines are enough:

```yaml
spec: 0.1
name: my-app
```

| Key | Required | Format | Meaning |
|---|---|---|---|
| `spec` | yes | `MAJOR.MINOR`, such as `0.1` | The version of this specification the backlog follows. |
| `name` | yes | lowercase letters, digits and dashes, such as `my-app` | The backlog's name: other backlogs refer to its items as `my-app/2.3.4`. |
| `estimate` | no | `points`, `hours` or `days` | The unit of every `estimate`. Points if absent. |
| `done-folders` | no | `month` | Tools file finished items into `stories/done/YYYY-MM/`. Directly in `done/` if absent. |
| `repos` | no | repository name → clone URL | The repositories this backlog's work happens in (T3). |
| `backlogs` | no | backlog name → clone URL | The other backlogs this one gathers (T4). |

- **[config-required]** `config.yml` MUST have `spec` and `name`, and every key in the table
  MUST have the format it gives.

A tool MUST keep keys it does not know, as for item fields ([keep-unknown]).

### The four tiers

A backlog grows through four tiers. Each one adds to the last; none changes an item file.

| Tier | What it is | What changes |
|---|---|---|
| **T1** | A backlog inside the code repository it plans: `my-app/backlog/` | Two lines of `config.yml`. |
| **T2** | A backlog in a repository of its own: `my-app-backlog/backlog/` | Nothing but where the folder lives: move `backlog/` into the new repository. |
| **T3** | One backlog for an application built in several repositories | `repos:` in `config.yml`, and a `repo` field on items that need one. |
| **T4** | A portfolio that gathers several backlogs | `backlogs:` in `config.yml`; their names prefix their items' IDs. |

A T3 backlog's configuration:

```yaml
spec: 0.1
name: open-backlog
repos:
  backlog-cli: git@github.com:open-backlog/backlog-cli.git
  backlog-board: git@github.com:open-backlog/backlog-board.git
```

and a story that names one, or a list for work that spans two:

```yaml
repo: backlog-cli
```

```yaml
repo: [backlog-cli, backlog-board]
```

- **[repo-listed]** Each name in an item's `repo` MUST be a key of `repos:` in `config.yml`.

A T4 portfolio lists its backlogs the same way, and refers to their items with the backlog's
name in front, such as `notes/2.3.4`:

```yaml
spec: 0.1
name: portfolio
backlogs:
  notes: git@github.com:example/notes-backlog.git
  photos: git@github.com:example/photos-backlog.git
```

A key in `backlogs:` SHOULD be that backlog's own `name`.

### Other repositories are local clones

A backlog never fetches anything. A tool finds each repository in `repos:` and each backlog in
`backlogs:` as a clone on the same disk: by default a folder with the key's name beside the
repository that holds this backlog, such as `../backlog-cli/`, with a listed backlog's files in
its `backlog/` folder. The clone URL tells people where to clone from; tools do not use it.

- **[local-only]** A tool MUST NOT need network access or git submodules to read a backlog.
- **[missing-clone]** When a listed repository or backlog has no local clone, a tool MUST warn
  and carry on, and MUST NOT fail.

So a T3 or T4 backlog works with whatever is cloned: everything about this backlog is always
there, and what other clones add is shown when they are present.
