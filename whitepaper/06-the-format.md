# The format

An item is one Markdown file [@commonmark] that starts with a few lines of YAML [@yaml122]. This
section walks through a real one: story 1.3.1 from the backlog this paper was planned in, the
story that created the repository the specification lives in. The rules it follows are in
section 3 of the specification [@openbacklog-spec]; here the point is to show how they read.

## A real story

Here is the file `backlog/stories/done/2026-11/1.3.1-repo-scaffold.md`, with its longer
paragraphs cut short (marked `…`):

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

## The name

The filename is the ID, a dash and a slug: `1.3.1-repo-scaffold.md`. The ID says where the story
sits, the slug says what it is, and together they make a directory listing readable without
opening a file. The ID has three parts, so this is a story-level item. Dropping the last part
gives its feature, `1.3`; dropping another gives its epic, `1`. In this backlog those are
`1.3-spec.md` ("Spec draft") and `1-foundation.md` ("Foundation"), so the path to the story reads
`1-foundation › 1.3-spec › 1.3.1-repo-scaffold`: the first story of the spec-draft feature of the
foundation epic. The slug can be reworded at any time; the ID cannot.

## The header

The front matter is a YAML mapping between two `---` lines, and it holds only what a program
needs to find, check and order the item:

- `id`, `type` and `title` are required. `id` repeats the filename's ID, so the file still
  identifies itself if it is renamed or copied, and a tool can check that the two agree.
- `parent: 1.3` is the one link to the rest of the backlog. The feature does not list this
  story; it is a child of `1.3` because it says so.
- `repo: backlog-spec` is optional. This backlog plans work across several repositories (tier
  T3, section 8), so each story names the one it changes, and `config.yml` lists where each
  repository is cloned from.

The other optional fields (`rank`, `sprint`, `estimate`, `assignee`, `release`, `due`) are
absent here, as they are from most items in a small backlog. A team adds a field when it starts
using it, and may add fields of its own, such as `customer: acme`; tools keep fields they do not
know. What never appears is a field for something that can be worked out: no `status`, no
`children`, no `progress`.

Values that look like numbers are read as the text written. YAML would read `parent: 1.10` as
the number 1.1 [@yaml122], which is a different feature, so the specification has tools read
IDs as text, and a person never needs to quote them.

## The body

Everything after the header is Markdown, written for people. The specification recommends a
shape for stories and requires only one detail of it:

- **As a … I want to … So that …** says who the work is for, what they want, and how anyone will
  know it is done. Writing each "So that" line as something that can be checked ("Verify the
  README explains the idea without any other file") turns it into the story's acceptance test.
- **As built** is written when the work is finished: what was actually done, what was checked,
  and anything that differs from the plan. It is the part of a story most useful a year later.
- **Comments** come last, one list item each, oldest first, starting with a date and a handle:
  `- 2026-11-19 @claude: …`. This is the one part of the body the specification fixes, so tools
  can add comments and read them back. A comment may continue on indented lines, as the change
  set above does.

People are named by handle, never by email address. A backlog is read by everyone with a clone,
and often published; `people.yml` can map a handle to a name, but an address does not belong in
the files.

## Other items

Epics and features use the same format with less in them. The feature this story belongs to is
`backlog/features/1.3-spec.md`; its header is `id: 1.3`, `type: feature`,
`title: Spec draft` and `parent: 1`, and its body, in the same As a / So that shape, says what
the feature delivers. It holds no list of its stories and no
status: both come from the story files.

## By hand, from nothing

A complete backlog can be started with a text editor:

1. Create `backlog/config.yml` with two lines: `spec: 0.1` and `name: my-app`.
2. Create `backlog/epics/1-first-release.md` with `id: 1`, `type: epic` and a `title`.
3. Create `backlog/features/1.1-sign-in.md` with `id: 1.1`, `type: feature`, a `title` and
   `parent: 1`.
4. Create `backlog/stories/todo/1.1.1-email-link.md` with `id: 1.1.1`, `type: story`, a
   `title`, `parent: 1.1`, and an As a / So that body.
5. Commit. The backlog is valid, and the next section shows how the story moves.
