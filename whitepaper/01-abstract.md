# Abstract

Open Backlog is an open convention for keeping an agile backlog as plain Markdown files in the
git repository of the work it plans. Each epic, feature and story is a file with a few lines of
YAML at the top; a story's status is the folder it sits in, so starting or finishing work is a
`git mv`; and git history is the record of who changed what, and when. Nothing is generated,
stored twice or kept in sync, and there is no server, account or API: people and AI agents read
and change the backlog the same way, with a text editor and git. The same files scale from a
backlog inside one repository to a portfolio of backlogs, by adding configuration rather than
changing any file. This paper explains why a backlog belongs beside its code, sets out the
convention and the reasoning behind it, and describes the tools built for it, which are optional.

# Executive summary

**The problem.** Most agile teams keep their backlog in a hosted tool, apart from the code it
describes. The plan and the work drift apart: a story is marked done in one place while its code
is still in review in another, and the history of a decision is split between a tracker, a chat
and the commits. Every person and every program that needs the backlog must go through that
tool's account, permissions and API. That cost is growing, because AI coding agents now do real
work in repositories. They are good at reading and changing plain files, and poor at reaching
into a third-party service for the context they need.

**The idea.** Keep the backlog in the repository, as files a person can read without any tool:

- **Each item is a Markdown file** [@commonmark] with a small YAML header [@yaml122]: its ID,
  type, title and parent, and optional fields such as an estimate or an assignee.
- **Status is the folder.** A story lives in `todo/`, `doing/` or `done/`. Moving it is the
  status change, and the commit that moves it records who and when [@progit].
- **IDs are numbers that show the plan.** `2.3.4` is the fourth story of the third feature of the
  second epic, so reading IDs in order is reading the build order.
- **Nothing is derived and stored.** Progress, children and status are worked out from the files,
  so there is nothing to keep in sync and nothing to go stale.
- **It grows without migration.** The same files work in one repository, in a repository of their
  own, across an application's repositories, and across a portfolio of backlogs.

**What it changes.** Planning moves into the same review, branch and history as the code. A
backlog can be read on any machine, offline, in any editor, and long after any tool is gone. An
AI agent can pick up a story, read its context, do the work and move the file, using the
operations it already uses for code. Tools such as the `ob` command line and a local board make
this faster, but every rule can be followed by hand.

**What's in this paper.** The problem and related work (sections 2–3); the principles and the
model (4–5); the file format and the lifecycle (6–7); scaling from one repository to a portfolio,
with worked examples (8); everyday workflows and working with AI agents (9–10); reporting from git
history, the reference implementation and interoperability (11–13); and security, limitations,
governance and the roadmap (14–17). The normative rules are in the specification, version 0.1,
at https://openbacklog.org/spec; where this paper and the specification differ, the
specification wins.
