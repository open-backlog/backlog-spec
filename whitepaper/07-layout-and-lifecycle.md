# Layout and lifecycle

Where a file sits is part of what it says. This section shows the folders, how a story moves
through them, and the trade-offs behind each choice, including what each one gives up. The rules
are in section 4 of the specification [@openbacklog-spec].

## The folders

![The folders of a backlog. The folder a story file is in is its status.](figures/folder-tree.svg)

A backlog is one folder, usually `backlog/` at the top of a repository. Here is the backlog this
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

Capabilities, epics and features each have a folder and never leave it. Story-level items live
in `stories/`, in the folder for their status. The backlog's `config.yml` has
`done-folders: month`, so finished stories are filed by month under `done/`; that is optional,
and a backlog without it keeps every finished story directly in `done/`.

## A story's life

![Story 2.3.4 moving from todo to doing to done. Each move is a git mv of the same file.](figures/story-lifecycle.svg)

A new story is a file in `stories/todo/`. Starting it, finishing it and reopening it are all the
same act, moving the file, and the commit is the record:

```sh
git mv backlog/stories/todo/2.1.4-format-lifecycle.md backlog/stories/doing/
git commit -m "2.1.4 doing"

git mv backlog/stories/doing/2.1.4-format-lifecycle.md backlog/stories/done/2026-11/
git commit -m "2.1.4 done"
```

Git follows a moved file, so the story's history stays whole [@progit]. Here is story 1.3.1's,
from this project's backlog:

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

Read from the bottom: the story was refined while it waited, edited as the work was done, and
moved to `done/` on the day it finished. Git records the commits people make, not every move in
between: here the moves into and out of `doing/` were committed together, so the history goes
straight from `todo/` to `done/`. A team that wants the start recorded commits the first move on
its own.

A story is reopened by moving it back. Its parent's progress follows, because progress is never
stored: a feature is done when all its stories are in `done/`, and in progress when any has
started.

## Order and IDs

Items are listed by ID, each part compared as a number, so `2.9` comes before `2.10`. Within a
folder, items with a `rank` come first, lowest first, which is how a team reorders `todo/`
without renumbering anything. A new item takes the next number under the item it is filed under,
counting items since deleted, and the number is never used again.

## Pruning

Finished stories pile up. `done/` may have subfolders of any names and depth, such as months, and
moving between them changes nothing. Old items may also be removed with `git rm`: the files leave
the working tree, git history keeps them, and their IDs stay used. Children go before their
parent, or the backlog breaks the rule that a parent must exist.

## The trade-offs

Every choice above gave something up. These are the main ones.

**Status is a folder, not a field.** A move is visible in a directory listing, in a diff and in
a pull request; it cannot disagree with itself, because there is no second place it is written;
and changing it needs no tool. *What it gives up:* status has only three values, `todo`, `doing`
and `done`. There is no "in review" or "blocked" folder. A team that needs those can say so in a
comment or a field of its own, but tools will not treat them as status. Moving a file also
changes its path, so a link to a story's file breaks when it moves; links should use the ID.

**IDs never change.** A reference to `2.3.4` in a commit, a chat or another backlog keeps
pointing at the same work forever, and the ID records where the work was first planned. *What it
gives up:* after a story moves to another feature, its ID no longer matches its parent, so IDs
show where work was filed, not always where it is now. Two people on separate branches can also
take the same next number; after the merge, the later one is renumbered, which the
specification allows only for that case.

**Nothing that can be worked out is stored.** Children, status and progress come from the files,
so there is nothing to keep in sync and nothing to go stale, and two branches never conflict over
a list. *What it gives up:* answering "what are this feature's stories?" or "how far along is
this epic?" means reading every item file, not one. For a backlog of thousands of files that is
still fast for a program, but a person with only a text editor has to search, not read a list.

**Old work can be pruned.** Removing finished items keeps the working tree small, and git keeps
the record. *What it gives up:* a pruned item is no longer in the files, so a tool or a person
that wants it must go to git history, and progress counts for its parent change unless the tool
reads history too. Most backlogs never need to prune; filing `done/` by month keeps the folders
manageable first.
