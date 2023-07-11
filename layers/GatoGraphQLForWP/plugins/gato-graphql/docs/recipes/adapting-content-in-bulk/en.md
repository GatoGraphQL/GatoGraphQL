# Adapting content in bulk

This recipe executes a search/replace of content in bulk, i.e. on multiple posts with a single GraphQL request.

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
    postInput: _objectProperty(
      object: $postInputs,
      by: { key: $__id }
    )
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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- In addition to function fields, the [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension also provides functionality via their corresponding "function directives", such as `@strReplaceMultiple`
- When [Multi-Field Directives](https://gatographql.com/guides/special-features/multifield-directives/) is enabled, we can apply a directive to more than one field, indicating the relative position(s) of the additional field(s) via argument `affectAdditionalFieldsUnderPos`
- When applying a directive to multiple fields, we [must use `@deferredExport` instead of `@export`](http://localhost:8080/guides/schema/executing-multiple-queries-concurrently/#heading-multi-field-directives)
- When applying a single `@export` or `@deferredExport` directive to multiple fields, the [exported value is a JSON object containing all the fields](http://localhost:8080/guides/schema/executing-multiple-queries-concurrently/#heading-dictionary-type-/-multi-field)

</div>
