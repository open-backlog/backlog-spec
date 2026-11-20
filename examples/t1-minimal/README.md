# T1: a minimal backlog

The smallest useful backlog, inside the code repository it plans (here, a notes app):
two lines of `config.yml`, one epic, one feature and three stories, one in each status.

```
backlog/
├── config.yml                      spec: 0.1, name: notes
├── epics/1-launch.md
├── features/1.1-write-a-note.md
└── stories/
    ├── todo/1.1.3-delete-note.md
    ├── doing/1.1.2-edit-note.md
    └── done/1.1.1-new-note.md
```

Starting 1.1.3 is `git mv backlog/stories/todo/1.1.3-delete-note.md backlog/stories/doing/`.
