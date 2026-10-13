# Spec-Compliant Error Handling

Handle errors in the GraphQL response as the [GraphQL spec](https://spec.graphql.org/draft/#sec-Handling-Execution-Errors) defines.

## Description

This option is disabled by default, as it changes the shape of the response. When enabled, the response changes as follows.

### `null` in a non-null field is passed up to the nearest nullable parent

A non-null field (such as `String!`) cannot be `null`. When it has an error, its `null` is passed up to its parent field and, if that one is non-null too, further up, until reaching a nullable field (or `data`).

Given this schema:

```graphql
type Post {
  title: String!
}

type Query {
  post(id: ID!): Post
}
```

...if field `title` has an error, the response is:

```json
{
  "errors": [
    {
      "message": "...",
      "locations": [{ "line": 3, "column": 5 }],
      "path": ["post", "title"]
    }
  ],
  "data": {
    "post": null
  }
}
```

When disabled, the response is `{ "post": { "title": null } }` instead.

If every field from the root down to the error is non-null, `data` is `null`.

### A `null` item in a list with non-null items makes the whole list `null`

For a field of type `[String!]`, a `null` item makes the whole field `null` (and, if the field is `[String!]!`, the `null` is passed further up).

### Scalar and enum values are validated

The values of the built-in scalars and of enums are coerced to their type, or produce an error when that's not possible:

| Type | Behavior |
| --- | --- |
| `Int` | Must be a 32-bit integer. `1.0` is printed as `1`, and `"123"` as `123` |
| `Float` | Must be a finite number. `1` is printed as `1.0` |
| `String` | Numbers and booleans are printed as strings |
| `Boolean` | Numbers are printed as `true` (non-zero) or `false` |
| `ID` | Always printed as a string, such as `"1"` |
| Enums | Must be one of the values of the enum |

Custom scalars (such as `Date`, `URL` or `JSONObject`) are printed as they are.

### Every error indicates its path in the response

Errors have entry `path`, with the position in the response that produced the error (using the field aliases and the list indexes):

```json
{
  "errors": [
    {
      "message": "Value 'abc' of field 'commentCount' cannot be coerced to type 'Int'",
      "locations": [{ "line": 4, "column": 5 }],
      "path": ["posts", 1, "commentCount"]
    }
  ]
}
```

If an error applies to several positions in the response, it is printed once for each of them.

### Directives that set `null` on a non-null field produce an error

If a directive sets `null` on a non-null field without producing an error itself, an error is added indicating the directive that did it.

## How to use

The spec-compliant error handling can be configured as follows, in order of priority:

✅ Specific mode for the custom endpoint or persisted query, defined in the schema configuration

✅ Default mode, defined in the Settings

If the schema configuration has value `"Default"`, it will use the mode defined in the Settings.

The spec-compliant error handling does not apply to the internal GraphQL server, or to the GraphQL endpoints used by the plugin's own blocks in the WordPress editor, as these process the response in its default shape.
