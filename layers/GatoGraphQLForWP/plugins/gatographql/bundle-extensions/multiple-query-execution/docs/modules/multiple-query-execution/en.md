# Multiple Query Execution

Multiple query execution combines multiple queries into a single query, executing them in the same requested order, while allowing them to communicate state with each other via dynamic variables.

```graphql
query GetLoggedInUserName {
  me {
    name @export(as: "loggedInUserName")
  }
}

query FindPosts @depends(on: "GetLoggedInUserName") {
  posts(filter: { search: $loggedInUserName }) {
    id
    title
  }
}
```

This feature offers several benefits:

- **Performance**: Instead of executing a query against the GraphQL server, then wait for its response, and then use that result to execute another query, combine the queries together into one and execute them in a single request, thus avoiding the latency from the multiple HTTP connections.
- **Functionality**: Adapt field values as needed. Query some value from the database in one operation, and transform it and inject it into another field on another operation.
- **Modularity**: Manage your GraphQL queries into atomic operations (or logical units) that depend on each other, and that can be conditionally executed based on the result from a previous operation.

<!-- ## List of bundled extensions

- [Multiple Query Execution](../../../../../extensions/multiple-query-execution/docs/modules/multiple-query-execution/en.md) -->
