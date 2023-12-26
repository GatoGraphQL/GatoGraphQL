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

- [“All in One Toolbox for WordPress” Bundle](../../../../../bundle-extensions/all-in-one-toolbox-for-wordpress/docs/modules/all-in-one-toolbox-for-wordpress/en.md)
- [“Automated Content Translation & Sync for WordPress Multisite” Bundle](../../../../../bundle-extensions/automated-content-translation-and-sync-for-wordpress-multisite/docs/modules/automated-content-translation-and-sync-for-wordpress-multisite/en.md)
- [“Better WordPress Webhooks” Bundle](../../../../../bundle-extensions/better-wordpress-webhooks/docs/modules/better-wordpress-webhooks/en.md)
- [“Easy WordPress Bulk Transform & Update” Bundle](../../../../../bundle-extensions/easy-wordpress-bulk-transform-and-update/docs/modules/easy-wordpress-bulk-transform-and-update/en.md)
- [“Private GraphQL Server for WordPress” Bundle](../../../../../bundle-extensions/private-graphql-server-for-wordpress/docs/modules/private-graphql-server-for-wordpress/en.md)
- [“Responsible WordPress Public API” Bundle](../../../../../bundle-extensions/responsible-wordpress-public-api/docs/modules/responsible-wordpress-public-api/en.md)
- [“Selective Content Import, Export & Sync for WordPress” Bundle](../../../../../bundle-extensions/selective-content-import-export-and-sync-for-wordpress/docs/modules/selective-content-import-export-and-sync-for-wordpress/en.md)
- [“Simplest WordPress Content Translation” Bundle](../../../../../bundle-extensions/simplest-wordpress-content-translation/docs/modules/simplest-wordpress-content-translation/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md)
- [“Versatile WordPress Request API” Bundle](../../../../../bundle-extensions/versatile-wordpress-request-api/docs/modules/versatile-wordpress-request-api/en.md)

<!-- ## Tutorial lessons referencing extension

- [Bulk translating block content in multiple posts to a different language](../../../../../docs/tutorial/bulk-translating-block-content-in-multiple-posts-to-a-different-language/en.md)
- [Transforming data from an external API](../../../../../docs/tutorial/transforming-data-from-an-external-api/en.md)
- [Filtering data from an external API](../../../../../docs/tutorial/filtering-data-from-an-external-api/en.md)
- [Importing a post from another WordPress site](../../../../../docs/tutorial/importing-a-post-from-another-wordpress-site/en.md) -->
