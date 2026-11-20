# Invalid: [parent]

- `1.1.1` is a story whose parent is the epic `1`.
- `1.1.2` is a story with no parent.
- `2.1` is a feature whose parent is the story `1.1.3`.
- `2` is an epic whose parent is the feature `1.1`.
- `C1` is a capability with a parent.

Everything else is valid, so a validator reports [parent] and nothing else. See the
[spec](../../../spec/README.md#2-items).
