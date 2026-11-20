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
- Added: the layout, with `config.yml` required [config-file] and each item in its type's
  folder [item-folder]; status is the folder (`todo`, `doing`, `done`) and never stored
  [no-stored-status]; only story-level items move; progress, order, `done/` subfolders and
  pruning [prune-children].
- Added: IDs by depth (epic `2`, feature `2.3`, story-level `2.3.4`, capability `C1`)
  [id-form], unique [id-unique], numbered in build order [id-new], never changed or reused
  [id-forever]; filenames `<id>-<slug>.md` [filename], [slug-form]; references by ID and
  across backlogs as `name/2.3.4`; epic `0` with monthly features for unplanned work
  [epic-zero]; duplicates from parallel branches renumbered, later one first.
- Added: the body, the recommended story shape and comment lines [comment-line].
- Added: sprint files [sprint-dates], [sprint-no-list] and `people.yml` [people-format],
  [people-no-email].
- Added: the repository, its licenses, the contributing guide and the versioning policy.
