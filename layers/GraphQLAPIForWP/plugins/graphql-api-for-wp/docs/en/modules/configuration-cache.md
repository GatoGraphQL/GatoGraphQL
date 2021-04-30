# Configuration Cache

Internal configuration cache, for the mapping between the GraphQL query, and the component model that resolves it

---

The cache is stored under the plugin's `cache/config-via-symfony-cache/` folder.

This module improves performance, by storing to disk the component model required by the GraphQL server to resolve a query.

The first time a query is requested, the component model is calculated and stored. From the second time onwards, it is directly retrieved from disk, thus speeding up the resolution of the query.
