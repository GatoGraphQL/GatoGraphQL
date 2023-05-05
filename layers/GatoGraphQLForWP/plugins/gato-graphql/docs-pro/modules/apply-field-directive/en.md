# Apply Field Directive

`@applyField` directive, to execute a certain field on the resolved field's value

## Description

Applied to some field, the `@applyField` directive allows to execute another field (which is available on the same type and is applied on the same object), and either pass that resulting value along to another directive, or override the value of the field.

This allows us to manipulate the field's value in multiple ways, applying some functionality as provided via the **Function Fields**, and storing the new result in the response.

In the query below, the `Post.title` field for the object has value `"Hello world!"`. By adding `@applyField` to execute the field `_strUpperCase` (and preceding it with `@passOnwards`, which exports the field value under dynamic `$input`):

```graphql
{
  post(by: { id: 1 }) {
    title
      @passOnwards(as: "input")
      @applyField(
        name: "_strUpperCase"
        arguments: {
          text: $input
        },
        setResultInResponse: true
      )
  }
}
```

...the field value is transformed to upper case, producing:

```json
{
  "data": {
    "post": {
      "title": "HELLO WORLD!"
    }
  }
}
```

## How to use

`@applyField` receives the following arguments:

| Argument | Mandatory? | Description |
| --- | --- | --- |
| `name` | Yes | The name of the field to execute (eg: `_strUpperCase`) |
| `arguments` | No, unless the field has mandatory arguments | A JSON object with the arguments to pass to the field (eg: `{ text: $input }` to execute `_strUpperCase(text: $input)`) |
| `setResultInResponse` | No (can also provide `passOnwardsAs`) | if `true`, it will override the field value in the response |
| `passOnwardsAs` | No (can also provide `setResultInResponse`) | The dynamic variable name under which to define the resulting value |

### `name`

The field name corresponds to some field that lives on the same type where `@applyFunction` is being applied. For instance, in the previous query below, it is applied on the `Post` type.

Because **Global Fields** are present in all types, these can always be referenced via `@applyFunction`. In particular, all fields available via **Function Fields** can be executed (including `_objectProperty`, `_strSubstr`, `_strReplace`, and all others).

### `arguments`

These are the arguments to pass to the field. Please notice that this argument is not mandatory by itself, however when the field has mandatory arguments, then it must be provided.

In the previous query, function `_strUpperCase` has a mandatory `text` input. If it is not provided:

```graphql
{
  post(by: { id: 1 }) {
    title
      @passOnwards(as: "input")
      @applyField(
        name: "_strUpperCase"
        setResultInResponse: true
      )
  }
}
```

...then we obtain an error:

```json
{
  "errors": [
    {
      "message": "Directive 'applyField' failed because its nested function '_strUpperCase' produced errors",
      "causes": [
        {
          "message": "Mandatory argument 'text' in field '_strUpperCase' of type 'Post' has not been provided"
        }
      ]
    }
  ],
  "data": {
    "post": {
      "title": null
    }
  }
}
```

### `setResultInResponse`

When set to `true`, the value from the applied field will override the original field's value in the response.

### `passOnwardsAs`

This argument accepts a `String` corresponding to a dynamic variable name, to export the resulting applied field's value. This value can then be input to another directive, which will eventually override the original value (using `setResultInResponse: true`).

In the query below, there are 2 `@applyFunction` operations applied:

1. Transform to upper case, and pass the value onwards under `$ucTitle`
2. Replace `" "` with `"-"` and override the field value

```graphql
{
  post(by: { id: 1 }) {
    title
      @passOnwards(as: "input")
      @applyField(
        name: "_strUpperCase"
        arguments: {
          text: $input
        },
        passOnwardsAs: "ucTitle"
      )
      @applyField(
        name: "_strReplace"
        arguments: {
          replace: " ",
          with: "-",
          in: $ucTitle
        },
        setResultInResponse: true
      )
  }
}
```

The response will be:

```json
{
  "data": {
    "post": {
      "title": "HELLO-WORLD!"
    }
  }
}
```

## Further examples

Retrieve the opposite value than the field provides:

```graphql
{
  posts {
    id
    notHasComments: hasComments
      @passOnwards(as: "hasComments")
      @applyField(
        name: "_not",
        arguments: {
          value: $hasComments
        },
        setResultInResponse: true
      )
  }
}
```

Manipulate all items in an array, shortening to no more than 20 chars long:

```graphql
{
  posts {
    categoryNames
      @forEach(passValueOnwardsAs: "categoryName")
        @applyField(
          name: "_strSubstr"
          arguments: {
            string: $categoryName,
            offset: 0,
            length: 20
          },
          setResultInResponse: true
        )
  }
}
```

Convert the first item of an array to upper case:

```graphql
{
  posts {
    categoryNames
      @underArrayItem(passOnwardsAs: "value", index: 0)
        @applyField(
          name: "_strUpperCase"
          arguments: {
            text: $value
          },
          setResultInResponse: true
        )
  }
}
```
