# Versioning

The specification follows [Semantic Versioning](https://semver.org): `MAJOR.MINOR.PATCH`.

| Change | Version |
|---|---|
| Breaking: an existing valid backlog becomes invalid, or a file means something different | MAJOR |
| A new optional rule, field or file; nothing existing changes meaning | MINOR |
| Wording, examples and fixes that change no rule | PATCH |

## Before 1.0

Versions `0.x` may break between minor releases (0.1 → 0.2). Each breaking change is
marked **breaking** in [CHANGELOG.md](CHANGELOG.md) with how to update a backlog.

## From 1.0

A breaking change only ever comes with a new major version. Within a major version, a
backlog that was valid stays valid.

## A release is a tag

Every release of the specification is a git tag named `spec-v<version>`, for example
`spec-v0.1.0`, on `main`. Nothing untagged is a release: the text on `main` between tags is
a draft of the next version. Each release has:

- a GitHub release with notes taken from CHANGELOG.md, and the whitepaper PDF attached;
- its JSON Schema at a permanent address, `https://openbacklog.org/schema/<major.minor>/`,
  which never changes after release;
- the version in a backlog's `config.yml` (`spec: 0.1`), so a tool knows which rules apply.

## What earns 1.0

The specification becomes 1.0 when both are true:

- it has been used on real backlogs for **3 to 6 months without needing a breaking
  change**;
- **every open breaking-change issue is resolved or deferred** to a later major version.

A second, independent implementation of the tools is a goal, not a requirement.
