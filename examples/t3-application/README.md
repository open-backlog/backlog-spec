# T3: one backlog for an application

A shop built in three repositories (`shop-api`, `shop-web`, `shop-ios`), planned in one
backlog. It shows most of the convention:

- `repos:` in `config.yml`, and a `repo` on each story: one name, or a list for work that
  spans two (`1.2.1`);
- a capability (`C1`) above an epic, and every story-level type but `task`;
- `people.yml`, a sprint, `estimate`, `rank`, `release`, `due` and comments;
- unplanned work: epic `0`, this month's feature `0.1-2026-11`, and a stray bug in it;
- `done-folders: month`, so finished work is in `stories/done/2026-11/`.

```
backlog/
├── config.yml
├── people.yml
├── capabilities/C1-payments.md
├── epics/0-unplanned.md, 1-checkout.md
├── features/0.1-2026-11.md, 1.1-cart.md, 1.2-pay-by-card.md
├── sprints/2026-S24.md
└── stories/
    ├── todo/0.1.1-tax-rounding.md, 1.2.1-card-form.md, 1.2.2-card-spike.md
    ├── doing/1.1.2-cart-page.md
    └── done/2026-11/1.1.1-cart-api.md
```
