# Working with AI agents

This section adds nothing to the specification. A team that never uses AI loses nothing.

An AI coding agent already reads files, edits them and runs `git mv` and `git commit`. That is all
an Open Backlog needs, so an agent works a story exactly as a person does: it reads the story and
its parents, moves it to `doing/`, does the work, writes `## As built`, and moves it to `done/`.
There is no agent API, format or permission. Its access to the backlog is its access to the
repository.

- **Context is a read away.** The story, its feature, the design and the code are all in the
  working tree. Nobody pastes the story into a prompt.
- **No tokens.** The agent needs no tracker account, so no token can leak.
- **Changes are reviewable.** When an agent re-plans or writes acceptance criteria, it is a diff
  in a pull request.
- **One history.** What an agent did and what a person did are in the same `git log`.

This project's backlog was worked this way: an AI agent did each story and moved its file, adding
comments as `@claude`, and a person reviewed, committed and released.

## A sample AGENTS.md

Many coding agents read a file named `AGENTS.md` for a repository's conventions [@agentsmd]. This
is an example, **not part of the specification**:

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

## Keeping people in charge

The safeguards are the ones used for code: the agent works on a branch, its changes arrive as a
pull request, a person merges them, and a validator in CI rejects a backlog that breaks the rules.
