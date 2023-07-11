# Adapting content in bulk

This recipe executes a search/replace of content in bulk, i.e. on multiple posts with a single GraphQL request.

## Using nested mutations

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have  [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

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
    id
    title
    excerpt
      @strReplaceMultiple(
        search: $replaceFrom
        replaceWith: $replaceTo
        affectAdditionalFieldsUnderPos: 1
      )
      @deferredExport(
        as: "postInputs"
        type: DICTIONARY
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
    postInput: _objectProperty(object: $postInputs, by: { key: $__id })
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
