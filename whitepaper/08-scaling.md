# Scaling: T1 to T4

A backlog for one developer's side project and a backlog for a portfolio of products use the
same item files. What differs is where the backlog lives and what its `config.yml` lists. The
specification names four tiers (section 5 of [@openbacklog-spec]); this section works through
each one, using the example backlogs published with the specification in `examples/` and, for
T3, the backlog this project is planned in.

![The four tiers, from a backlog inside one repository to a portfolio of backlogs.](figures/tiers.svg)

| Tier | Where the backlog is | What is added | Item files changed |
|---|---|---|---|
| T1 | inside the code repository it plans | nothing: two lines of config | — |
| T2 | in a repository of its own | nothing: the folder moves | none |
| T3 | in one repository, for an application in several | `repos:` in config; `repo` on items where useful | none |
| T4 | a portfolio, gathering other backlogs | `backlogs:` in config | none, in any backlog |

## T1: inside the code

Most backlogs should start here, and many never need to leave. The example `t1-minimal` plans a
small notes app from inside the app's own repository. Its whole configuration is:

```yaml
spec: 0.1
name: notes
```

```
notes/                         the app's repository
├── src/ …
└── backlog/
    ├── config.yml
    ├── epics/1-launch.md
    ├── features/1.1-write-a-note.md
    └── stories/
        ├── todo/1.1.3-delete-note.md
        ├── doing/1.1.2-edit-note.md
        └── done/1.1.1-new-note.md
```

A story and the code that delivers it can land in the same commit, and the same pull request
that changes the code moves the story to `done/`. Branches carry the plan with them: a story
started on a branch is in `doing/` on that branch and nowhere else until it merges.

## T2: a repository of its own

A backlog moves to its own repository when the plan should not live with any one codebase: the
code is about to be split, people who plan should not need the code, or the plan is private
while the code is public. The move is a copy of the `backlog/` folder into a new repository,
keeping its history if wanted (`git subtree split` or `git filter-repo` does this):

```
notes-backlog/                 a repository holding only the plan
└── backlog/
    ├── config.yml             unchanged: spec: 0.1, name: notes
    └── …                      every item file unchanged
```

```yaml
spec: 0.1
name: notes
```

The configuration is the same two lines and every item file is the same; the published examples
have no separate T2 backlog for that reason, since `t1-minimal` in its own repository is one.
What is given up is the shared commit: the story and its code now change in two repositories, so
a story is moved by its own commit after the code is merged.

## T3: one application, several repositories

When an application spans repositories (an API, a web app, a mobile app), one backlog plans all
of them. The configuration lists the repositories, and an item may say which one its work
changes. The published example `t3-application` is a shop in three repositories:

```yaml
spec: 0.1
name: shop
estimate: points
done-folders: month
repos:
  shop-api: git@github.com:example/shop-api.git
  shop-web: git@github.com:example/shop-web.git
  shop-ios: git@github.com:example/shop-ios.git
```

A story then names its repository with `repo: shop-web`, or a list, `repo: [shop-api, shop-web]`,
for work that spans two. A tool can show the work by repository, and the specification checks
that every name used is one the configuration lists.

**Open Backlog itself is a T3 backlog.** Its plan lives in its own repository, `backlog-docs`,
and covers the repositories for the specification, the website, the art, the tools and the
servers they run on. This is its configuration as this paper was written:

```yaml
spec: 0.1
name: open-backlog
done-folders: month
repos:
  backlog-spec: git@github.com:brandon-castagna/backlog-spec.git
  backlog-cli: git@github.com:brandon-castagna/backlog-cli.git
  backlog-board: git@github.com:brandon-castagna/backlog-board.git
```

Of its 74 stories, 70 name one repository, such as `repo: backlog-spec` on the story that
created the specification's repository (section 6), and four name two or three:

```yaml
id: 1.4.3
type: story
title: Examples browser
parent: 1.4
repo: [backlog-spec, backlog-ui]
```

Moving from T2 to T3 changes the configuration only. `repo` is optional, so every item written
before the move stays valid without it, and it is added to new items, or to old ones when it
helps.

## T4: a portfolio

A portfolio gathers backlogs that are each complete on their own: several products, or several
teams' applications. It is a backlog too, with a little work of its own, and its configuration
lists the backlogs it gathers. The published example `t4-portfolio` has three repositories cloned
side by side:

```
t4-portfolio/
├── portfolio/backlog/    lists the other two under backlogs:
├── notes/backlog/        a T1 backlog
└── photos/backlog/       a T1 backlog
```

```yaml
spec: 0.1
name: portfolio
backlogs:
  notes: git@github.com:example/notes.git
  photos: git@github.com:example/photos.git
```

The two application backlogs keep their two-line configurations, `name: notes` and
`name: photos`. Neither knows it is in a portfolio: joining one changes no file of theirs, and
leaving one is deleting a line from the portfolio's configuration.

## Links across backlogs

Inside a backlog an item is named by its ID, `1.1.1`. From anywhere else it is named by the
backlog's name, a slash and the ID: `notes/1.1.1`. In the T4 example, the portfolio's story for a
shared sign-in service says how it will be checked:

```markdown
**So that:**
- Verify notes/1.1.1 and photos/1.1.2 can both sign in through it
```

and each application's story points back at it in its body: `Part of portfolio/1.1.1.` The names
are the `name` in each backlog's configuration, and the key it is listed under in the
portfolio's `backlogs:`.

These links are text. A `parent` always names an item in the same backlog, so no rule depends
on another backlog being present, and each backlog stays valid on its own. A tool that can see
the other backlog can follow the link; one that cannot still shows it as written.

## Finding other repositories: local clones

A tool finds a listed repository or backlog in one place only: a folder with the key's name
beside the repository holding this backlog. For the portfolio above, that is `../notes/` and
`../photos/`, each with its backlog in `backlog/`; for the shop, `../shop-api/` and so on. The
clone URL in the configuration is there to tell people where to clone from; a tool does not
fetch it.

This rule ([local-clones]) keeps every tool offline, fast and free of credentials: nothing is
read from the network, and no git submodules tie the repositories together. Each person clones
what they work on, and a tool works with what is there. When a listed repository is missing,
the tool warns and carries on. The published example `tools/local-clones` tests exactly that: a
portfolio that lists two backlogs with only one cloned, where a correct tool warns about the
missing one, shows the other, and succeeds.

Open Backlog's own backlog shows the same rule in daily use. It lists eleven repositories; at
the time of writing six are cloned beside it and five are not, because two are not created yet
and three belong to other projects and live elsewhere on disk. A tool reports those five as
missing and works with the rest.

## Moving up, in short

Each step up the tiers is a change to `config.yml` and, at T2, a move of the folder. No item
file is rewritten, no ID changes and no history is lost, so a team can start at T1 on its first
day and reach T4 years later with the backlog it started with.
