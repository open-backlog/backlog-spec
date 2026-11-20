# Changelog

Every change to a normative rule in `spec/` is listed here, newest first. A **breaking**
change makes an existing valid backlog invalid or changes what it means; see
[VERSIONING.md](VERSIONING.md).

## Unreleased (0.1, draft)

- Added: the item types `capability`, `epic`, `feature`, and the story-level `story`,
  `bug`, `spike`, `task` [type-known].
- Added: the hierarchy, linked by `parent` pointing up only: story-level → feature
  [parent-feature], feature → epic [parent-epic], epic → capability, optional
  [parent-capability], capability → none [parent-none]; a parent must exist
  [parent-exists]; children are never listed [no-children].
- Added: front matter [front-matter]; required fields `id`, `type`, `title`, `parent`
  [required-fields], [title-one-line]; IDs and similar values are text, not numbers
  [read-as-text]; optional fields `rank`, `sprint`, `estimate`,
  `assignee`, `release`, `due`, `repo` with their formats [field-format], [sprint-exists];
  no email addresses [no-email]; unknown fields are kept by tools [keep-unknown].
- Added: the body, the recommended story shape and comment lines [comment-line].
- Added: sprint files [sprint-dates], [sprint-no-list] and `people.yml` [people-format],
  [people-no-email].
- Added: the repository, its licenses, the contributing guide and the versioning policy.
