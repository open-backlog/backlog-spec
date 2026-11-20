# Workflows

Open Backlog adds no workflow of its own. A team keeps the branches, reviews and habits it already
has, and the backlog takes part in them as files. This section shows the everyday cases as short
sequences of git steps [@progit]. None of it is a rule: the specification says what a valid
backlog is, and these are ways of working with one.

## Planning in a pull request

New work is proposed the way code is: on a branch, reviewed, merged.

1. `git switch -c plan/export` starts a branch for the plan.
2. Add the files: a feature, `backlog/features/2.4-export.md` with `parent: 2`, and its stories
   in `backlog/stories/todo/`, `2.4.1-export-csv.md` and `2.4.2-export-pdf.md`.
3. `git add backlog && git commit -m "Plan 2.4 export"`, then push and open a pull request.
4. Reviewers comment on the stories line by line, as on code: a vague "So that" is caught
   before anyone builds the wrong thing. Changes are new commits on the branch.
5. Merge. The plan is now in the backlog, with the review on record.

Re-planning works the same way: a pull request that moves a story to another feature changes its
`parent`; one that drops a story deletes its file; one that reorders `todo/` changes `rank`
values. Each is a diff anyone can read.

## Starting and finishing work

1. `git mv backlog/stories/todo/2.4.1-export-csv.md backlog/stories/doing/`
2. `git commit -m "2.4.1 doing"`, and push, so the team sees it is taken.
3. Do the work on a branch as usual.
4. In the same pull request as the code, write the story's `## As built` and move it:
   `git mv backlog/stories/doing/2.4.1-export-csv.md backlog/stories/done/`.
5. Merge. The story is done when, and only when, its code is merged.

At T1, where the backlog is in the code's repository, step 4 makes the story and its code one
change. At T2 and above, the story moves in its own commit once the code is merged.

## Sprints

A sprint is a file, and an item joins it by naming it.

1. Create `backlog/sprints/2026-S24.md`:

   ```yaml
   ---
   start: 2026-11-16
   end: 2026-11-27
   goal: Checkout on the web
   ---
   ```

2. At sprint planning, add `sprint: 2026-S24` to each story taken on, in one commit or one pull
   request: `git commit -am "Plan sprint 2026-S24"`.
3. During the sprint, the sprint's board is every item whose `sprint` is `2026-S24`, grouped by
   folder; nothing else is stored.
4. At the end, what is in `done/` is finished. Unfinished stories get the next sprint's name, or
   none.

The sprint file never lists its stories, so two people planning on two branches never conflict
over a list. The history of a sprint is `git log -- backlog/sprints/2026-S24.md` plus the commits
that set `sprint`.

## Assigning work

`assignee: ana` on an item says who has it. Taking a story is setting the field and moving the
file in one commit; handing it over is changing the field. `people.yml` maps `ana` to a name and
optionally to a username on the git host:

```yaml
ana:
  name: Ana Silva
  forge: ana-silva
```

Handles keep email addresses out of the files. The commit that changed `assignee` records who
made the change, so "who gave this to Ana, and when" is in `git log`.

## Stories in commit messages

Start a commit message with the ID of the story it serves: `2.4.1 Write rows as CSV`. Then every
commit for a story can be found with `git log --grep '^2\.4\.1 '`, in any repository the work
touched, and a story's `## As built` can name the commits that delivered it. In a portfolio,
use the full name, `notes/1.1.1`. Nothing checks this; it is a habit that pays off when someone
asks what a story actually changed.

## Merge conflicts

Because each item is its own file and nothing is stored twice, most parallel work merges with no
conflict at all. Three cases are worth knowing. The outputs below are from git 2.54.

**An edit on one branch, a move on another.** Jo comments on story 1.1.1 on one branch while Ana
moves it to `doing/` on another. Git's rename detection carries the edit to the moved file, and
the merge succeeds with the comment in `doing/1.1.1-email-link.md`.

**Two moves to different folders.** One branch moves a story to `doing/` and another to `done/`:

```
CONFLICT (rename/rename): backlog/stories/todo/1.1.1-email-link.md renamed to
backlog/stories/done/1.1.1-email-link.md in HEAD and to
backlog/stories/doing/1.1.1-email-link.md in a.
```

This is a real disagreement about the story's status, and git asks a person to settle it: keep
one file with `git rm` on the other, then commit. Ordinary text conflicts inside a file are
resolved the usual way.

**Two new stories with the same ID.** Two branches each file the next story under feature 1.1,
`1.1.2-sign-out.md` and `1.1.2-remember-me.md`. Different filenames merge without complaint, but
the backlog now breaks the rule that IDs are unique. The specification already says how to fix
it: the item added later in git history takes the next free number and its file is renamed, here
to `1.1.3-remember-me.md` with `id: 1.1.3`. A validator, run in CI or as a pre-commit hook,
catches the duplicate at the merge, and a tool can offer to renumber.

## Reviewing the backlog

Since the backlog is files, the questions a team asks of a tracker become git commands:

- What started this week? `git log --since=1.week --diff-filter=A --name-only -- backlog/stories/doing/`
  (limited to one folder, a move into it shows as an added file).
- Who finished 2.4.1, and when? `git log -1 --follow --format='%an %ad' -- backlog/stories/done/2.4.1-export-csv.md`
- What was planned at the last release? `git ls-tree -r --name-only v1.2.0 -- backlog/stories/`

Section 11 builds reports on the same idea.
