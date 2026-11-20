# Examples

Small backlogs that show the convention, and that tools test against. Each `backlog/`
folder is a complete backlog; read any of them in a minute.

## Valid backlogs

| Example | What it shows |
|---|---|
| [t1-minimal/](t1-minimal/) | The smallest useful backlog: two lines of config, one epic, one feature, three stories. |
| [t3-application/](t3-application/) | One backlog for an app built in three repositories, using almost every field and file. |
| [t4-portfolio/](t4-portfolio/) | A portfolio backlog and the two app backlogs it gathers, side by side. |

## Invalid backlogs

[invalid/](invalid/) has one backlog per rule that a backlog's files can break, named for
the rule: `invalid/parent-feature/`, `invalid/no-email/`, and so on. Each is valid except for
that one rule, and its `README.md` says what is wrong.

## Tool checks

[tools/](tools/) has valid backlogs that a careless tool gets wrong, for the rules about what
tools do:

- [tools/read-as-text/](tools/read-as-text/): IDs `1.1` and `1.10` in one backlog;
- [tools/keep-unknown/](tools/keep-unknown/): fields the spec doesn't define, to survive a rewrite;
- [tools/missing-clone/](tools/missing-clone/): a portfolio with one backlog not cloned.

Four rules need git history or a running tool rather than a folder of files, so a test
builds them itself: [id-new] (the next number counts deleted items), [id-forever] (an ID
never changes), [prune-children] (a parent is pruned last) and [local-only] (no network).

## Using them as test fixtures

Copy this folder into a tool's tests. A validator passes when:

- every backlog under `t1-minimal/`, `t3-application/` and `t4-portfolio/` is valid;
- each `invalid/<rule>/backlog/` fails with `<rule>` and nothing else;
- the `tools/` backlogs are valid and behave as their READMEs say.

The examples are MIT licensed ([LICENSE-CODE](../LICENSE-CODE)), so they can be copied into
any project.
