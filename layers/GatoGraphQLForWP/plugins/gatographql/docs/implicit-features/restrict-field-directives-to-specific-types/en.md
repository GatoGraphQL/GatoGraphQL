# Restrict Field Directives to Specific Types

Field Directives can be restricted to be applied on fields of some specific type only.

## Description

GraphQL enables to apply directives to fields, to modify their value. For instance, field directive `@strUpperCase` transforms the string in the field to upper case:

```graphql
{
  posts {
    title @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "posts": [
      {
        "title": "HELLO WORLD!"
      }
    ]
  }
}
```

The functionality for `@strUpperCase` makes sense when applied on a `String` (as in the field `Post.title` above), but not on other types, such as `Int`, `Bool`, `Float` or any custom scalar type.

The **Restrict Field Directives to Specific Types** feature solves this problem, by having a field directive define what types it supports.

Field directive `@strUpperCase` has defined to support the following types only:

- `String`
- `ID`
- `AnyBuiltInScalar`

When the type is `String`, the validation succeeds automatically. When the type is `ID` or `AnyBuiltInScalar`, an extra validation `is_string` is performed on the value before it is accepted. For any other type, the validation fails, and an error message is returned.

The query below will then not work, as field `Post.commentCount` has type `Int`, which cannot be converted to upper case:

```graphql
{
  posts {
    commentCount @strUpperCase
  }
}
```

...producing this response:

```json
{
  "errors": [
    {
      "message": "Directive 'strUpperCase' is not supported at this directive location, or for this node in the GraphQL query",
      "locations": [
        {
          "line": 3,
          "column": 19
        }
      ],
      "extensions": {
        "path": [
          "@strUpperCase",
          "commentCount @strUpperCase",
          "posts { ... }",
          "query { ... }"
        ],
        "type": "Post",
        "field": "commentCount @strUpperCase",
        "code": "gql@5.7.2",
        "specifiedBy": "https://spec.graphql.org/draft/#sec-Directives-Are-In-Valid-Locations"
      }
    }
  ],
  "data": {
    "posts": [
      {
        "commentCount": null
      }
    ]
  }
}
```

## Naming Convention

Whenever a field directive is restricted to some type, the type identifier (`String` => `str`, `Int` => `int`, `Boolean` => `bool`, etc) is added at the beginning of the directive name. In addition, the type modifier of "Array" (such as `[String]`) can also be added to the name:

- No restrictions: `@default`
- Arrays: `@arrayUnique`
- `Boolean`: `@boolOpposite`
- `Int`: `@intAdd`
- `JSONObject`: `@objectAddEntry`
- `String`: `@strSubstr`

## Introspection

To find out which are the supported types for each field directive, custom directive extension `fieldDirectiveSupportedTypeNamesOrDescriptions` is available via introspection:

```graphql
query IntrospectionDirectiveExtensions {
  __schema {
    directives {
      name
      extensions {
        fieldDirectiveSupportedTypeNamesOrDescriptions
      }
    }
  }
}
```

The response of `fieldDirectiveSupportedTypeNamesOrDescriptions` will be the names or descriptions of the types the field directives is restricted to, or `null` if it supports any type (i.e. it defines no restrictions).

For other directives, such as Operation Directives, it will always return `null`.

For instance, running the query above will produce:

```json
{
  "data": {
    "__schema": {
      "directives": [
        {
          "name": "applyField",
          "extensions": {
            "fieldDirectiveSupportedTypeNamesOrDescriptions": null
          }
        },
        {
          "name": "arrayUnique",
          "extensions": {
            "fieldDirectiveSupportedTypeNamesOrDescriptions": null
          }
        },
        {
          "name": "boolOpposite",
          "extensions": {
            "fieldDirectiveSupportedTypeNamesOrDescriptions": [
              "Boolean",
              "AnyBuiltInScalar"
            ]
          }
        },
        {
          "name": "default",
          "extensions": {
            "fieldDirectiveSupportedTypeNamesOrDescriptions": [
              "String",
              "Float",
              "Int",
              "Boolean",
              "ID",
              "AnyBuiltInScalar",
              "Relational fields (eg: `{ comments { authorOrDefaultAuthor: author @default(value: 1) { id name } } }`)"
            ]
          }
        },
        {
          "name": "depends",
          "extensions": {
            "fieldDirectiveSupportedTypeNamesOrDescriptions": null
          }
        },
        {
          "name": "intAdd",
          "extensions": {
            "fieldDirectiveSupportedTypeNamesOrDescriptions": [
              "Int",
              "Numeric",
              "AnyBuiltInScalar"
            ]
          }
        },
        {
          "name": "objectAddEntry",
          "extensions": {
            "fieldDirectiveSupportedTypeNamesOrDescriptions": [
              "JSONObject"
            ]
          }
        },
        {
          "name": "strSubstr",
          "extensions": {
            "fieldDirectiveSupportedTypeNamesOrDescriptions": [
              "String",
              "ID",
              "AnyBuiltInScalar"
            ]
          }
        }
      ]
    }
  }
}
```

Please notice that the data includes not only type names, but also descriptions, which is useful to denote a category of types.

For instance, directive `@default` also supports any relational field, like this:

```graphql
{
  comments {
    authorOrDefaultAuthor: author @default(value: 1) {
      id
      name
    }
  }
}
```

Instead of stating every single type (`Post`, `User`, `Comment`, etc), the description `"Relational fields"` already represents all of them.
