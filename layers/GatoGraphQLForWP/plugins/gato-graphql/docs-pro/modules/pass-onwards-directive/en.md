# Pass Onwards Directive

Add directive `@passOnwards` to make the field's resolved value available to subsequent directives via a dynamic variable. It is the equivalent of the **Field to Input** feature, but allowing to reference the field value within a directive argument.

Directive `@passOnwards` allows us to manipulate the value of the field, by applying any needed directive, which can now receive the value of the field as an input.

## Description

In the query below, field `notHasComments` is composed by obtaining the value from field `hasComments` and calculating its opposite value. This works by:

- Making the field's value available via `@passOnwards`; the field's value can then be input into any subsequent directive
- `@applyField` takes the input (exported under dynamic variable `$postHasComments`), applies the global field `not` into it, and stores the result back into the field

```graphql
{
  posts {
    id
    hasComments
    notHasComments: hasComments
      @passOnwards(as: "postHasComments")
      @applyField(
        name: "_not"
        arguments: {
          value: $postHasComments
        },
        setResultInResponse: true
      )
  }
}
```

This will produce:

```json
{
  "data": {
    "posts": [
      {
        "id": 1724,
        "hasComments": true,
        "notHasComments": false
      },
      {
        "id": 358,
        "hasComments": false,
        "notHasComments": true
      },
      {
        "id": 555,
        "hasComments": false,
        "notHasComments": true
      }
    ]
  }
}
```
