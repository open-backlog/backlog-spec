# Reporting from git history

Open Backlog stores no status, no progress and no dates of moves, so it is fair to ask whether the
usual agile reports are still possible. They are. Every one of them is a question about *what
is where now* or *what moved when*, and the files answer the first while git history answers the
second [@progit]. This section names, for each report, the data it comes from.

Two kinds of data are used:

- **The files now**: which folder each story-level item is in, its `parent`, and optional fields
  such as `estimate` and `sprint`.
- **Git history**: each commit that added, moved or deleted an item file, with its author and
  date. A move is a rename; git finds renames when it compares commits, and `git log --follow`
  traces one file across them.

Reports in this section are computed by reading those, the same way by hand, by script or by a
tool. A tool may cache results to be fast, but a cache is never committed and never the source
of truth.

## Progress

*From the files now.* A feature's progress is its story-level items in `done/` out of all its
story-level items; an epic's and a capability's are the same count over the items beneath them.
A team that estimates may weight each item by `estimate` instead of counting it as one.

For the backlog this paper was planned in, at the time this section was written:

| Feature | Done / all |
|---|---|
| 1.1 Logo and art | 3 / 3 |
| 1.2 openbacklog.org | 11 / 11 |
| 1.3 Spec draft | 9 / 9 |
| 1.4 Simpler spec, app icon and site tools | 5 / 5 |
| 2.1 Whitepaper + PDF | 6 / 13 |
| 2.2 – 3.2 (five features) | 0 / 33 |
| **All** | **34 / 74 (46%)** |

Epic 1 is done because all four of its features are. Nothing in any file says so.

## Cycle time

*From git history.* A story's cycle time is the time from the commit that moved it into `doing/`
to the commit that moved it into `done/`; its lead time runs from the commit that created it.
Both come from one command:

```sh
git log --follow --name-status --format='%ad' --date=iso -- backlog/stories/done/2.4.1-export-csv.md
```

which lists every commit that touched the story, newest first, with the folder it was in after
each. The accuracy is the team's commit habit: a move is dated when it is committed, so a team
that commits the move into `doing/` when work starts gets real cycle times. In this project's
pilot the start and finish were often committed together, so its history gives lead times
reliably and cycle times only for some stories (section 7).

## Throughput and velocity

*From git history.* Throughput is the number of story-level items that arrived in `done/` in a
period; velocity is the same with each weighted by `estimate`, usually per sprint. Arrivals are
the commits that added a file under `done/`:

```sh
git log --diff-filter=A --name-only --format='@%ad' --date=short -- backlog/stories/done/
```

In this project's pilot that gives 6, 5, 12 and 4 stories on the four days from 2026-11-17 to
2026-11-20: the days the moves were committed, which is close to, but not always, the day the
work finished. To get velocity, read each arriving file's `estimate` at that commit
(`git show <commit>:<path>`), and group by the item's `sprint` or by the sprint whose dates
contain the commit.

## Burndown and burnup

*From git history.* A burndown is the remaining work on each day of a sprint or release. For
each day, take the last commit before its end, list the files in the backlog at that commit
(`git ls-tree -r --name-only <commit> -- backlog/stories/`), keep the items in scope (by `sprint`,
`release` or `parent`), and count, or sum the `estimate` of, those not in `done/`. A burnup is
the same series counting what is done, with total scope drawn as a second line, so scope added
mid-sprint shows as a rise in scope rather than as lost progress.

Because the whole backlog is in every commit, this works for any past day and any scope, without
anyone having taken a snapshot at the time.

## Who did what

*From git history.* The author and date of each commit that moved, edited or created an item say
who did it and when; `assignee` says who has it now, and its history says who had it before.
`git log -- backlog/` is the complete audit trail of the plan, and it cannot be edited quietly
after the fact without rewriting history that every clone holds.

## What git cannot tell

Some things are not recorded anywhere unless someone commits them: when a story was really
started if the move was committed late, time spent waiting in review, and anything decided off
the record. The reports are as good as the commits behind them. Open Backlog's answer is a habit,
not a rule: commit each move when it happens.

As designed, version 0.1 of the reference implementation reports progress (`ob tree`) and lists
recent finished work (`ob list`); cycle time, throughput, velocity and burndown as commands
(`ob report`) are planned for after 0.1 (section 17). Every report above can be run today with
git alone.
