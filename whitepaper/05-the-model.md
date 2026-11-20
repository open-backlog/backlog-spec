# The model

The model is the agile hierarchy most teams already use, cut down to what the files need. The
rules are in sections 2 and 3 of the specification [@openbacklog-spec].

```
capability            (optional)
  └── epic
        └── feature
              └── story · bug · spike · task
```

- A **capability** is a lasting ability of the product, such as billing. Most backlogs never need
  one.
- An **epic** is a large goal, delivered by its features.
- A **feature** is something a user can do or see, delivered by its stories.
- A **story**, **bug**, **spike** or **task** is the work itself. Only these *story-level* items
  move between `todo/`, `doing/` and `done/`; everything above takes its progress from them.

**Links point up.** Each item names its parent in `parent`, the only link in the model. Children
are never listed: they are the items that name this one. So there is one fact to change when a
story moves to another feature, no loop can be written, and two people adding stories to one
feature on two branches never conflict.

**Progress is worked out.** A feature is done when all its story-level items are in `done/`, and
in progress when any has started; an epic and a capability follow from theirs. A tool may weight
items by `estimate`.

**IDs show the plan.** An ID is its parent's ID plus a number: `2.3.4` is the fourth story filed
under feature `2.3`. The depth gives the type and the numbers give the planned order. An ID never
changes, so references to it keep working; if a story moves, only its `parent` changes.

**Epic 0 holds unplanned work.** A production bug or a stray chore goes to epic `0`, in that
month's feature (`0.1-2026-11`, `0.2-2026-12`). It is filed at once, and moved later by changing
its `parent` if it belongs to planned work.

## Familiar names

| Open Backlog | Jira | SAFe |
|---|---|---|
| capability | a level above epic, such as Initiative (Premium plans) | no direct match: SAFe's capability sits between epic and feature |
| epic | Epic | epic |
| feature | no standard level | feature |
| story, bug | Story, Bug | story |
| spike, task | Task, or a Story marked as a spike | enabler story |
| status (the folder) | workflow status | — |
| sprint | Sprint | iteration |
| estimate | Story points | story points |
| release | Fix version | release |

Sources: [@jira-hierarchy; @safe-backlog].

## Solution documents

A backlog says *what* to build and in what order, not *how*. The pilot behind this paper keeps the
how (scope, decisions, technical design and a master plan of stories) in a solution document in
`docs/`. Each story file links to it. The story files stay the source of truth for the work, and
the document holds the design. This is a practice, not a rule.
