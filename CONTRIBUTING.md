# Contributing

The convention is meant to stay small, so most of the work is deciding what not to add.

1. **Open an issue** saying what you want to change and why, labelled **spec change** (a
   rule), **bug** (a mistake or broken example) or **idea** (anything else). Wording and
   edge cases are settled there. Typo fixes can skip this step.
2. **Open a pull request** that links the issue and makes that one change.

The author (@brandon-castagna) approves every pull request. A rule change is accepted when it
keeps the convention simple, works with only a text editor and git, and has been tried on a
backlog. Its pull request also:

- adds a line to [CHANGELOG.md](CHANGELOG.md), marked **breaking** if it is;
- updates `examples/`, including the invalid backlog for the rule;
- updates the JSON Schema in `schema/` if it touches front matter or `config.yml`
  (`npm test` checks that the schema and the examples agree).

Contributions are licensed like the files they change: prose under [CC-BY-4.0](LICENSE),
code, schema and examples under [MIT](LICENSE-CODE).
