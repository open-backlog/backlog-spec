# The problem

Most agile teams plan in one place and build in another. The code is in git: every change is a
commit on a branch, reviewed in a pull request [@progit]. The backlog is in a hosted tracker, with
its own accounts, permissions, history and API. The two are joined by habit: a story key in a
branch name, a link in a pull request, someone remembering to move a card.

**The plan drifts from the work.** A story is marked done while its pull request is still open. A
merged change leaves its card in "In progress" for a week. A feature is re-scoped in a design
document and its stories never change. Neither system can see the other, so someone reconciles
the board before the stand-up, and release notes are written from memory.

**Planning has no review.** Code is proposed, reviewed and merged. The plan is edited in a web
form: acceptance criteria change, a feature moves to another epic, an estimate is overwritten. A
team cannot branch, diff, review or revert it the way it does code.

**Every reader needs a login.** A person needs an account and a seat. A program needs an API token
tied to a person's account [@jira-basic-auth; @asana-pat] and code written for one vendor's API.
Moving to another tracker means an export, an import, a field mapping and the loss of whatever
the next tool cannot represent.

**AI agents can't see the plan.** Coding agents now read, change and test code in the repository.
They cannot reach a third-party tracker without another token and another integration. So a
person pastes the story into the prompt and moves the card afterwards, and more of the plan lives
only in prompts.

Together these make the plan the least reliable record a team keeps. Putting the backlog in the
repository, as files, removes each of these costs.
