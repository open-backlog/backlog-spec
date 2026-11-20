# Invalid: [field-format]

The story has `estimate: lots`; an estimate is a number.

Everything else is valid, so a validator should report [field-format] and nothing else. The rule is
in [section 4](../../../spec/README.md#4-fields) of the spec.
