# Reporting from git history

Open Backlog stores no status, progress or dates, yet the usual agile reports still work. Each
asks either *what is where now*, which the files answer, or *what moved when*, which git history
answers [@progit]. A tool may cache results, but never commits the cache.

| Report | Comes from | How |
|---|---|---|
| Progress | the files now | story-level items in `done/` out of all of them, under each feature, epic or capability; optionally weighted by `estimate` |
| Cycle and lead time | git history | `git log --follow --name-status` on one story: when it entered `doing/` and `done/`, and when it was created |
| Throughput and velocity | git history | `git log --diff-filter=A -- backlog/stories/done/`: arrivals in `done/` per period; velocity sums their `estimate` (read with `git show <commit>:<path>`) per sprint |
| Burndown and burnup | git history | for each day, `git ls-tree` at that day's last commit, counting what is in scope and not done (or done) |
| Who did what | git history | commit authors and dates; the history of `assignee` |

Because the whole backlog is in every commit, any of these works for any past day, with no
snapshot taken at the time.

## This project's numbers

Progress, from the files, partway through the work:

| Feature | Done / all |
|---|---|
| 1.1 Logo and art | 3 / 3 |
| 1.2 openbacklog.org | 11 / 11 |
| 1.3 Spec draft | 9 / 9 |
| 1.4 Simpler spec, app icon and site tools | 5 / 5 |
| 2.1 Whitepaper + PDF | 6 / 13 |
| 2.2 – 3.2 (five features) | 0 / 33 |
| **All** | **34 / 74 (46%)** |

Epic 1 is done because all its features are; no file says so. Throughput from git history: 6, 5,
12 and 4 stories reached `done/` on 2026-11-17 to 2026-11-20.

## Limits

Reports are only as good as the commits. A move is dated when it is committed, so a team that
commits late gets wrong cycle times. This project often committed the start and finish of a
story together, so its lead times are reliable and its cycle times are not.

The first release of `ob` shows progress (`ob tree`) and recent work (`ob list`).
An `ob report` command is a candidate for later (section 17). Every report above already works
with git alone.
