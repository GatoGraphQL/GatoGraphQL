# Low-Level Persisted Query Editing

Make normally-hidden directives (which inject some functionality into the GraphQL server) visible when editing a persisted query.

## Description

In GraphQL, directives are functions that enable to modify the result from a field. For instance, a directive `@strUpperCase` will transform the value of the field into uppercase format.

There are 2 types of directives: those that are applied to the schema and are executed always, on every query; and those that are applied to the query, by the user or the application on the client-side.

In Gato GraphQL, plenty of functionality involved when resolving a query is executed through directives applied to the schema.

For instance, Cache Control works by applying directive `@cacheControl` on the schema. This configuration is by default hidden, and carried out by the plugin through the user interface:

<div class="img-width-1024" markdown=1>

![Defining a cache control policy](../../images/cache-control-entry.png "Defining a cache control policy")

</div>

Similarly, these directives provide Access Control for fields (and similar directives provide Access Control for directives):

- `@disableAccess`
- `@validateIsUserLoggedIn`
- `@validateIsUserNotLoggedIn`
- `@validateDoesLoggedInUserHaveAnyRole`.
- `@validateDoesLoggedInUserHaveAnyCapability`
- `@validateDoesVisitorComeFromAnyAllowedIP`
- `@validateDoesVisitorNotComeFromAnyDeniedIP`

---

This extension `Low-Level Persisted Query Editing` makes all directives to be applied to the schema available in the GraphiQL editor when editing persisted queries, allowing to avoid the user interface and add the schema-type directives already in the persisted query.

<div class="img-width-1024" markdown=1>

![Schema-type directives available in the Persisted queries editor](../../images/low-level-persisted-query-editing.png "Schema-type directives available in the Persisted queries editor")

</div>

As an example, defining Cache Control can then be done directly in the persisted query, by setting directive `@cacheControl` with argument `maxAge` on the field; or Access Control can be defined directly, by adding the corresponding `@validate...` directive directly on the field:

<div class="img-width-1024" markdown=1>

![Schema-type directives](../../images/schema-type-directives.gif "Schema-type directives")

</div>

<!-- 
## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-feature-bundled-extensions/docs/modules/all-feature-bundled-extensions/en.md)
- [“Multiple Query Execution” Bundle](../../../../../bundle-extensions/multiple-query-execution/docs/modules/multiple-query-execution/en.md) -->
