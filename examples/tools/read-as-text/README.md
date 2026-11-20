# Tool check: [read-as-text]

A valid backlog with features `1.1` and `1.10` (1.2 to 1.9 were pruned). YAML reads an
unquoted `1.10` as the number 1.1, so a tool that lets it sees two features with the same ID,
and puts `1.10.1` under the wrong one.

A correct tool reports this backlog as valid, lists `1.1` before `1.10`, and shows
`1.10.1` under `1.10 Export`.
