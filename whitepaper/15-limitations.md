# Limitations

Open Backlog is small on purpose, and being small costs something. These are its limits, stated
plainly. Some are permanent choices; others may change after 0.1 (section 17).

**It is for people who use git.** Everyone who changes the backlog needs a clone, an editor and
enough git to commit and push. A product manager or a client who has never used git cannot take
part without a tool, a mirror in a tracker, or a colleague. The board (section 12) narrows the
gap, but it still runs on a machine with a clone.

**Three statuses only.** A story is in `todo/`, `doing/` or `done/`. There is no "in review",
"blocked" or "waiting for release". A team can record those in a field of its own, but tools will
not treat them as status. Custom status folders are an idea for later, not a promise.

**No notifications, no real time.** Nobody is told when a story is assigned to them or when a
plan changes, except through the git host's own notifications on pull requests. Two people
editing the same story see each other's changes only after a pull and push, and may get a merge
conflict.

**Permissions are all or nothing.** Access is the repository's (section 14): there is no read-only
plan in a writable repository and no private item in a shared one.

**Questions across many files need a program.** Nothing that can be worked out is stored, so
"show me every open bug for the API" means reading every item. That is fast for a tool and slow
for a person with an editor. At very large sizes (tens of thousands of items) even a tool will
want a cache, which the specification allows as long as it is never committed.

**Reports are only as good as the commits.** Cycle time and burndown come from when moves were
committed, not when work happened (section 11). A team that commits late gets wrong numbers, and
nothing in the format can tell.

**Links across backlogs are text.** `notes/1.1.1` is not checked unless the other backlog is
cloned beside this one, and a `parent` can never cross backlogs. A portfolio sees its backlogs'
items but cannot own them.

**IDs show where work was filed, not where it is.** A story moved to another feature keeps its
old ID. Two people on separate branches can take the same ID, and one must be renumbered after
the merge.

**Attachments are not part of the format.** Screenshots, designs and logs can live in the
repository and be linked from a story, but they make the repository larger forever, and the
specification says nothing about them.

**It is new.** Version 0.1 is a draft, may change in breaking ways before 1.0, has one
implementation that is not yet built, and has been used on one real backlog: the one this paper
was planned in. Its governance is a single maintainer (section 16). These are the limits of any
young convention, and the next sections say how they are meant to end.
