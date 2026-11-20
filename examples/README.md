# Examples

Small backlogs that show the convention and that tools test against. Each `backlog/` folder
is a complete backlog.

| Folder | What it is |
|---|---|
| [t1-minimal/](t1-minimal/) | The smallest useful backlog: two lines of config, one epic, one feature, three stories. |
| [t3-application/](t3-application/) | One backlog for an app in three repositories, using almost every field and file. |
| [t4-portfolio/](t4-portfolio/) | A portfolio and the two app backlogs it gathers. |
| [invalid/](invalid/) | One backlog per rule a backlog can break, named for the rule, with a README saying what is wrong. |
| [tools/](tools/) | Valid backlogs that a careless tool gets wrong: [read-as-text](tools/read-as-text/), [keep-unknown](tools/keep-unknown/), [local-clones](tools/local-clones/). |

[id-numbers] needs git history, so a test builds its own.

**As test fixtures**, a validator passes when every valid backlog is valid, each
`invalid/<rule>/backlog/` fails with `<rule>` and nothing else, and the `tools/` backlogs
behave as their READMEs say. MIT licensed ([LICENSE-CODE](../LICENSE-CODE)).
