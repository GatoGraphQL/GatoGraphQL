# Deprecation

Evolve the GraphQL schema by deprecating fields, and explaining how to replace them, through a user interface.

---

With **Field Deprecation**, the extension provides a user interface to deprecate fields from the GraphQL schema.

<div class="img-width-1024" markdown=1>

![Field Deprecation List editor](../../../../../extensions/field-deprecation/docs/images/field-deprecation-list.png "Field Deprecation List editor")

</div>

---

With the **Deprecation Notifier**, whenever a deprecated field is queried, a deprecation message is returned in that same GraphQL response, under the top-level entry `extensions`.

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

<!-- ## List of bundled extensions

- [Deprecation Notifier](../../../../../extensions/deprecation-notifier/docs/modules/deprecation-notifier/en.md)
- [Field Deprecation](../../../../../extensions/field-deprecation/docs/modules/field-deprecation/en.md) -->
