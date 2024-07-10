# Lesson 10: Retrieving structured data from blocks

We can iterate the (Gutenberg) blocks in the post and extract the attributes from deep within the block structure, unlocking these attributes to be fed into any functionality in our site.

For instance, by extracting all the image URLs contained in the `core/image` blocks in a post, we can create an image carousel with all these images.

In addition, as the block's attributes now become widely accessible (outside of the block editor), we can truly consider these as structured content, and expose them via an API to power all our applications (such as a mobile app or a newsletter).

This allows us to treat blocks as the single source of truth for all our content, and distribute it across different mediums and apps following the [COPE (“Create Once, Publish Everywhere”) strategy](https://www.smashingmagazine.com/2019/10/create-once-publish-everywhere-wordpress/).

This tutorial lesson demonstrates how to retrieve the image URLs from all `core/image` blocks in a post.

## Extracting the image URLs from all `core/image` blocks in a post

This GraphQL query uses field `blockFlattenedDataItems` to fetch all blocks in the post (including inner blocks) while filtering them by `core/image` type. It then iterates all entries, extracts property `attributes.url` from each, and uses it to override the field value. Finally it removes duplicate URLs (for if two `core/image` blocks use the same image) via `@arrayUnique`:

```graphql
query GetImageBlockImageURLs($postID: ID!) {
  post(by: { id: $postID } ) {
    coreImageURLs: blockFlattenedDataItems(
      filterBy: { include: "core/image" }
    )
      @underEachArrayItem(
        passValueOnwardsAs: "blockDataItem"
      )
        @applyField(
          name: "_objectProperty"
          arguments: {
            object: $blockDataItem,
            by: {
              path: "attributes.url"
            }
          }
          setResultInResponse: true
        )
      @arrayUnique
  }
}
```

The response is:

```json
{
  "data": {
    "post": {
      "coreImageURLs": [
        "https://d.pr/i/fW6V3V+",
        "https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-1024x622.jpg",
        "https://gatographql.lndo.site/wp-content/uploads/GatoGraphQL-logo-suki-1024x598.png"
      ]
    }
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

`@underEachArrayItem` (provided by the [**Field Value Iteration and Manipulation**](https://gatographql.com/extensions/field-value-iteration-and-manipulation/) extension) is a [composable directive](https://gatographql.com/guides/schema/using-composable-directives/) (or "meta directive", it is a directive which can contain nested directives) that iterates over an array of elements, and applies its nested directive on each of them.

`@underEachArrayItem` helps bridge GraphQL types, as it can make a field that returns a `[String]` value, be applied a directive that receives a `String` value as input (or other combinations).

For instance, in the query below:

- Field `User.capabilities` returns `[String]`
- Directive `@strUpperCase` receives `String`

Thanks to `@underEachArrayItem`, we can convert all capability items to upper case:

```graphql
{
  user(by: { id: 3 }) {
    capabilities
      @underEachArrayItem
        @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "user": {
      "capabilities": [
        "LEVEL_0",
        "READ",
        "READ_PRIVATE_EVENTS",
        "READ_PRIVATE_LOCATIONS"
      ]
    }
  }
}
```

</div>