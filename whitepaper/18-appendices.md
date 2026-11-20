# Appendices

## A. Field reference

A summary of every field and key in version 0.1. The specification's sections 3 and 5 are the
definition [@openbacklog-spec]; where this table and the specification differ, the specification
wins. The specification's MUST, MUST NOT, SHOULD and MAY are used as in BCP 14
[@rfc2119; @rfc8174].

**Item front matter**

| Field | Required | Format | Notes |
|---|---|---|---|
| `id` | yes | `2` epic, `2.3` feature, `2.3.4` story-level, `C1` capability | read as text; never changes |
| `type` | yes | `capability`, `epic`, `feature`, `story`, `bug`, `spike` or `task` | |
| `title` | yes | text on one line, not empty | |
| `parent` | by type | the `id` of a feature (story-level), an epic (feature) or a capability (epic, optional) | none on a capability |
| `rank` | no | a number | lowest first within its folder |
| `sprint` | no | the name of a file in `sprints/` | read as text |
| `estimate` | no | a number, zero or more | in the unit set by `estimate` in `config.yml` |
| `assignee` | no | a handle | never an email address |
| `release` | no | text, such as `0.1.0` | read as text |
| `due` | no | a date, `YYYY-MM-DD` | |
| `repo` | no | a key of `repos:`, or a list of them | T3 |
| any other | no | anything | kept by tools; never `children`, `status` or `progress` |

**`config.yml`**

| Key | Required | Format |
|---|---|---|
| `spec` | yes | the specification version, `MAJOR.MINOR`, read as text |
| `name` | yes | lowercase letters, digits and dashes |
| `estimate` | no | `points` (default), `hours` or `days` |
| `done-folders` | no | `month`: tools file finished items in `stories/done/YYYY-MM/` |
| `repos` | no | repository name → clone URL (T3) |
| `backlogs` | no | backlog name → clone URL (T4) |

**Other files**

| File | Contents |
|---|---|
| `sprints/<name>.md` | front matter with `start` and `end` (`YYYY-MM-DD`) and an optional `goal`; never a list of items |
| `people.yml` | handle → `name`, and an optional `forge` username |

## B. The JSON Schema

The schema checks the rules a single file can break [@jsonschema07]. It is published at
`https://openbacklog.org/schema/0.1/` and kept in the specification's repository in
`schema/0.1/`; the copies below are checked against those files on every push. Rules that span
files (a parent exists, IDs are unique, sprints and repositories are listed) need a validator.

