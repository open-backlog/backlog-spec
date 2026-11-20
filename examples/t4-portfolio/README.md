# T4: a portfolio of backlogs

Three repositories side by side, as they would be cloned on one disk:

```
t4-portfolio/
├── portfolio/backlog/    lists the other two under backlogs:
├── notes/backlog/        a T1 backlog
└── photos/backlog/       a T1 backlog
```

The portfolio's `config.yml` names `notes` and `photos` under `backlogs:`. A tool finds each
as `../notes/backlog/` and `../photos/backlog/`, beside the portfolio's repository, and shows
their items as `notes/1.1.1` and `photos/1.1.2`. The portfolio has a little work of its own,
shared sign-in, whose stories point at the apps' stories by those full IDs.

Neither app backlog knows it is in a portfolio: joining one changes no file of theirs.
