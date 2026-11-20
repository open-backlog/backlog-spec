# Scaling: T1 to T4

A side project and a portfolio of products use the same item files. Only where the backlog lives
and what `config.yml` lists change. The tiers are defined in section 5 of the specification
[@openbacklog-spec]; the examples here are the specification's own, in `examples/`, and this
project's backlog.

![The four tiers, from a backlog inside one repository to a portfolio of backlogs.](figures/tiers.svg)

| Tier | Where the backlog is | What is added | Item files changed |
|---|---|---|---|
| T1 | inside the code repository it plans | nothing: two lines of config | none |
| T2 | in a repository of its own | nothing: the folder moves | none |
| T3 | in one repository, for an application in several | `repos:` in config; `repo` on items | none |
| T4 | a portfolio, gathering other backlogs | `backlogs:` in config | none, in any backlog |

## T1: inside the code

Most backlogs should start here. The example `t1-minimal` plans a notes app from inside its own
repository, and its whole configuration is:

```yaml
spec: 0.1
name: notes
```

A story can move to `done/` in the same pull request as its code, and a story started on a branch
is in `doing/` only on that branch.

## T2: a repository of its own

A backlog moves out when the plan should not live with any one codebase: the code is being split,
planners should not need the code, or the plan is private and the code is public. Copy the
`backlog/` folder to a new repository (`git subtree split` or `git filter-repo` keeps its
history). The configuration and every item file stay the same, which is why the examples have no
separate T2 backlog. The cost: a story now moves in its own commit, after its code is merged.

## T3: one application, several repositories

The configuration lists the repositories, and an item may name the one it changes, with
`repo: shop-web` or a list, `repo: [shop-api, shop-web]`. The example `t3-application` is a shop
in three repositories.

**Open Backlog itself is T3.** Its backlog lives in its own repository and plans eleven:

```yaml
spec: 0.1
name: open-backlog
done-folders: month
repos:
  backlog-spec: git@github.com:brandon-castagna/backlog-spec.git
  backlog-cli: git@github.com:brandon-castagna/backlog-cli.git
  backlog-board: git@github.com:brandon-castagna/backlog-board.git
```

70 of its 74 stories name one repository; four name two or three. Because `repo` is optional,
moving from T2 to T3 changes only the configuration.

## T4: a portfolio

A portfolio is a backlog that lists other complete backlogs. The example `t4-portfolio`:

```yaml
spec: 0.1
name: portfolio
backlogs:
  notes: git@github.com:example/notes.git
  photos: git@github.com:example/photos.git
```

The two app backlogs keep their two-line configurations and do not know they are in a portfolio.

**Links across backlogs** are the backlog's name, a slash and the ID: `notes/1.1.1`. The
portfolio's story says "Verify notes/1.1.1 and photos/1.1.2 can both sign in through it", and
each app's story says `Part of portfolio/1.1.1.` These links are text: `parent` never crosses
backlogs, so each backlog stays valid on its own.

## Local clones

Tools find a listed repository or backlog only as a folder with its name beside this one, such
as `../notes/` or `../shop-api/`. The clone URL is for people; tools never fetch it, so they need
no network and no credentials, and no submodules tie repositories together. A missing clone gives
a warning, not an error, as the example `tools/local-clones` tests. This project's backlog lists
eleven repositories and has six cloned beside it: two are not created yet and three live
elsewhere.

Each step up is a change to `config.yml`, plus a folder move at T2. No item file is rewritten and
no ID changes.
