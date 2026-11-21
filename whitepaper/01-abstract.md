# Abstract

Open Backlog keeps a backlog as Markdown files in the git repository of the work it plans.
Each epic, feature and story is a file. A story's status is the folder it is in, so starting or
finishing work is a `git mv`, and git history records who did what, and when. There is no server,
account or API: people and AI agents read and change the backlog with a text editor and git. The
same files scale from one repository to a portfolio by adding configuration. This paper explains
why, sets out the convention, and describes the optional tools designed for it.

# Executive summary

**The problem.** Most teams keep their backlog in a hosted tracker, apart from the code. The two
drift apart, changes to the plan are never reviewed, every reader needs an account, and AI agents
working in the repository cannot see the plan at all.

**The idea.** Keep the backlog in the repository:

- **Each item is a Markdown file** [@commonmark] with a short YAML header [@yaml122].
- **Status is the folder:** `todo/`, `doing/` or `done/`. The commit that moves a file records
  who and when [@progit].
- **IDs show the plan:** `2.3.4` is the fourth story of feature `2.3` of epic `2`.
- **Nothing is stored that can be worked out:** progress, children and status come from the files.
- **It grows without migration:** from one repository to a portfolio, by configuration.

**What it changes.** Planning gets the same branches, review and history as code. The backlog
works offline, in any editor, and outlives any tool. An AI agent works a story with the same file
operations it uses for code. Tools help, but every rule can be followed by hand.

The rules are in the specification, version 0.1, at https://openbacklog.org/spec. Where this
paper and the specification differ, the specification wins.
