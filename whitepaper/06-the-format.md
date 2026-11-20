# The format

An item is a Markdown file [@commonmark] that starts with YAML front matter [@yaml122]. Here is
a real one from the backlog this paper was planned in,
`stories/done/2026-11/1.3.1-repo-scaffold.md`, shortened where marked `…`. The rules are in
section 3 of the specification [@openbacklog-spec].

```markdown
---
id: 1.3.1
type: story
title: Repo scaffold
parent: 1.3
repo: backlog-spec
---

**1.3.1 — [backlog-spec] Repo scaffold**

**As the** spec author, **I want to:**
- Create `backlog-spec` with `README.md` (Open Backlog in a paragraph and a folder tree), and
  folders `spec/`, `whitepaper/`, `examples/`, `schema/`.
- Add `LICENSE` (CC-BY-4.0 for `spec/` and `whitepaper/`), `LICENSE-CODE` (MIT for scripts and
  the schema) and `CONTRIBUTING.md` (…).
- Add `VERSIONING.md`: semver; 0.x may break; …

**So that:**
- Verify the README explains the idea without any other file.
- Verify the license split is unambiguous.
- Verify a newcomer can tell how to propose a change.

## As built

- 2026-11-19: `README.md`: Open Backlog in one paragraph, a backlog folder tree, …
- Checked: all 15 relative links resolve; the README reads on its own.

## Comments

- 2026-11-19 @claude: change set (standard outline)
  - backlog-spec
    - README.md (new)
    - …
```

**The name.** The filename is the ID and a slug: `1.3.1-repo-scaffold.md`. Three parts make it a
story-level item; dropping the last part gives its feature `1.3`, and dropping another its epic
`1`, so the path reads `1-foundation › 1.3-spec › 1.3.1-repo-scaffold`. The slug can change; the
ID cannot.

**The header** holds what a program needs:

- `id`, `type` and `title` are required. `id` repeats the filename's ID, so a tool can check both.
- `parent: 1.3` is the item's one link to the rest of the backlog.
- `repo: backlog-spec` is optional. It names the repository the work changes (section 8).
- The other optional fields, `rank`, `sprint`, `estimate`, `assignee`, `release` and `due`, are
  added when used. Teams may add their own fields, such as `customer: acme`; tools keep them.
- IDs are read as text, so `parent: 1.10` is never mistaken for 1.1, and nobody needs to quote it.

**The body** is free Markdown, with a recommended shape:

- **As a … I want to … So that …**: who it is for, what they want, and how to check it is done.
  Each "So that" line is an acceptance test.
- **As built**: what was done and checked, written when the work is finished.
- **Comments**, last: one list item each, starting with a date and a handle, as in
  `- 2026-11-19 @claude: …`. This is the only part of the body the specification fixes.

Epics and features use the same format with less in them. Feature `1.3-spec.md` has `id`, `type`,
`title` and `parent: 1`, and a body saying what it delivers. It never lists its stories.

## By hand, from nothing

1. Create `backlog/config.yml` with two lines: `spec: 0.1` and `name: my-app`.
2. Create `backlog/epics/1-first-release.md` with `id: 1`, `type: epic` and a `title`.
3. Create `backlog/features/1.1-sign-in.md` with `id: 1.1`, `type: feature`, a `title` and
   `parent: 1`.
4. Create `backlog/stories/todo/1.1.1-email-link.md` with `id: 1.1.1`, `type: story`, a `title`
   and `parent: 1.1`.
5. Commit. The backlog is valid.
