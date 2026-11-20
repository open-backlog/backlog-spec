# Workflows

Open Backlog adds no workflow. A team keeps its branches, reviews and habits, and the backlog
takes part as files [@progit]. None of this is a rule.

## Planning in a pull request

1. `git switch -c plan/export`
2. Add a feature, `backlog/features/2.4-export.md` with `parent: 2`, and its stories in
   `backlog/stories/todo/`: `2.4.1-export-csv.md`, `2.4.2-export-pdf.md`.
3. Commit, push and open a pull request.
4. Reviewers comment on the stories as on code, so a vague "So that" is caught before anyone
   builds the wrong thing.
5. Merge.

Re-planning is the same: change a `parent`, delete a file, or change `rank`, in a pull request.

## Starting and finishing

1. `git mv backlog/stories/todo/2.4.1-export-csv.md backlog/stories/doing/`, commit and push, so
   the team sees it is taken.
2. Do the work on a branch.
3. In the code's pull request, write `## As built` and move the story to `done/`.
4. Merge. The story is done when its code is.

At T2 and above, the story moves in its own commit after the code is merged.

## Sprints and assignees

A sprint is a file, `backlog/sprints/2026-S24.md`:

```yaml
---
start: 2026-11-16
end: 2026-11-27
goal: Checkout on the web
---
```

Stories join it with `sprint: 2026-S24`, and the sprint never lists them. Its board is every item
with that `sprint`, by folder. Unfinished stories get the next sprint's name.

`assignee: ana` says who has a story. `people.yml` maps the handle to a name and optionally a
username on the git host:

```yaml
ana:
  name: Ana Silva
  forge: ana-silva
```

## Commit messages

Start a commit message with the story's ID: `2.4.1 Write rows as CSV`. Then
`git log --grep '^2\.4\.1 '` finds every commit for it, in any repository. Nothing checks this;
it is a habit.

## Merge conflicts

Each item is its own file, so most parallel work merges cleanly. Three cases, tested with git
2.54:

- **An edit on one branch, a move on another:** git carries the edit to the moved file and the
  merge succeeds.
- **Two moves to different folders:** git stops with a real disagreement about status:

  ```
  CONFLICT (rename/rename): backlog/stories/todo/1.1.1-email-link.md renamed to
  backlog/stories/done/1.1.1-email-link.md in HEAD and to
  backlog/stories/doing/1.1.1-email-link.md in a.
  ```

  Keep one file, `git rm` the other, and commit.
- **Two new stories with the same ID**, such as `1.1.2-sign-out.md` and `1.1.2-remember-me.md`:
  they merge, but break [id-unique]. The specification's fix: the one added later in git history
  takes the next free number and is renamed. A validator in CI or a pre-commit hook catches it.

## Asking git

```sh
# What started this week? (Limited to one folder, a move into it shows as an added file.)
git log --since=1.week --diff-filter=A --name-only -- backlog/stories/doing/

# Who finished 2.4.1, and when?
git log -1 --follow --format='%an %ad' -- backlog/stories/done/2.4.1-export-csv.md

# What was planned at the last release?
git ls-tree -r --name-only v1.2.0 -- backlog/stories/
```
