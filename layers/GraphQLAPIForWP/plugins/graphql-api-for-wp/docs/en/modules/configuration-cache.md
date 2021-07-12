# Configuration Cache

Internal configuration cache, to store results from operations to disk

---

The cache is stored under the plugin's `cache/config-via-symfony-cache/` folder.

This module improves performance, by storing to disk, and reusing from then on, the results from several operations. Such operations can include:

- the component model required by the GraphQL server to resolve a query
- the results from expensive operations (such as calling external APIs)

This module is enabled by default, unless global caching is disabled (via environment variable, or constant in `wp-config.php`).