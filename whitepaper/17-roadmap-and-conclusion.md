# Roadmap and conclusion

## Roadmap

The first release is being built in the open, story by story, in the backlog this paper
describes.

- **Done:** the specification draft, its examples and JSON Schema; openbacklog.org with the
  specification, the examples, a file checker and a story maker; this paper and its PDF; the core
  and `ob` on npm (`open-backlog` 0.3, with `ob comment`, `ob serve` and `ob init --hook`); the
  board (`open-backlog-board` 0.3); and this project's backlog checked by those tools.
- **In progress:** the repositories made public and the specification released as
  `spec-v0.1.0`.

**Candidates after that**, none a commitment: `ob sprint` and sprint filters on the board;
reading across repositories and backlogs in the tools; `ob report` (section 11); a hosted board;
CSV import and export for Jira, Asana and Rally (section 13); custom status folders.

## Conclusion

Teams keep their plan apart from their work out of habit, and pay in drift, logins, unreviewed
planning, and AI agents that cannot see the plan. Open Backlog's answer is the smallest one that
could work: the backlog as Markdown files in the repository, status as a folder, and git as the
history, the review and the permissions. None of the pieces is new. What Open Backlog adds is a
short specification that lets them work together, by hand or with any tool, for people and AI
agents alike.

## How to take part

- **Try it** in a repository you already have: section 6 shows the five steps, and
  https://openbacklog.org/new writes a story file for you. Check a file at
  https://openbacklog.org/check.
- **Read** the specification at https://openbacklog.org/spec and the examples at
  https://openbacklog.org/examples.
- **Report what breaks** in an issue once the repositories are public. Reports from real
  backlogs are what earn 1.0.
- **Build a tool.** The schema and examples are a test suite for any implementation.
