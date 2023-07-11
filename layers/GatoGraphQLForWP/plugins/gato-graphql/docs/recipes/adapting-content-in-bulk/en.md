# Adapting content in bulk

This recipe executes a search/replace of content in bulk, i.e. on multiple posts with a single GraphQL request.

## Using nested mutations


nested/3-transform-post-properties.gql

```graphql
query TransformAndExportData(
  $limit: Int! = 5,
  $offset: Int! = 0,
  $replaceFrom: String!
  $replaceTo: String!
) {
  posts: posts(
    pagination: { limit: $limit, offset: $offset }
    sort: { by: ID, order: ASC }
  ) {
    id @export(as: "postIDs")
    title
    excerpt
      @strReplaceMultiple(
        search: $replaceFrom
        replaceWith: $replaceTo
        affectAdditionalFieldsUnderPos: 1
      )
      @deferredExport(
        as: "postInputs"
        affectAdditionalFieldsUnderPos: 1
      )
  }
}

mutation UpdatePost(
  $limit: Int! = 5,
  $offset: Int! = 0
)
  @depends(on: "TransformAndExportData")
{
  adaptedPosts: posts(
    pagination: { limit: $limit, offset: $offset }
    sort: { by: ID, order: ASC }
  ) {
    id
    positionInArray: _arraySearch(array: $postIDs, element: $__id)
    postInput: _arrayItem(array: $postInputs, position: $__positionInArray)
    update(input: $__postInput) {
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

The dictionary of `variables` receives the list of strings to search and replace, and how many posts to affect:

```json
{
  "limit": 10,
  "offset": 0,
  "replaceFrom": ["Old string 2", "Old string 2"],
  "replaceTo": ["New string1", "New string 2"]
}
```


## Using multiple 
