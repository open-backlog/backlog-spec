# Background and related work

Open Backlog borrows most of its ideas. This is where they come from.

## Hosted trackers

- **Jira** shares this paper's vocabulary (epic, story, bug, sprint). Its standard levels are
  epic, story and sub-task; levels above the epic need a Premium or Enterprise plan
  [@jira-hierarchy]. Programs use its REST API with an account's email address and an API token
  [@jira-basic-auth; @atlassian-apitokens].
- **Rally** adds *portfolio items*, such as features, initiatives and themes, above user stories,
  defined per workspace [@rally-portfolio].
- **Asana** organizes work as projects, sections and tasks; scripts use a personal access token
  that acts as its owner [@asana-pat]. This project's own backlog is mirrored there.
- **Linear** links pull requests to issues through its GitHub integration and moves an issue's
  status as its pull request is opened and merged [@linear-github].
- **GitHub Issues** belong to a repository. Sub-issues, generally available since April 2025,
  nest issues several levels deep [@github-subissues; @github-issues-2025], and GitHub Projects
  can show that hierarchy [@github-hierarchy].

These tools do far more than Open Backlog: boards, reports, notifications, permissions,
integrations. Open Backlog's point is narrower: the backlog's source of truth can be files in the
repository, which a tracker can mirror rather than own.

## Work kept in git

**git-bug** is a distributed, offline-first bug tracker integrated in git. It stores issues inside
git, adding no files to the project, shares them with `git bug push` and `git bug pull`, and has
bridges to GitHub, GitLab, Jira and Launchpad [@gitbug]. Its storage is read by its tool, not
edited by hand.

**Backlog.md** is Open Backlog's closest relative, and came first. It "turns any folder into a
self-contained project board powered by plain Markdown files" [@backlogmd]: each task is a
Markdown file in `backlog/`, with acceptance criteria, milestones and dependencies, a command
line, a terminal board and a local web board. It is MIT-licensed, needs no server or account, and
is built around AI coding agents [@backlogmd].

The two agree on the essentials: the backlog belongs in the repository, as Markdown, for people
and agents alike. They differ in what they are. Backlog.md is a tool, and asks users to prefer its
commands over hand-editing so its metadata stays consistent [@backlogmd]. Open Backlog is a
specification, written so that hand-editing is the normal case. A tool like Backlog.md could read
and write an Open Backlog.

## Conventions that moved into the repository

- **Architecture decision records** keep each decision as a short numbered file in the repository
  [@nygard-adr]. A story's `## As built` does the same for the work.
- **Conventional Commits** gives commit messages a structure tools can read, so changelogs come
  from history [@conventionalcommits]. Open Backlog works out status and progress from history
  the same way.
- **Docs as code** keeps documentation in plain text, in version control, reviewed in pull
  requests [@writethedocs]. Open Backlog does this for the plan.

## Scaled agile

**SAFe** plans with epics, features and stories, with a *capability* level between epic and
feature in its full configuration [@safe-backlog]. Open Backlog uses the same levels, but its
capability is a lasting product ability at the top.

## What Open Backlog adds

The combination, written down as a short specification:

- **A specification, not a tool:** twenty rules, a JSON Schema [@jsonschema07] and example
  backlogs. Any tool, or none, can work with it.
- **Status is a folder**, changed by `git mv`.
- **The full hierarchy:** capability, epic, feature and story, linked upward by `parent`.
- **Scaling without migration:** four tiers, by configuration only.
