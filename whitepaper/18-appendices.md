# Appendices

## A. Field reference

Every field and key in version 0.1, from sections 3 and 5 of the specification
[@openbacklog-spec], which wins if they differ. Its MUST, SHOULD and MAY are used as in BCP 14
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

The schema checks the rules one file can break [@jsonschema07]; rules across files need a
validator. It is published at `https://openbacklog.org/schema/0.1/`, and these copies are checked
against the specification's `schema/0.1/` on every push.

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

- **Backlog**: a folder, usually `backlog/`, with `config.yml` and item files.
- **Item**: one file: a capability, epic, feature or story-level item.
- **Story-level item**: a story, bug, spike or task; the only items with a status.
- **Status**: the folder a story-level item is in: `todo`, `doing` or `done`.
- **Parent**: the item an item points up at. **Children** are worked out, never written.
- **Progress**: the share of story-level items under an item that are done; worked out.
- **ID**: an item's number, such as `2.3.4`; never changes. A **full ID** adds the backlog's name:
  `notes/1.1.1`.
- **Slug**: the words after the ID in a filename; may change.
- **Front matter**: the YAML between two `---` lines at the top of an item file.
- **Handle**: a short lowercase name for a person, used instead of an email address.
- **Tier**: T1 to T4, from one repository to a portfolio.
- **Local clone**: a repository checked out beside this one, where tools look for it.
- **Pruning**: removing old finished items with `git rm`.

## D. References

::: {#refs}
:::
