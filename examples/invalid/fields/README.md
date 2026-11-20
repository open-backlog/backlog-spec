# Invalid: [fields]

- `1.1.1` has `type: chore`.
- `1.1.2` has no `title`.
- `1.1.3` has an empty `title`.
- `1.1.4` has `estimate: lots`.

Everything else is valid, so a validator reports [fields] and nothing else. See the
[spec](../../../spec/README.md#3-files).
