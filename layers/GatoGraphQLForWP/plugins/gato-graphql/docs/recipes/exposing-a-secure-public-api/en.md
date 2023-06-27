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
    Then copy again to gatographql.com
Contents from:
    src/guides/config/removing-types-from-the-schema.md



## Exposed the `__schema` introspection field in the ACLs

The `__schema` field is now exposed in the Access Control Lists:

![__schema field in the Access Control List](../../images/releases/v09/schema-introspection-field-in-acl.png)

This allows us to disable introspection for the single endpoint or custom endpoints using access control rules, such as:

- Disable always
- Disable for logged-out users
- Disable for users with or without a certain role or capability

![Disabling the __schema field in the Access Control List](../../images/releases/v09/disabling-schema-introspection-field-in-acl.png)

For instance, opening the GraphiQL client on a custom endpoint after disabling access to `__schema` we get an error:

> Uncaught (in promise) Error: Invalid or incomplete introspection result. Ensure that you are passing "data" property of introspection response and no "errors" was returned alongside: { __schema: null }

![GraphiQL error from disabled introspection](../../images/releases/v09/introspection-disabled-graphiql-error.png)

