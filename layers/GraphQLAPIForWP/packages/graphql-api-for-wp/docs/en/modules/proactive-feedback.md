# Proactive Feedback

Use the top-level entry `extensions` to send data concerning deprecations, warnings, logs, notices and traces in the response to the query.

## Deprecations

Deprecations are returned in the same query involving deprecated fields, and not only when doing introspection.

For instance, running this query, where field `isPublished` is deprecated:

```graphql
query {
  posts {
    title
    isPublished
  }
}
```

...will produce this response:

```json
{
  "extensions": {
    "deprecations": [
      {
        "message": "Use 'isStatus(status:published)' instead of 'isPublished'",
        "extensions": {
          ...
        }
      }
    ]
  },
  "data": {
    "posts": [
      ...
    ]
  }
}
```

## Warnings

Warning are issues which can be considered non-blocking, i.e. they enhance the query but do not break it. While in standard GraphQL they would be considered errors, the GraphQL API takes a more lenient approach towards them, by ignoring their execution only, and not the whole query.

For instance, passing parameter `limit` with the wrong type will not stop execution of the query, it will just ignore this parameter (hence, the response will bring more results that are needed, but that's not a breaking issue) and provide an appropriate `warning` message.

Executing this query:

```graphql
query {
  posts(limit:3.5) {
    title
  }
}
```

...will produce this response:

```json
{
  "extensions": {
    "warnings": [
      {
        "message": "For field 'posts', casting value '3.5' for argument 'limit' to type 'int' failed, so it has been ignored",
        "extensions": {
          ...
        }
      }
    ]
  },
  "data": {
    "posts": [
      ...
    ]
  }
}
```

Similarly, passing fields arguments which have not been added to the schema is considered a warning, and not an error.

Executing this query:

```graphql
query {
  posts {
    title
    date(nonExistingFieldName: "something")
  }
}
```

...will produce this response:

```json
{
  "extensions": {
    "warnings": [
      {
        "message": "Argument with name 'nonExistingFieldName' has not been documented in the schema, so it may have no effect (it has not been removed from the query, though)",
        "extensions": {
          ...
        }
      }
    ]
  },
  "data": {
    "posts": [
      ...
    ]
  }
}
```

## Logs, Notices and Traces

Any resolver (for fields and directives) can log any piece of information, inform the user about it through a notice, and analyze it through tracing, as to provide the developer with useful information to debug/improve the application.
