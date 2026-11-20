# Versioning

The spec uses [Semantic Versioning](https://semver.org), `MAJOR.MINOR.PATCH`:

| Change | Version |
|---|---|
| Breaking: a valid backlog becomes invalid, or a file means something else | MAJOR |
| A new optional rule, field or file | MINOR |
| Wording, examples or fixes that change no rule | PATCH |

Before 1.0, a minor version may break (0.1 → 0.2), and CHANGELOG.md marks each breaking
change with how to update a backlog. From 1.0, only a major version breaks.

**A release is a tag**, `spec-v<version>` on `main`, such as `spec-v0.1.0`; the text between
tags is a draft. Each release has a GitHub release with the whitepaper PDF, and its JSON
Schema at `https://openbacklog.org/schema/<major.minor>/`, which never changes after release.
A backlog says which version it follows with `spec:` in `config.yml`.

**1.0** comes when the spec has been used on real backlogs for 3 to 6 months without needing a
breaking change, and every open breaking-change issue is resolved or deferred. A second,
independent implementation is a goal, not a requirement.
