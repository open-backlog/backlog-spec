# Working with AI agents

This section is informative. It adds nothing to the specification, and a team that never uses an
AI agent can skip it and lose nothing: every part of Open Backlog works with a person, a text
editor and git.

## The same operations

An AI coding agent working in a repository already does three things well: it reads files, it
edits files, and it runs commands such as `git mv` and `git commit`. That is everything an Open
Backlog needs. An agent working a story does what a person does:

1. Read `backlog/stories/todo/` and pick the story it was given, or the next by rank.
2. Read the story, its feature and its epic by following `parent`, and any linked design
   document, to understand what is wanted and why.
3. `git mv` the story to `doing/` and commit.
4. Do the work, checking each "So that" line as it goes.
5. Write `## As built`, add a dated comment with its handle, `git mv` the story to `done/`, and
   commit with the code, or open a pull request for a person to review.

There is no agent API, no special format and no separate permission. The agent's access to the
backlog is exactly its access to the repository, and every change it makes is a commit with an
author, reviewed and reverted like any other.

## Why plain files suit agents

- **Context is a read away.** The story, its parent feature, the design and the code are all in
  the working tree. Nobody has to paste the story into a prompt, and the agent sees the current
  version, not a copy.
- **Nothing to authenticate.** The agent needs no tracker account and no API token, so no token
  can leak and no integration can break.
- **Changes are reviewable.** When an agent re-plans, splits a story or writes acceptance
  criteria, the change is a diff in a pull request. A person approves the plan the same way they
  approve the code.
- **History is shared.** What an agent did and what a person did are in the same `git log`, in
  the same form.

The backlog this paper was planned in was worked largely this way. An AI coding agent picked up
each story file, did the work, wrote its `## As built`, added a comment under the handle
`@claude` and moved the file; a person reviewed, committed and released. The story files in
sections 6 and 7 are examples of that work.

## Telling an agent about the backlog

Agents need to be told the conventions of a repository, and many coding agents now read a file
named `AGENTS.md` for that: a README written for agents [@agentsmd]. The snippet below is an
example of what a team might put in theirs. **It is not part of the specification**: the
wording, the steps and the habits it asks for are one team's choice, and any of them can be
changed.

```markdown
## Backlog

This repo plans its work in `backlog/`, an Open Backlog (https://openbacklog.org/spec).

- Each item is a Markdown file with YAML front matter. A story's status is its folder:
  `backlog/stories/todo/`, `doing/` or `done/`.
- To start a story: `git mv backlog/stories/todo/<file> backlog/stories/doing/` and commit
  with the message `<id> doing`.
- Read the story's parent feature and epic (follow `parent:`) before starting.
- Treat each "So that" line as an acceptance test. Do not finish a story until every one
  has been checked.
- When finished, write `## As built` (what was done and how it was checked), add a comment
  `- YYYY-MM-DD @agent: <summary>` under `## Comments`, and move the file to `done/` in the
  same pull request as the code.
- Never write `status`, `children` or `progress` fields, and never change an item's `id`.
- Start commit messages with the story's ID.
- New stories go in `todo/` with the next free ID under their feature. Propose them in a
  pull request; do not start them until a person has merged the plan.
```

A tool can also help an agent: the reference implementation's `ob` commands (section 12) give
it short, checked operations instead of raw file edits. They are a convenience for agents just as
for people, never a requirement.

## Keeping people in charge

An agent that can move a file can move it too early. The safeguards are the ones a team already
uses for code: the agent works on a branch, its changes arrive as a pull request, a person merges
them, and a validator in CI rejects a backlog that breaks the rules. Because the plan and the
work are reviewed together, a person sees both what the agent claimed and what it did before
either is accepted.
