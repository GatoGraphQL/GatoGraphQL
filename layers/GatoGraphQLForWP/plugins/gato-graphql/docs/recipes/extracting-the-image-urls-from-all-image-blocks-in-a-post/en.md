# Extracting the image URLs from all Image blocks in a post

We can iterate the inner structure of (Gutenberg) blocks in the post content, and extract those desired items.

This recipe demonstrates how to extract the image URLs from all `core/image` blocks in a post.

## GraphQL query to extract the image URLs from all `core/image` blocks in a post

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
  }
}
```
