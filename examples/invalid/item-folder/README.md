# Invalid: [item-folder]

The story is in a subfolder of `stories/todo/`; only `done/` may have subfolders.

Everything else is valid, so a validator should report [item-folder] and nothing else. The rule is
in [section 8](../../../spec/README.md#8-layout-and-lifecycle) of the spec.
