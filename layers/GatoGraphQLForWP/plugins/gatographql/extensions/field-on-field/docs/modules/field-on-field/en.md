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
<!-- 
## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-feature-bundled-extensions/docs/modules/all-feature-bundled-extensions/en.md)
- [“Caching” Bundle](../../../../../bundle-extensions/caching/docs/modules/caching/en.md)
- [“Custom Endpoints” Bundle](../../../../../bundle-extensions/custom-endpoints/docs/modules/custom-endpoints/en.md)
- [“Private GraphQL Server for WordPress” Bundle](../../../../../bundle-extensions/deprecation/docs/modules/deprecation/en.md)
- [“Selective Content Import, Export & Sync for WordPress” Bundle](../../../../../bundle-extensions/selective-content-import-export-and-sync-for-wordpress/docs/modules/selective-content-import-export-and-sync-for-wordpress/en.md)
- [“Simplest WordPress Content Translation” Bundle](../../../../../bundle-extensions/simplest-wordpress-content-translation/docs/modules/simplest-wordpress-content-translation/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md)
- [“Unhindered WordPress Email Notifications” Bundle](../../../../../bundle-extensions/unhindered-wordpress-email-notifications/docs/modules/unhindered-wordpress-email-notifications/en.md)
- [“Versatile WordPress Request API” Bundle](../../../../../bundle-extensions/versatile-wordpress-request-api/docs/modules/versatile-wordpress-request-api/en.md) -->
