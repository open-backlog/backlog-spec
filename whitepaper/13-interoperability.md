# Interoperability

**Everything in this section is future work.** Version 0.1 has no import, export, connector or
sync with any other tool, by design: the first release proves the format on its own. This
section describes what interoperability could look like, so that tool builders can see where it
fits and so that teams can judge what moving to or from Open Backlog would take.

## Why it matters

Few teams start from nothing. A team with years of history in Jira, Asana or Rally will try Open
Backlog only if it can bring that history, and some will want both for a while: the files for
engineers and agents, a tracker for people who already live in it. Others will leave, and the
format should make that easy too. A backlog that can only be left by hand is a lock-in of its
own.

## Import

Each tracker can export its items, and each item maps onto an Open Backlog file:

| Open Backlog | Jira | Asana | Rally |
|---|---|---|---|
| epic | Epic | a section, or a project | a portfolio item |
| feature | an epic's child level, or a custom level | a section, or a parent task | a portfolio item (Feature) |
| story-level item | Story, Bug, Task | task | user story, defect, task |
| `parent` | the parent link | section or parent task | the parent portfolio item |
| status folder | the workflow status, grouped into three | completed or not, or the section | the schedule state, grouped into three |
| `assignee` | Assignee, as a handle | Assignee, as a handle | Owner, as a handle |
| `estimate` | Story points | a custom field | Plan Estimate |
| comments | comments, as dated comment lines | comments | discussion |

The names are each tool's usual ones; fields vary with plan and configuration, so an importer
reads the tool's own export to be sure. An importer creates one file per item, numbered in the
tracker's order under its new parent, and keeps the tracker's own key as an extra field, such as
`jira: SHOP-123`, which the specification allows and tools keep. Statuses that have no folder of
their own (review, blocked) are grouped into `todo`, `doing` or `done`, and the original can be
kept as a field. Email addresses are replaced by handles, because the files never hold them. The
tracker's history of changes stays in the tracker; the backlog's history starts with the import
commit.

The mapping is partly a judgement call: Jira's levels above the epic are configured per site
[@jira-hierarchy], Rally's portfolio item types are defined per workspace [@rally-portfolio], and
Asana has no fixed hierarchy, so an importer has to ask which level is which.

## Export

Export is the reverse, and simpler, since the files are already plain: one tracker item per file,
the hierarchy from `parent`, the status from the folder, and the ID kept in a field or the title
so links still resolve. A CSV export is enough for most trackers' own importers.

## Living side by side

The hardest case is keeping a tracker and the files in step. If both can change, every item has
two copies, which is the drift section 2 describes. A bridge that is honest about this makes one
side the source of truth: the files, with the tracker as a read-only mirror updated from git, or,
during a migration, the tracker, with the files regenerated from it. The backlog this paper was
planned in works this way today, by hand: its stories are mirrored in an Asana project so people
outside the repository can follow them, and the story files are the source of truth.

A two-way sync is possible, but it is a product, not a format, and it would have to settle
conflicts that git settles today for free. It is not planned for Open Backlog itself.

## What is planned

After 0.1, the roadmap (section 17) lists CSV import and export for Jira, Asana and Rally as an
idea, not a commitment. Any of it can equally be built by someone else: the specification, the
JSON Schema and the example backlogs are everything an importer or exporter needs, and none of it
depends on the reference implementation.
