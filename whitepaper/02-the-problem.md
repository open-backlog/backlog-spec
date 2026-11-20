# The problem

Most agile teams plan in one place and build in another. The code is in a git repository: every
change is a commit, reviewed in a pull request, on a branch, with a history anyone with a clone
can read [@progit]. The backlog that says what the code is for (the epics, features and stories)
is in a hosted tracker, with its own accounts, its own permissions, its own history and its own
API. The two are joined by habit: a story key in a branch name, a link in a pull request, someone
remembering to move a card.

That split is so common that it rarely looks like a choice. This section describes what it costs.

## The plan drifts from the work

A story is marked done on the board while its pull request is still open. A pull request is
merged, and the card stays in "In progress" for a week. A feature is re-scoped in a design
document and the stories under it never change. Nothing in either system can notice, because
neither can see the other: the board does not know which branch holds the work, and the
repository does not know what the work was meant to deliver.

Each team pays for this in its own small way. Someone reconciles the board before the stand-up
meeting. A release note is written from memory, because the tracker and the commits tell
different stories. A new team member reads a story, then finds the code does something else, and
cannot tell which of the two is right.

## Planning has no review

Code changes are proposed, reviewed and merged. Changes to the plan usually are not. A story's
acceptance criteria are edited in a web form; a feature moves to another epic with a drag; an
estimate changes and nobody sees the old one. The tracker may keep an activity log, but it is not
something a team can branch, diff, review or revert the way it does with code. The plan is the
one part of the work that skips the process the team trusts for everything else.

## Every reader needs a login

To read the backlog, a person needs an account on the tracker, a seat in its licence and the
right permissions in the right project. A program needs more: an API key or token tied to a
person's account [@jira-basic-auth; @asana-pat], a client for that vendor's API, and code that
pages through results and copes with rate limits. A contractor who has the repository but not the
tracker sees code with no plan. A script that wants to list the stories in a release is written
against one vendor and breaks when the team moves to another.

The backlog also outlives the tracker less often than the code does. Code moves between hosts
with a `git push`; a backlog moves with an export, an import, a field mapping and the loss of
whatever the next tool cannot represent.

## AI agents can't see the plan

AI coding agents now do real work in repositories: they read the code, change it, run the tests
and open pull requests. What they read well is plain text in the working tree. What they cannot
reach, without extra set-up, is a third-party service: the story they are working on, the
feature it belongs to, the decision made last month about why it is shaped this way.

So a person copies the story into the prompt, and the agent's context is whatever was pasted.
When the work is finished, a person moves the card. Giving the agent direct access means another
token, another integration and another set of permissions, and the agent still edits the plan
through an API rather than with the file operations it already uses for code. The more of the
work agents do, the more this gap costs, and the more of the plan lives only in the prompts.

## The cost, in short

None of these is a disaster on its own. Together they mean the plan is the least reliable record
a team keeps: stored apart from the work, changed without review, readable only through an
account, and out of reach of the tools that are doing a growing share of the work. The rest of
this paper argues that the backlog can live where the code lives, as files in the same
repository, and that doing so removes each of these costs rather than managing it.
