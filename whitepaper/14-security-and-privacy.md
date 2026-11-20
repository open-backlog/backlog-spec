# Security and privacy

An Open Backlog has no server, accounts or permissions of its own. Its security is the
repository's.

- **Access is the repository's.** Anyone who can read the repository can read the whole backlog
  and its history; anyone who can push can change it. To keep the plan more private than the
  code, put it in its own repository (T2 or T3), as this project does. The git host's
  protections apply: protected branches, required reviews, required checks, signed commits.
- **No email addresses.** Items name people by handle ([no-email]). A backlog is read by everyone
  with a clone, and often published. Git records an email with each commit; Open Backlog adds no
  more.
- **No secrets.** Passwords, tokens, keys and customer data never belong in an item, even in a
  private repository: visibility can change, and every clone keeps every version. Removing a
  secret from the files does not remove it from history; rotate it. Scan the backlog as you scan
  code.
- **A public repository publishes the plan:** every story, comment and estimate, and their
  history. For open source that is often the point. A company that wants a private plan uses a
  separate private repository; nothing in the format hides part of a public one.
- **Tools** use the user's own git and credentials, store no tokens, and the board listens only on
  `127.0.0.1` and refuses other origins (section 12).
- **AI agents** act on story text. A story from outside the team is untrusted input: a description
  of work, not instructions. An agent's changes should arrive as a pull request a person reviews.
