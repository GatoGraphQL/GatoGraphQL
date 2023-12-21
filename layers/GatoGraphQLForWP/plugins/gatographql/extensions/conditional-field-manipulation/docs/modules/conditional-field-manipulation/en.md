# Conditional Field Manipulation

Addition of meta directives `@if` and `@unless` to the GraphQL schema, to conditionally execute a nested directive on the field.

<!-- ## Description

📣 _Please read the documentation for module "Composable Directives" to understand what meta directives are, and how to use them._

This extension introduces these meta-directives into the GraphQL schema:

1. `@if`
2. `@unless` -->

## @if

`@if` executes its nested directives only if a condition has value `true`.

In this query, users `"Leo"` and `"Peter"` get their names converted to upper case, since they are in the "special user" array, while `"Martin"` does not:

```graphql
query {
  users {
    name
      @passOnwards(as: "userName")
      @applyField(
        name: "_inArray"
        arguments: {
          value: $userName
          array: ["Leo", "John", "Peter"]
        }
        passOnwardsAs: "isSpecialUser"
      )
      @if(
        condition: $isSpecialUser
      )
        @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "users": [
      {
        "name": "LEO"
      },
      {
        "name": "Martin"
      },
      {
        "name": "PETER"
      }
    ]
  }
}
```

## @unless

Similar to `@if`, but it executes the nested directives when the condition is `false`.

In this query, it is user `"Martin"` who gets the name converted to upper case, while the other ones do not:

```graphql
query {
  users {
    name
      @passOnwards(as: "userName")
      @applyField(
        name: "_inArray"
        arguments: {
          value: $userName
          array: ["Leo", "John", "Peter"]
        }
        passOnwardsAs: "isSpecialUser"
      )
      @unless(
        condition: $isSpecialUser
      )
        @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "users": [
      {
        "name": "Leo"
      },
      {
        "name": "MARTIN"
      },
      {
        "name": "Peter"
      }
    ]
  }
}
```

## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-extensions/docs/modules/all-extensions/en.md)
- [“WordPress Automator” Bundle](../../../../../bundle-extensions/application-glue-and-automator/docs/modules/application-glue-and-automator/en.md)
- [“WordPress Content Translation” Bundle](../../../../../bundle-extensions/content-translation/docs/modules/content-translation/en.md)
- [“WordPress Public API” Bundle](../../../../../bundle-extensions/wordpress-public-api/docs/modules/wordpress-public-api/en.md)

## Tutorial lessons referencing extension

- [Bulk translating block content in multiple posts to a different language](../../../../../docs/tutorial/bulk-translating-block-content-in-multiple-posts-to-a-different-language/en.md)
- [Transforming data from an external API](../../../../../docs/tutorial/transforming-data-from-an-external-api/en.md)
- [Filtering data from an external API](../../../../../docs/tutorial/filtering-data-from-an-external-api/en.md)
- [Importing a post from another WordPress site](../../../../../docs/tutorial/importing-a-post-from-another-wordpress-site/en.md)
