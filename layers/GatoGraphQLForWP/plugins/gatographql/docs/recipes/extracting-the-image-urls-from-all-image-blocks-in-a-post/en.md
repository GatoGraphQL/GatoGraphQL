# Extracting the image URLs from all Image blocks in a post

We can iterate the inner structure of (Gutenberg) blocks in the post content, and extract those desired items.

This recipe demonstrates how to extract the image URLs from all `core/image` blocks in a post.

## GraphQL query to extract the image URLs from all `core/image` blocks in a post

This GraphQL query uses field `blockFlattenedDataItems` to fetch all blocks in the post (including inner blocks) while filtering them by `core/image` type. It then iterates all entries, extracts property `attributes.url` from each, and uses it to override the field value. Finally it removes duplicate URLs (for if two `core/image` blocks use the same image) via `@arrayUnique`:

```graphql
query GetImageBlockImageURLs($postID: ID!) {
  post(by: { id: $postID } ) {
    coreImage: blockFlattenedDataItems(
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
      "coreImage": [
        "https://d.pr/i/fW6V3V+",
        "https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-1024x622.jpg",
        "https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-suki-1024x598.png"
      ]
    }
  }
}
```
