# Invalid: [parent-epic]

The feature's `parent` is the story `1.1.1`, not an epic. (This is also the loop 1.1 → 1.1.1 → 1.1.)

Everything else is valid, so a validator should report [parent-epic] and nothing else. The rule is
in [section 3](../../../spec/README.md#3-the-hierarchy) of the spec.