**`item.schema.json`**, for an item's front matter:

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "$id": "https://openbacklog.org/schema/0.1/item.schema.json",
  "title": "Open Backlog 0.1 item front matter",
  "description": "The front matter of a capability, epic, feature, story, bug, spike or task file. Checks what one file can show; rules that span files (parents, unique IDs, folders) are for a validator. See spec/README.md.",
  "type": "object",
  "required": ["id", "type", "title"],
  "properties": {
    "id": {
      "description": "The item's ID: epic 2, feature 2.3, story-level 2.3.4, capability C1. Text, not a number: 2.10 is not 2.1 [read-as-text].",
      "type": ["string", "number"]
    },
    "type": {
      "description": "One of the seven types [fields].",
      "enum": ["capability", "epic", "feature", "story", "bug", "spike", "task"]
    },
    "title": {
      "description": "A short name, on one line [fields].",
      "type": "string",
      "pattern": "^[^\\n]*\\S[^\\n]*$"
    },
    "parent": {
      "description": "The ID of the item above: a feature for story-level items, an epic for features, a capability (optional) for epics.",
      "type": ["string", "number"]
    },
    "rank": { "description": "Order within a status folder, lowest first.", "type": "number" },
    "sprint": { "description": "The name of a file in sprints/, such as 2026-S24.", "type": "string" },
    "estimate": { "description": "The size of the work, in the backlog's unit.", "type": "number", "minimum": 0 },
    "assignee": {
      "description": "A handle: lowercase letters, digits, -, _ and . [fields].",
      "type": "string",
      "pattern": "^[a-z0-9][a-z0-9._-]*$"
    },
    "release": { "description": "The release the item ships in, such as 0.1.0.", "type": ["string", "number"] },
    "due": { "description": "A date, YYYY-MM-DD.", "type": "string", "pattern": "^\\d{4}-\\d{2}-\\d{2}$" },
    "repo": {
      "description": "A repository listed under repos: in config.yml, or a list of them [repo-listed].",
      "oneOf": [
        { "type": "string", "minLength": 1 },
        { "type": "array", "items": { "type": "string", "minLength": 1 }, "minItems": 1 }
      ]
    },
    "children": { "description": "Never listed: children are worked out [not-stored].", "not": {} },
    "status": { "description": "Never stored: the folder is the status [not-stored].", "not": {} },
    "progress": { "description": "Never stored: progress is worked out [not-stored].", "not": {} }
  },
  "additionalProperties": {
    "description": "Fields of a backlog's own are allowed and kept by tools [keep-unknown], but hold no email address [no-email].",
    "not": { "type": "string", "pattern": "[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}" }
  },
  "allOf": [
    {
      "if": { "properties": { "type": { "enum": ["story", "bug", "spike", "task"] } }, "required": ["type"] },
      "then": {
        "required": ["parent"],
        "properties": {
          "id": { "pattern": "^(0|[1-9]\\d*)\\.[1-9]\\d*\\.[1-9]\\d*$" },
          "parent": { "pattern": "^(0|[1-9]\\d*)\\.[1-9]\\d*$" }
        }
      }
    },
    {
      "if": { "properties": { "type": { "const": "feature" } }, "required": ["type"] },
      "then": {
        "required": ["parent"],
        "properties": {
          "id": { "pattern": "^(0|[1-9]\\d*)\\.[1-9]\\d*$" },
          "parent": { "type": ["string", "integer"], "pattern": "^(0|[1-9]\\d*)$" }
        }
      }
    },
    {
      "if": { "properties": { "type": { "const": "epic" } }, "required": ["type"] },
      "then": {
        "properties": {
          "id": { "type": ["string", "integer"], "minimum": 0, "pattern": "^(0|[1-9]\\d*)$" },
          "parent": { "type": "string", "pattern": "^C[1-9]\\d*$" }
        }
      }
    },
    {
      "if": { "properties": { "type": { "const": "capability" } }, "required": ["type"] },
      "then": {
        "properties": {
          "id": { "type": "string", "pattern": "^C[1-9]\\d*$" },
          "parent": false
        }
      }
    }
  ]
}
```

**`config.schema.json`**, for `config.yml`:

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "$id": "https://openbacklog.org/schema/0.1/config.schema.json",
  "title": "Open Backlog 0.1 config.yml",
  "description": "A backlog's config.yml [config]. See spec/README.md, section 5.",
  "type": "object",
  "required": ["spec", "name"],
  "properties": {
    "spec": {
      "description": "The spec version the backlog follows, MAJOR.MINOR. Text, not a number [read-as-text].",
      "type": ["string", "number"],
      "pattern": "^\\d+\\.\\d+$"
    },
    "name": {
      "description": "The backlog's name, used by other backlogs as name/2.3.4.",
      "type": "string",
      "pattern": "^[a-z0-9]+(-[a-z0-9]+)*$"
    },
    "estimate": { "description": "The unit of every estimate. Points if absent.", "enum": ["points", "hours", "days"] },
    "done-folders": { "description": "File finished items into stories/done/YYYY-MM/.", "const": "month" },
    "repos": {
      "description": "Repository name to clone URL (T3).",
      "type": "object",
      "additionalProperties": { "type": "string" }
    },
    "backlogs": {
      "description": "Backlog name to clone URL (T4).",
      "type": "object",
      "additionalProperties": { "type": "string" }
    }
  }
}
```

## C. Glossary

- **Backlog**: a folder, usually `backlog/`, holding `config.yml` and item files; valid when it
  breaks none of the specification's rules.
- **Item**: one file in a backlog: a capability, epic, feature or story-level item.
- **Story-level item**: a `story`, `bug`, `spike` or `task`: the work itself, the only items with
  a status.
- **Capability**: an optional lasting ability of the product, above epics.
- **Epic**: a large goal, delivered by its features. Epic `0` holds unplanned work.
- **Feature**: something a user can do or see, delivered by its stories.
- **Parent**: the one item an item points up at, in its `parent` field.
- **Children**: the items whose `parent` is an item's ID; worked out, never written.
- **Status**: the folder a story-level item is in: `todo`, `doing` or `done`.
- **Progress**: the share of story-level items under an item that are done; worked out.
- **ID**: an item's number, whose parts show where it was filed (`2.3.4`); never changes.
- **Full ID**: a backlog's name, a slash and an ID (`notes/1.1.1`), used from other backlogs.
- **Slug**: the words after the ID in a filename (`ob-move` in `2.3.4-ob-move.md`); may change.
- **Front matter**: the YAML mapping between two `---` lines at the top of an item file.
- **Handle**: a short lowercase name for a person (`jo-smith`), used instead of an email address.
- **Rank**: an optional number that orders items within a folder.
- **Sprint**: an optional time-box, a file in `sprints/` that items name.
- **Tier**: T1 to T4, how far a backlog reaches: one repository, its own repository, an
  application's repositories, a portfolio of backlogs.
- **Local clone**: a repository or backlog checked out beside the one holding this backlog,
  where tools look for it.
- **Pruning**: removing old finished items with `git rm`; git history keeps them.
- **Solution document**: an optional design document beside the backlog, holding the design and a
  master plan; a practice, not a rule.

## D. References

::: {#refs}
:::
