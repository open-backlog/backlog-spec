# Security and privacy

An Open Backlog has no server, no accounts and no permissions of its own, so its security is
the security of the repository it lives in. That is simpler than a tracker's, and it has
consequences a team should choose knowingly.

## Access is whatever the repository allows

Anyone who can read the repository can read the whole backlog, including every past version of
it; anyone who can push can change it. There is no "read the code but not the plan", no
private story in a shared repository and no field-level permission. A team that needs the plan
to be more private than the code keeps it in a repository of its own (tier T2 or T3, section 8)
with its own access. The backlog this paper was planned in does exactly that: its code
repositories will be public, and its backlog repository will stay private.

The git host's protections apply unchanged: protected branches, required reviews, required
status checks, signed commits. A team that requires review for code can require it for the plan
with the same settings, and a validator run as a required check keeps an invalid backlog out of
the main branch.

## No email addresses

People are named by handle, never by email address: the specification forbids an address in
front matter, in comment lines and in `people.yml` ([no-email]). A backlog is read by everyone
with a clone and is often published, and a list of addresses in plain text is a gift to spammers
and to anyone building a profile. Git itself records an email address with every commit; Open
Backlog cannot change that, but it does not add more, and `people.yml` maps a handle only to a
display name and a username on the git host.

## No secrets

Item files are plain text in history forever. Passwords, tokens, keys, customer data and
anything else that must not leak never belong in a story, a comment or a field, even in a
private repository, because a repository's visibility can change and every clone keeps every
version. Removing a secret from the current files does not remove it from history; it has to be
rotated. A team should run the same secret scanning on the backlog as on its code.

## Public repositories publish the plan

A T1 backlog in a public repository is public: every story, comment and estimate, and the
history of every change. For open source projects that is often the point. For a company it
means the roadmap, the bugs not yet fixed and the names of the people working on them are
visible to anyone. The answer is a separate private backlog repository, not a filter: there is
nothing in the format that can hide part of a public repository.

## Tools and agents

The reference tools (section 12) are designed to keep the same boundary: they use the user's own
git and credentials, store no tokens, and the board listens only on `127.0.0.1` and refuses
requests from other origins, so a web page cannot drive it. They are published to npm, and the
plan is to publish them with provenance once their repositories are public.

An AI agent working a backlog reads story text and acts on it. A story written by someone
outside the team is untrusted input like any other: an agent should treat it as a description
of work, not as instructions to follow, and its changes should arrive as a pull request a person
reviews (section 10). This is the same care a team takes with an issue from a stranger, applied
to a file.
