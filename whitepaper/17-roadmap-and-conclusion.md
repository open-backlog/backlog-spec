# Roadmap and conclusion

## What comes next

**Version 0.1** is being built in the open, story by story, in the backlog this paper describes.
Done: the specification draft, its example backlogs and JSON Schema, and openbacklog.org with the
specification, the examples, a file checker and a story maker. In progress: this paper and its
PDF. Planned for 0.1:

- the core library and the `ob` command line (section 12), published to npm;
- the local board, started with `ob serve`;
- this project's own backlog checked by those tools, as the first real test;
- the repositories made public, and the specification released as `spec-v0.1.0`.

**After 0.1** these are candidates, each to be planned as real stories when its time comes. None
is a commitment:

- **Planning:** `ob sprint` to create and close sprints and carry work over; sprint and assignee
  filters on the board.
- **Scale:** reading across repositories and backlogs (T3 and T4) in the tools; a tree of a whole
  portfolio.
- **Reports:** `ob report` for progress, cycle time, burndown and velocity from git history
  (section 11).
- **Ideas:** a hosted, multi-user board; CSV import and export for Jira, Asana and Rally
  (section 13); custom status folders.

**Toward 1.0:** three to six months of use on real backlogs without a breaking change
(section 16). The fastest way there is more backlogs than one.

## Conclusion

Agile teams keep their plan apart from their work out of habit, and pay for it in drift, in
logins and API keys, in planning that is never reviewed, and now in AI agents that cannot see
what they are building toward. Open Backlog's answer is the smallest one that could work: put
the backlog in the repository as plain Markdown files, make a story's status the folder it is in,
and let git be the history, the review and the permissions.

Nothing about this is new on its own. Markdown, YAML, folders and git are older than most
backlogs, and the ideas behind it come from trackers, from tools such as Backlog.md and git-bug,
and from conventions such as architecture decision records and docs as code (section 3). What
Open Backlog adds is a short, versioned specification, twenty rules a person can follow by hand,
that lets those ideas work together and lets any tool, or none, work on the result. It scales
from a folder in one repository to a portfolio without converting a file, and it treats people
and AI agents the same way: both read files, move them and commit.

## How to take part

- **Try it.** Start a backlog in a repository you already have, by hand: section 6 shows the
  five steps, and https://openbacklog.org/new writes a valid story file for you. Check a file
  with https://openbacklog.org/check.
- **Read the specification** at https://openbacklog.org/spec, and the example backlogs at
  https://openbacklog.org/examples.
- **Tell us what breaks.** Once the repositories are public, open an issue: a rule that is
  unclear, a case it gets wrong, an idea (section 16). Reports from real backlogs are what earn
  1.0.
- **Build a tool.** The JSON Schema and the example backlogs are a test suite for any
  implementation (section 12), in any language. An editor extension, a bridge to a tracker, or
  an existing tool that reads and writes the format all count.
