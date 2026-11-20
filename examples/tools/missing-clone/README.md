# Tool check: [missing-clone] and [local-only]

A portfolio whose `config.yml` lists two backlogs, `notes` and `photos`. Only `notes` is
cloned beside it; `photos` is not.

Run a tool from `portfolio/`. A correct tool warns that `photos` has no local clone, shows
`notes/1.1.1`, and exits successfully. It does not fail, and it does not try to clone
`photos` or reach the network.
