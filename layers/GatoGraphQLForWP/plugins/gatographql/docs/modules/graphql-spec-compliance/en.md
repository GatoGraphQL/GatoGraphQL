# GraphQL Spec Compliance

Follow the [GraphQL spec](https://spec.graphql.org/draft/) where Gato GraphQL behaves differently by default.

## Description

Gato GraphQL deviates from the GraphQL spec in a few places, to make the response easier to use. Each of these behaviors can be changed to follow the spec, via its own option. All options are disabled by default.

### Pass a `null` up to the nearest nullable parent

A non-null field (such as `String!`) cannot be `null`. When it has an error, the GraphQL spec passes its `null` up to its parent field and, if that one is non-null too, further up, until reaching a nullable field (or `data`).

Given this schema:

```graphql
type Post {
  title: String!
}

type Query {
  post(id: ID!): Post
}
```

...if field `title` has an error, by default the response is:

```json
{
  "data": {
    "post": {
      "title": null
    }
  }
}
```

With this option enabled, the response is:

```json
{
  "data": {
    "post": null
  }
}
```

If every field from the root down to the error is non-null, `data` is `null`.

The same happens with lists with non-null items: for a field of type `[String!]`, a `null` item makes the whole field `null` (and, if the field is `[String!]!`, the `null` is passed further up).

If a directive sets `null` on a non-null field (or a `null` item on a list with non-null items) without producing an error itself, an error is added indicating the directive that did it.

### Coerce scalar and enum values

The values of the built-in scalars and of enums are coerced to their type, or produce an error when that's not possible:

| Type | Behavior |
| --- | --- |
| `Int` | Must be a 32-bit integer. `1.0` is printed as `1`, and `"123"` as `123` |
| `Float` | Must be a finite number. `1` is printed as `1.0` |
| `String` | Numbers and booleans are printed as strings |
| `Boolean` | Numbers are printed as `true` (non-zero) or `false` |
| `ID` | Printed as a string, such as `"1"` (by default, it is printed as `1`) |
| Enums | Must be one of the values of the enum |

Custom scalars (such as `Date`, `URL` or `JSONObject`) are printed as they are.

### Add the response path to errors

Gato GraphQL always prints the path of the error in the query, under `extensions.path`.

With this option enabled, errors also have entry `path`, with the position in the response that produced the error (using the field aliases and the list indexes):

```json
{
  "errors": [
    {
      "message": "...",
      "locations": [{ "line": 4, "column": 5 }],
      "path": ["posts", 1, "title"]
    }
  ]
}
```

If an error applies to several positions in the response, it is printed once for each of them.

## How to use

Each option can be configured as follows, in order of priority:

✅ Specific mode for the custom endpoint or persisted query, defined in the schema configuration

✅ Default mode, defined in the Settings

If the schema configuration has value `"Default"`, it will use the mode defined in the Settings.

These options do not apply to the internal GraphQL server, or to the GraphQL endpoints used by the plugin's own blocks in the WordPress editor, as these process the response in its default shape.
