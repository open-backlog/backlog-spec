# Tool check: [keep-unknown]

A valid backlog whose story has fields the spec doesn't define (`customer`, `risk` and a
nested `links`), in an unusual order, and whose `config.yml` has a key of its own
(`board-colour`).

A correct tool can move the story, change its title or add a comment, and afterwards the
file still has every unknown field with the same value, in the same order, and the rest of
the body unchanged. Diff the file before and after to check.
