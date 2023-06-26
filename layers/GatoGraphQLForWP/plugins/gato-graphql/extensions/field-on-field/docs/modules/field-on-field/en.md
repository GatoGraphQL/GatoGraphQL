# Field on Field

`@applyField` directive, to execute a certain field on the resolved field's value

## Description

Applied to some field, the `@applyField` directive allows to execute another field (which is available on the same type and is applied on the same object), and either pass that resulting value along to another directive, or override the value of the field.

This allows us to manipulate the field's value in multiple ways, applying some functionality as provided via the **PHP Functions via Schema** extension, and storing the new result in the response.

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

We can concatenate multiple `@applyFunction`, using the response from one as input into another, thus performing multiple operations on the same field value.

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
          search: " ",
          replaceWith: "-",
          in: $ucTitle
        },
        setResultInResponse: true
      )
  }
}
```

...producing:

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

Together with the **Data Iteration Meta Directives** extension, manipulate all items in an array, shortening each to no more than 20 chars long:

```graphql
{
  posts {
    categoryNames
      @underEachArrayItem(passValueOnwardsAs: "categoryName")
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

Together with the **Data Iteration Meta Directives** extension, convert the first item of an array to upper case:

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
