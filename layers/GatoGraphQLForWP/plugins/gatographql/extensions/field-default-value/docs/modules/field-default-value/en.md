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

## Bundles including extension

- [“All in One Toolbox for WordPress” Bundle](../../../../../bundle-extensions/all-in-one-toolbox-for-wordpress/docs/modules/all-in-one-toolbox-for-wordpress/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md)
- [“Responsible WordPress Public API” Bundle](../../../../../bundle-extensions/responsible-wordpress-public-api/docs/modules/responsible-wordpress-public-api/en.md)

<!-- ## Tutorial lessons referencing extension

- [Transforming data from an external API](../../../../../docs/tutorial/transforming-data-from-an-external-api/en.md) -->
