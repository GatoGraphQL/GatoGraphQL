# Deprecation Notifier

Send deprecations in the response to the query (and not only when doing introspection), under the top-level entry `extensions`.

## Description

Whenever a deprecated field is queried, a deprecation message is returned in that same GraphQL response, under the top-level entry `extensions`.

This alerts users of our APIs to upgrade their use of the schema, even when they are not paying attention to the introspection query.

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

## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-extensions/docs/modules/all-extensions/en.md)
- [“Public API” Bundle](../../../../../bundle-extensions/public-api/docs/modules/public-api/en.md)

<!-- ## Tutorial lessons referencing extension -->
