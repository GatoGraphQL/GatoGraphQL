# Field Default Value

`@default` directive, to set a value to null or empty fields.

## Description

Directive `@default` accepts two arguments:

1. `value`: the default value, from any scalar type (string, boolean, integer, float or ID).
2. `condition`: the condition that must be true to apply the default value, as one of enum values `IS_NULL`, `IS_EMPTY` or `ALWAYS`. By default it is `IS_NULL`.

In the example below, when a post does not have a featured image, field `featuredImage` returns `null`:

```graphql
{
  post(by: { id: 1 }) {
    featuredImage {
      id
      src
    }
  }
}
```

```json
{
  "data": {
    "post": {
      "featuredImage": null
    }
  }
}
```

By using `@default`, we can then retrieve some default image:

```graphql
{
  post(by: { id: 1 }) {
    featuredImage @default(value: 55) {
      id
      src
    }
  }
}
```

```json
{
  "data": {
    "post": {
      "featuredImage": {
        "id": 55,
        "src": "http://mysite.com/wp-content/uploads/my-default-image.png"
      }
    }
  }
}
```
<!-- 
## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-feature-bundled-extensions/docs/modules/all-feature-bundled-extensions/en.md)
- [“Caching” Bundle](../../../../../bundle-extensions/caching/docs/modules/caching/en.md)
- [“Custom Endpoints” Bundle](../../../../../bundle-extensions/custom-endpoints/docs/modules/custom-endpoints/en.md)
- [“Deprecation” Bundle](../../../../../bundle-extensions/deprecation/docs/modules/deprecation/en.md)
- [“Multiple Query Execution” Bundle](../../../../../bundle-extensions/multiple-query-execution/docs/modules/multiple-query-execution/en.md)
- [“Persisted Queries” Bundle](../../../../../bundle-extensions/persisted-queries/docs/modules/persisted-queries/en.md)
- [“Query Functions” Bundle](../../../../../bundle-extensions/query-functions/docs/modules/query-functions/en.md)
- [“Schema Extensions” Bundle](../../../../../bundle-extensions/schema-extensions/docs/modules/schema-extensions/en.md) -->
