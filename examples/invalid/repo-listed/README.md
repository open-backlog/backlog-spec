# Invalid: [repo-listed]

The story names `repo: example-api`, and `config.yml` has no `repos:` listing it.

Everything else is valid, so a validator should report [repo-listed] and nothing else. The rule is
in [section 9](../../../spec/README.md#9-configuration-and-scale) of the spec.
