# Invalid: [parent-exists]

The story's `parent` is `1.9`, which no item has as its `id`.

Everything else is valid, so a validator should report [parent-exists] and nothing else. The rule is
in [section 3](../../../spec/README.md#3-the-hierarchy) of the spec.
