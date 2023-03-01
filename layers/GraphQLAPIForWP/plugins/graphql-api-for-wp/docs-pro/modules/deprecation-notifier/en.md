# Deprecation Notifier

Send deprecations in the response to the query (and not only when doing introspection), under the top-level entry `extensions`

## How it works

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
