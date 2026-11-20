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
- Added: the repository, its licenses, the contributing guide and the versioning policy.
