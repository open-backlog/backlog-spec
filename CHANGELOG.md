# Changelog

Every change to a rule in `spec/`, newest first. A **breaking** change makes a valid backlog
invalid or changes what it means ([VERSIONING.md](VERSIONING.md)).

## 0.1.0 (2026-11-23)

The first release, tagged `spec-v0.1.0`. Its JSON Schema, `schema/0.1/`, is frozen at
https://openbacklog.org/schema/0.1/.

The rules: items and their parents, IDs, files and fields, folders and
status, configuration and the four tiers, and the rules for tools. Twenty rules, with an
example of each broken in `examples/invalid/`, a JSON Schema in `schema/`, and the site at
https://openbacklog.org/spec.

### Simplified (1.4.1)

The draft was rewritten in six sections, and its 35 rules merged into 20. Nothing a backlog
can or must do changed; only the names and the grouping did:

| Before | Now |
|---|---|
| parent-feature, parent-epic, parent-capability, parent-none | parent |
| parent-exists | parent-exists |
| type-known, required-fields, title-one-line, field-format | fields |
| front-matter | front-matter |
| no-children, no-stored-status, sprint-no-list | not-stored |
| no-email, people-no-email | no-email |
| sprint-exists, sprint-dates | sprints |
| people-format | people |
| comment-line | comment-line |
| id-form | id-form |
| id-unique | id-unique |
| id-new, id-forever | id-numbers |
| filename, slug-form | filename |
| epic-zero | epic-zero (month order now follows from id-numbers) |
| config-file, config-required | config |
| item-folder | item-folder |
| repo-listed | repo-listed |
| prune-children | a sentence: pruning a parent first breaks parent-exists |
| read-as-text | read-as-text |
| keep-unknown | keep-unknown (now also unknown config keys and the body) |
| local-only, missing-clone | local-clones |

One clarification: a key in `backlogs:` is the name used for that backlog's items.
