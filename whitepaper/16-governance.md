# Governance

The specification should stay small, so most of governing it is deciding what not to add. The
full text is `CONTRIBUTING.md` and `VERSIONING.md` in the specification's repository.

**How a change is made.** First an issue, labeled *spec change*, *bug* or *idea*, where the change
is discussed (a typo fix can skip this). Then a pull request making that one change. A rule change
is accepted when it keeps the convention simple, works with a text editor and git, and has been
tried on a real backlog. It also adds a changelog line (marked *breaking* if it is), updates the
example backlogs, and updates the JSON Schema if needed; a check on every push fails if the schema
and the examples disagree. Today the author approves every pull request; more maintainers are a
goal once others use the convention.

**Versions** follow Semantic Versioning [@semver]:

| Change | Version |
|---|---|
| Breaking: a valid backlog becomes invalid, or a file means something else | MAJOR |
| A new optional rule, field or file | MINOR |
| Wording, examples or fixes that change no rule | PATCH |

Before 1.0, a minor version may break, and the changelog says how to update. A release is a tag,
such as `spec-v0.1.0`, with the whitepaper PDF and a JSON Schema at
`https://openbacklog.org/schema/<major.minor>/` that never changes. A backlog names its version
with `spec:` in `config.yml`.

**1.0** comes after three to six months of use on real backlogs with no breaking change needed,
and every open breaking-change issue resolved or deferred. A second, independent implementation
is a goal, not a requirement.

**Licenses.** The specification and this paper are CC-BY-4.0. The schema, examples and code are
MIT. Contributions take the license of the files they change.
