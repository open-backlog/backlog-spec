# Contributing

Thank you for helping. The convention is meant to stay small, so most of the work is
deciding what *not* to add.

## How a change is proposed

1. **Open an issue first.** Say what you want to change and why: the problem you hit, a
   backlog that the rules handle badly, or a gap. Label it:
   - **spec change**: a change to a rule in `spec/` (anything with MUST, SHOULD or MAY);
   - **bug**: a mistake, contradiction or broken example;
   - **idea**: anything else, including the whitepaper and the examples.
2. **Discuss it in the issue.** Wording, edge cases and whether it breaks existing backlogs
   are settled there, before anyone writes the change.
3. **Open a pull request** that links the issue. Keep it to that one change.

Typo and wording fixes that change no rule can skip straight to a pull request.

## How a change is decided

The author (@brandon-castagna) reviews and approves every pull request. A spec change is
accepted when it keeps the convention simple, works with only a text editor and git, and
has been tried on a real or example backlog.

## Rules for a spec change

- **Every change to a normative rule gets a line in [CHANGELOG.md](CHANGELOG.md)**, under
  *Unreleased*, in the same pull request. Say whether it is *added*, *changed* or
  *removed*, and whether it is **breaking** (an existing valid backlog becomes invalid, or
  means something different).
- Update or add an example in `examples/` that shows the rule, and an invalid example if
  the rule can be broken.
- If the rule touches front matter or `config.yml`, update the JSON Schema in `schema/` in
  the same pull request. `npm test` (and CI) checks that the schema and the examples agree.
- [VERSIONING.md](VERSIONING.md) decides which version number the change ships in.

## Licenses

By contributing you agree that your contribution is licensed like the files it changes:
prose under [CC-BY-4.0](LICENSE), code, schema and examples under [MIT](LICENSE-CODE).
