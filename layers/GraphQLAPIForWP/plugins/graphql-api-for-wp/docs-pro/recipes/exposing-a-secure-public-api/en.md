# Exposing a secure public API

non-PRO:

- Single endpoint vs persisted queries
- Disabling all uneeded modules
    - No mutations? Disable mutations
    - No users? Disable users
        Mention how all types are painfully linked together, so related fields will also get disabled
- Custom endpoints => Obfuscation
- querying-sensitive-data-fields
- Which settings (from table wp_options) and meta values (from tables wp_postmeta, wp_usermeta, wp_commentmeta and wp_taxonomymeta) can be queried must be explicitly defined in the configuration.
- contents from:
    src/guides/config/removing-types-from-the-schema.md

PRO:

- Access Control
- Validating by IP
    Reference: "127.0.0.1 works"
    or Client IP
- Disable introspection
    Check:
        src/guides/config/disabling-introspection.md
    Use:
        disabling-schema-introspection-field-in-acl.png
        introspection-disabled-graphiql-error.png
        schema-introspection-field-in-acl.png
    Then copy again to graphql-api.com
Contents from:
    src/guides/config/removing-types-from-the-schema.md