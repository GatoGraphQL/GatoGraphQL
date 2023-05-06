# Default Directive

`@default` directive, to set a value to null or empty fields

## Description

Directive `@default` accepts two arguments:

1. `value`: the default value, from any scalar type (string, boolean, integer, float or ID).
2. `condition`: if the field must be null or empty, via enum values `IS_NULL` or `IS_EMPTY`. By default it is null.

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

