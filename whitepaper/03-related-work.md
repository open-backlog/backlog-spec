# Background and related work

Open Backlog borrows almost all of its ideas. This section names where they come from: the
trackers most teams use today, the tools that already keep work in git, and the conventions that
moved other kinds of project knowledge into the repository. It ends with what Open Backlog adds.

## Hosted trackers

**Jira**, from Atlassian, is an agile tracker whose vocabulary (epic, story, bug, sprint,
estimate) this paper shares. Its standard hierarchy runs from epic to story to sub-task; levels
above the epic, such as an initiative, are configured in its advanced planning features, which
need a Premium or Enterprise plan [@jira-hierarchy]. Programs reach Jira Cloud through a REST API,
authenticating with an Atlassian account's email address and an API token [@jira-basic-auth;
@atlassian-apitokens].

**Rally**, from Broadcom, plans at scale. Above user stories it has *portfolio items*, such as
features, initiatives and themes, whose types and levels are defined per workspace
[@rally-portfolio].

**Asana** organises work as projects, sections and tasks, and teams keep backlogs in it as well as
other work. Scripts reach it through an API with a personal access token that acts as the user who
made it [@asana-pat]. The backlog this paper was written from is also mirrored in Asana, so
people outside the repository can follow it.

**Linear** is an issue tracker for software teams. Its GitHub integration links pull requests to
issues and moves an issue's status as its pull request is opened and merged [@linear-github],
which narrows the drift described in section 2 from the tracker's side.

**GitHub Issues** sits on the same host as the code: each issue belongs to a repository.
Sub-issues, generally available since April 2025, let an issue break down into child issues across
several levels [@github-subissues; @github-issues-2025], and GitHub Projects can show that
hierarchy as a view [@github-hierarchy].

These are mature tools that do far more than Open Backlog: boards, reports, notifications,
permissions, integrations with everything. Open Backlog does not try to replace what they do
well. Its point is narrower: the backlog's *source of truth* can be files in the repository, and a
tracker, where a team wants one, can show those files rather than own them.

## Work kept in git

**git-bug** is a distributed, offline-first bug tracker integrated in git. It keeps issues inside
git itself, adding no files to the project, and shares them through the same remotes as the code
with `git bug push` and `git bug pull`. It has a terminal and a web interface, and bridges that
import from and export to GitHub, GitLab, Jira and Launchpad [@gitbug]. It shows that work items
can travel with a repository; it chooses storage that its tool reads, rather than files a person
edits.

**Backlog.md** is the closest relative of Open Backlog, and came first. It "turns any folder into
a self-contained project board powered by plain Markdown files" [@backlogmd]. Each task is a
Markdown file in a `backlog/` folder of the repository, with acceptance criteria, a Definition of
Done, milestones and dependencies. It has a command line, a terminal Kanban board and a local web
board, needs no server or account, and is MIT-licensed. It is designed around AI coding agents: it
sets up instructions or an MCP connector for coding agents, and frames agent work as three
reviews, of the tasks, the plan and the code, with one task per pull request. Its own development
is tracked in its own `backlog/` folder [@backlogmd].

Backlog.md and Open Backlog agree on the essentials: the backlog belongs in the repository, as
Markdown, readable without a service, and usable by people and agents alike. Where they differ is
in what they are. Backlog.md is a tool, and its files are shaped by that tool: its README asks
users to prefer its commands over hand-editing task files, so field types and metadata stay
consistent [@backlogmd]. Open Backlog is a specification with no required tool, written so that
editing by hand is the normal case. The two are not in competition: a tool like Backlog.md could
read and write an Open Backlog, and the specification is written to make that possible.

## Conventions that moved into the repository

Open Backlog follows a line of conventions that each took one kind of project knowledge out of a
separate system and into plain text beside the code.

- **Architecture decision records** keep each significant decision as a short numbered text file
  in the repository, with its context and consequences, so the reason for a design is reviewed
  and versioned with the design [@nygard-adr]. A story's `## As built` and comments do the same
  for the work.
- **Conventional Commits** gives commit messages a small structure that both people and tools can
  read, so that changelogs and version numbers can be worked out from history instead of being
  kept by hand [@conventionalcommits]. Open Backlog works out status and progress from files and
  history in the same spirit.
- **Docs as code** treats documentation like code: written in plain-text formats, kept in version
  control, reviewed in pull requests and published by the same automation [@writethedocs]. Open
  Backlog applies the same treatment to the plan.

## Scaled agile

**SAFe**, the Scaled Agile Framework, gives the hierarchy that large organisations plan with:
epics, broken into features (with an optional *capability* level between them for very large
solutions), broken into stories, each held in its own backlog [@safe-backlog]. Open Backlog's
four levels (capability, epic, feature, story) come from the same practice, though it uses the
word *capability* differently: for a lasting ability of the product, at the top. Its tiers (one
repository, a repository of its own, an application across repositories, a portfolio) are how
the same files reach the scale SAFe describes.

## How Open Backlog differs

Taken one by one, Open Backlog's ideas all exist somewhere above. What it adds is the combination,
written down as a short specification:

- **A specification, not a tool.** Version 0.1 is twenty named rules, a JSON Schema
  [@jsonschema07] and a set of examples that break each rule. A backlog is valid by the rules,
  not by what one program accepts, so any tool, or none, can work with it.
- **Status is a folder.** A story is in `todo/`, `doing/` or `done/`, so a status change is a
  `git mv` and the commit that makes it records who and when. Nothing about status is stored in
  the file, so there is nothing for a tool to keep consistent.
- **The full hierarchy, by default.** Capability, epic, feature and story are part of the format,
  linked upward by `parent`, with IDs that show where each item was filed. Progress at every
  level is worked out from the stories.
- **It scales without migration.** The same item files work in four tiers, from a backlog inside
  one repository (T1) to a portfolio of backlogs (T4), by adding configuration rather than
  changing any file.
- **Stories are paired with design.** In the pilot this paper comes from, each story links to a
  solution document that holds the technical design, and the story file stays the record of what
  was asked and what was built. The plan and the reasoning behind it are reviewed together.

Open Backlog is also smaller than most of what is described here, on purpose. It has no board,
no notifications and no permissions of its own: those come from git, the git host and whatever
tools a team adds. Section 13 describes how it connects to the trackers above, and section 15
what it leaves out.
