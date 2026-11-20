# The model

The model is the agile hierarchy most teams already use, reduced to what the files need. This
section explains its shape; the exact rules are in sections 2 and 3 of the specification
[@openbacklog-spec].

## Four levels

```
capability            (optional)
  └── epic
        └── feature
              └── story · bug · spike · task
```

- A **capability** is a lasting ability of the product, such as identity or billing. Most
  backlogs never need one; a product with many epics uses them to group the epics that build the
  same ability over time.
- An **epic** is a large goal, delivered by its features.
- A **feature** is something a user can do or see, delivered by its stories.
- A **story**, **bug**, **spike** or **task** is the work itself: the only items that are
  started and finished. These four are the *story-level* types, and they differ only in what
  kind of work they name.

Capabilities, epics and features organise the work; story-level items are the work. That split
is the whole of the hierarchy's logic: only story-level items move between `todo/`, `doing/`
and `done/`, and everything above them takes its progress from them.

## Links point up

Each item names its parent in one field, `parent`, and that is the only link in the model. A
feature does not list its stories and an epic does not list its features: an item's children
are the items whose `parent` is its ID. This choice does three things.

- **Nothing to keep in sync.** A list of children is a second copy of the `parent` fields, and
  two copies drift. With one link there is one fact to change: to move a story to another
  feature, change its `parent`.
- **No loops.** A parent is always a level higher, so a cycle cannot be written.
- **Merges stay clean.** Two people adding stories to the same feature on two branches touch two
  new files, not one shared list, so git merges them without a conflict.

Progress follows the same reasoning. A feature is done when all its story-level items are in
`done/`, and in progress when any has started; an epic is done when its features are, and a
capability when its epics are. A tool can count items or weigh them by `estimate`. None of this
is written down, so it is never stale.

## IDs that show the plan

An item's ID is its parent's ID plus a number: feature `2.3` is the third feature filed under
epic `2`, and `2.3.4` is the fourth story filed under it. The depth gives the type, the numbers
give the order the work was planned in, and the filenames spell it out:
`2-build › 2.3-cli › 2.3.4-ob-move`. An ID never changes, so links to it in commits, chats and
other backlogs keep working; if a story later moves to another feature, its `parent` changes and
its ID still records where it was first filed.

## Epic 0: work nobody planned

Real backlogs have strays: a bug found in production, a chore that blocks a release, a request
that fits nowhere yet. Rather than make every stray wait for a place in the plan, the model keeps
epic `0` for them, with one feature per month (`0.1-2026-11`, `0.2-2026-12`). A stray is filed at
once, its month's feature shows how much unplanned work arrived, and when it turns out to belong
to a planned feature, its `parent` changes.

## Familiar names

The levels map onto the terms of the tools and frameworks most teams know. The fit is close but
not exact, and the notes say where it differs.

| Open Backlog | Jira | SAFe | Notes |
|---|---|---|---|
| capability | a custom level above epic, such as Initiative (Premium plans) | no direct match | SAFe's *capability* sits between epic and feature; Open Backlog's is a lasting product ability at the top |
| epic | Epic | epic | the same idea in all three |
| feature | no standard level: often an epic, a custom level or a label | feature | |
| story | Story | story | |
| bug | Bug | story | |
| spike | a Story or Task marked as a spike | enabler story | |
| task | Task | story or enabler story | Jira's sub-tasks have no equivalent: split the story instead |
| status (the folder) | workflow status, board column | — | worked out, never stored |
| sprint | Sprint | iteration | a file in `sprints/` |
| estimate | Story points or time | story points | unit set once in `config.yml` |
| release | Fix version | release | free text |
| epic `0` | — | — | unplanned work by month |

Sources: Jira's hierarchy and its levels above epic [@jira-hierarchy]; SAFe's backlog structure
[@safe-backlog].

## Solution documents and the backlog

A backlog says *what* to build and in what order. It is a poor place to explain *how*: the
architecture, the decisions and the questions that shaped the plan span many stories and belong
together. The pilot behind this paper keeps those in a **solution document**, a Markdown file in
`docs/` beside the backlog, with sections for scope, open questions, decisions, the technical
design and a master plan that lists every story in build order.

The two have different jobs:

- **The solution document describes the backlog and holds the design.** It is where a feature is
  argued for before its stories exist, where a decision records why, and where the overall shape
  of the work can be read in one place.
- **The story files are the source of truth for the work.** Each story is written once, in its
  own file, and links back to the solution document. Status, progress and history come from the
  story files and git, never from the document.

Early in a project, stories are often drafted in the solution document, where they are easier to
reorder. Once they become files, the document keeps the design and a master plan of links, and
the story text lives only in the story files, so nothing is written twice. Solution documents
are a practice, not a rule: the specification does not require them, and a backlog without one
is just as valid.
