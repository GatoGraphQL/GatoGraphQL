# Adapting content in bulk

...

Also nested mutations!



nested/3-transform-post-properties.gql

```graphql
query ExportAndTransformData(
  $replaceFrom: String!
  $replaceTo: String!
) {
  # Exclude ID 28 because its blocks render the domain, so it doesn't work for "PROD Integration Tests"
  posts: posts(pagination: { limit: 3 }, sort: { by: ID, order: ASC }, filter: { excludeIDs: 28 }) {
    id @export(as: "postIDs")
    title @strReverse
    excerpt
      @strReplace(
        search: $replaceFrom
        replaceWith: $replaceTo
      )
      @deferredExport(
        as: "postProps"
        affectAdditionalFieldsUnderPos: 1
      )
  }
}
mutation TransformPostData @depends(on: "ExportAndTransformData") {
  adaptedPosts: posts(pagination: { limit: 3 }, sort: { by: ID, order: ASC }, filter: { excludeIDs: 28 }) {
    id
    positionInArray: _arraySearch(array: $postIDs, element: $__id)
    postData: _arrayItem(array: $postProps, position: $__positionInArray)
    update(input: $__postData) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        title
        contentSource
      }
    }
  }
}
```

var

```json
{
  "replaceFrom": " ",
  "replaceTo": "|||"
}
```
