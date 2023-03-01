# Schema Introspection Cache

Cache the generated schema when doing introspection, for each custom endpoint and persisted query

## How it works

The GraphQL API for WordPress supports a dynamic schema, generated on runtime, which allows to customize access to data by the different custom endpoints and persisted queries (for instance, involving different Access Control rules to expose or hide fields).

The dynamic schema needs to be generated when executing the introspection query. By caching it to disk, the server can reuse an already-generated schema from a previous introspection request, thus improving speed.

## When it is disabled

Caching the introspection schema is disabled when the API is private, because the schema metadata can change depending on the context (eg: some users can be denied access to some field).

If the API is public, though, the schema metadata is unique, and available to everyone, hence it can be safely cached.

As a consequence, this module `Schema Introspection Cache` can only be enabled when module `Public/Private Schema` is disabled.
