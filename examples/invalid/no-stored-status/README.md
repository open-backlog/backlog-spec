# Invalid: [no-stored-status]

The story stores its status in a `status` field, instead of letting its folder say it.

Everything else is valid, so a validator should report [no-stored-status] and nothing else. The rule is
in [section 8](../../../spec/README.md#8-layout-and-lifecycle) of the spec.
