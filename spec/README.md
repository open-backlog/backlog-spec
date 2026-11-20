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

Not written yet in this draft: fields and the body of an item, IDs and filenames, the
folder layout and lifecycle, and configuration and scale.

## 1. Conventions

The key words **MUST**, **MUST NOT**, **SHOULD**, **SHOULD NOT** and **MAY** are to be
interpreted as described in [BCP 14](https://www.rfc-editor.org/info/bcp14) ([RFC
2119](https://www.rfc-editor.org/rfc/rfc2119), [RFC 8174](https://www.rfc-editor.org/rfc/rfc8174))
when, and only when, they appear in all capitals, as shown here.

Each rule is one sentence with a short name in brackets, such as **[parent-feature]**. A
backlog is *valid* when it breaks none of the rules. The names are stable: tools use them in
their messages, and `examples/invalid/` has one backlog per rule.

An item's properties are written as YAML front matter at the top of its file, between two
`---` lines. This document uses only `id`, `type`, `title` and `parent`.

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
