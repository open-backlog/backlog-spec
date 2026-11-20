# Layout and lifecycle

The rules are in section 4 of the specification [@openbacklog-spec].

## The folders

![The folders of a backlog. The folder a story file is in is its status.](figures/folder-tree.svg)

A backlog is one folder, usually `backlog/` at the top of a repository. This is the backlog this
paper was planned in, partway through the work:

```
backlog/
├── config.yml
├── epics/          1-foundation.md  2-build.md  3-launch.md
├── features/       1.1-brand.md  1.2-site.md  1.3-spec.md  1.4-polish.md
│                   2.1-paper.md  2.2-core.md  2.3-cli.md  2.4-board.md
│                   3.1-dogfood.md  3.2-live.md
└── stories/
    ├── todo/       2.1.5-scaling.md  2.2.1-repo-scaffold.md  …
    ├── doing/      2.1.4-format-lifecycle.md
    └── done/
        └── 2026-11/  1.1.1-repo-logo.md  …  1.3.1-repo-scaffold.md  …
```

Capabilities, epics and features each have a folder and never move. Story-level items move
between `todo/`, `doing/` and `done/`. This backlog sets `done-folders: month` in its config, so
finished stories are filed by month; without it they go straight into `done/`.

## A story's life

![Story 2.3.4 moving from todo to doing to done. Each move is a git mv of the same file.](figures/story-lifecycle.svg)

Starting, finishing and reopening a story are each a move of its file, and the commit is the
record:

```sh
git mv backlog/stories/todo/2.1.4-format-lifecycle.md backlog/stories/doing/
git commit -m "2.1.4 doing"

git mv backlog/stories/doing/2.1.4-format-lifecycle.md backlog/stories/done/2026-11/
git commit -m "2.1.4 done"
```

Git follows a moved file, so its history stays whole [@progit]. Story 1.3.1's:

```
$ git log --follow --name-status --format='%h %ad %an | %s' --date=short \
    -- backlog/stories/done/2026-11/1.3.1-repo-scaffold.md

9c2671e 2026-11-19 Brandon Castagna | 1.3.1 — [backlog-spec] Repo scaffold [ID-1436]
R100  backlog/stories/todo/1.3.1-repo-scaffold.md  backlog/stories/done/2026-11/1.3.1-repo-scaffold.md
bf9ccdd 2026-11-19 Brandon Castagna | 1.3.1 — [backlog-spec] Repo scaffold [ID-1436]
M     backlog/stories/todo/1.3.1-repo-scaffold.md
54458ca 2026-11-16 Brandon Castagna | adding details to beginning/ending of a story description
M     backlog/stories/todo/1.3.1-repo-scaffold.md
```

Read from the bottom: refined while waiting, edited during the work, moved to `done/`. Here the
moves into and out of `doing/` were committed together, so the history skips `doing/`. A team that
wants the start recorded commits that move on its own.

**Order.** Items are listed by ID, each part compared as a number, so `2.9` comes before `2.10`.
Within a folder, an optional `rank` reorders items without renumbering them.

**Pruning.** `done/` may have subfolders of any names. Old items may be removed with `git rm`;
git keeps them, and their IDs are never reused. Remove children before their parent.

## The trade-offs

| Choice | What it gains | What it gives up |
|---|---|---|
| Status is a folder | visible in a listing, a diff and a pull request; can't disagree with itself; no tool needed | only three statuses, with no "in review" or "blocked"; a file's path changes when it moves, so link by ID |
| IDs never change | references keep working; the ID shows where work was planned | a moved story's ID no longer matches its parent; parallel branches can take the same ID (section 9) |
| Nothing stored that can be worked out | nothing to sync or go stale; no conflicts over lists | a question about many items means reading every file |
| Old work can be pruned | a small working tree | pruned items are only in git history |
