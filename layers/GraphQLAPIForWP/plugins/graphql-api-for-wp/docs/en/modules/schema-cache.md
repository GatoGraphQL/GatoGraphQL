# Schema Cache

Cache the generated schema for each custom endpoint and persisted query

---

The GraphQL API for WordPress supports a dynamic schema, generated on runtime, which allows to customize access to data by the different custom endpoints and persisted queries (for instance, involving different Access Control rules to expose or hide fields).

The dynamic schema may need to be generated when executing a query. Caching the schema allows the server to reuse an already-generated schema from a previous request, thus speeding up its execution.

Caching the schema can not work when the API is private, because the schema metadata can change depending on the user accessing it (eg: some users can be denied access to some field). If the API is public, though, the schema metadata is unique, and available to everyone, hence it can be safely cached.

As a consequence, this module `Schema Cache` can only be enabled when module `Public/Private Schema` is disabled.

This module must be disabled for development.
