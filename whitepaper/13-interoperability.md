# Interoperability

**This is future work.** The first release has no import, export or sync with other tools.

Teams with years of history in a tracker will want to bring it, and some will want both for a
while. Others will leave, and that should be easy too.

**Import.** Each tracker item becomes one file:

| Open Backlog | Jira | Asana | Rally |
|---|---|---|---|
| epic | Epic | a section or project | a portfolio item |
| feature | a custom level | a section or parent task | a portfolio item (Feature) |
| story-level item | Story, Bug, Task | task | user story, defect, task |
| status folder | workflow status, grouped into three | completed, or the section | schedule state, grouped into three |
| `assignee` | Assignee | Assignee | Owner |
| `estimate` | Story points | a custom field | Plan Estimate |

Field names vary with plan and configuration: Jira's levels above the epic are configured per
site [@jira-hierarchy] and Rally's portfolio types per workspace [@rally-portfolio], so an
importer asks which level is which. It keeps the tracker's key as an extra field, such as
`jira: SHOP-123`, replaces email addresses with handles, and groups extra statuses into the three
folders. The tracker's history stays in the tracker.

**Export** is the reverse, and simpler: one row per file, with the hierarchy from `parent` and the
status from the folder. CSV is enough for most trackers.

**Side by side.** If both a tracker and the files can change, every item has two copies, and they
drift. A bridge should make one side the source of truth, usually the files, with the tracker as a
read-only mirror. This project does that by hand with Asana. Two-way sync is a product, not a
format, and is not planned.

After the first release, CSV import and export for Jira, Asana and Rally is a candidate (section
17). Anyone can build it: the specification, schema and examples are all an importer needs.
