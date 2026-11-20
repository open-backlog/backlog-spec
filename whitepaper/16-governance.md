# Governance

The specification is meant to stay small, so most of governing it is deciding what not to add.
This section summarises how it changes; `CONTRIBUTING.md` and `VERSIONING.md` in the
specification's repository are the full text.

## How a change is made

1. **An issue first.** Anyone opens an issue saying what should change and why, labelled
   *spec change* (a rule), *bug* (a mistake or a broken example) or *idea* (anything else).
   Wording and edge cases are settled there. A typo fix can skip this step.
2. **Then a pull request** that links the issue and makes that one change.

A rule change is accepted when it keeps the convention simple, still works with only a text
editor and git, and has been tried on a real backlog. Its pull request also adds a line to the
changelog, marked *breaking* if it is; updates the example backlogs, including the invalid
backlog for the rule; and updates the JSON Schema if it touches front matter or `config.yml`. A
check on every push fails when the schema and the examples disagree.

Today the author approves every pull request. That is the honest state of a young project with
one maintainer; a wider group of maintainers is a goal once there are people using the
convention who want to take part in deciding it.

## Versions

The specification uses Semantic Versioning [@semver]:

| Change | Version |
|---|---|
| Breaking: a valid backlog becomes invalid, or a file means something else | MAJOR |
| A new optional rule, field or file | MINOR |
| Wording, examples or fixes that change no rule | PATCH |

Before 1.0 a minor version may break (0.1 to 0.2), and the changelog says how to update a backlog
for each breaking change. From 1.0, only a major version breaks.

A release is a tag on the main branch, `spec-v0.1.0` and so on; text between tags is a draft. Each
release has the whitepaper as a PDF and its JSON Schema at
`https://openbacklog.org/schema/<major.minor>/`, which never changes after release. A backlog
says which version it follows with `spec:` in `config.yml`, so a tool knows which rules to apply.

## What earns 1.0

Version 1.0 comes when the specification has been used on real backlogs for three to six months
without needing a breaking change, and every open breaking-change issue is resolved or deferred.
A second, independent implementation is a goal, not a requirement.

## Licences

The specification and this paper are licensed CC-BY-4.0: anyone may copy, adapt and build on
them, with credit. The JSON Schema, the example backlogs and all code are MIT-licensed, so they
can be copied into projects and tools freely. Contributions take the licence of the files they
change.
