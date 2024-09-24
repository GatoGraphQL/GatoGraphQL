# Lesson 7: Adapting content in bulk

This tutorial lesson adapts content in bulk, updating the title and excerpt for multiple posts with a single GraphQL request.

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

The GraphQL query below retrieves the data for the multiple posts, executes a search and replace on the `title`, `content` and `excerpt` fields for each of them, adapts these as inputs to the mutation, and exports a single dynamic variable `$postInputs` with all the results as a dictionary, with format:

```json
{
  "${post ID}": {
    "title": "${adapted post title}",
    "excerpt": "${adapted post excerpt}"
  },
  // repeat for all other posts ...
}
```

In the `mutation` operation, each of these entries is then retrieved via `_objectProperty` (using `${post ID}` as the key) and passed as the `input` to update the post:

```graphql
query TransformAndExportData(
  $limit: Int! = 5,
  $offset: Int! = 0,
  $replaceFrom: [String!]!
  $replaceTo: [String!]!
) {
  posts: posts(
    pagination: { limit: $limit, offset: $offset }
    sort: { by: ID, order: ASC }
  ) {
    rawTitle
    rawContent
    rawExcerpt
      @strReplaceMultiple(
        search: $replaceFrom
        replaceWith: $replaceTo
        affectAdditionalFieldsUnderPos: [1, 2]
      )
      @deferredExport(
        as: "postAdaptedSources"
        type: DICTIONARY
        affectAdditionalFieldsUnderPos: [1, 2]
      )
  }
}

query AdaptDataForMutationInput
  @depends(on: "TransformAndExportData")
{
  postInputs: _echo(value: $postAdaptedSources)
    @underEachJSONObjectProperty(
      passValueOnwardsAs: "adaptedSource",
      affectDirectivesUnderPos: [1, 2, 3, 4]
    )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $adaptedSource,
          by: {
            key: "rawTitle"
          }
        },
        passOnwardsAs: "adaptedTitle"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $adaptedSource,
          by: {
            key: "rawExcerpt"
          }
        },
        passOnwardsAs: "adaptedExcerpt"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $adaptedSource,
          by: {
            key: "rawContent"
          }
        },
        passOnwardsAs: "adaptedContent"
      )
      @applyField(
        name: "_echo",
        arguments: {
          value: {
            title: $adaptedTitle,
            excerpt: $adaptedExcerpt,
            contentAs: {
              html: $adaptedContent
            }
          }
        },
        setResultInResponse: true
      )
    @export(as: "postInputs")
}

mutation UpdatePost(
  $limit: Int! = 5,
  $offset: Int! = 0
)
  @depends(on: "AdaptDataForMutationInput")
{
  adaptedPosts: posts(
    pagination: { limit: $limit, offset: $offset }
    sort: { by: ID, order: ASC }
  ) {
    id
    postInput: _objectProperty(
      object: $postInputs,
      by: { key: $__id }
    ) @remove
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
        excerpt
      }
    }
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- The **[Field on Field](https://gatographql.com/extensions/query-functions)** extension provides directive `@applyField` which, invoked with `_objectProperty`, extracts the properties from each item in the JSON object (passed as `$adaptedSource`), and then with `_echo`, it creates the corresponding JSON input with those properties
- In addition to function fields, the [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension also provides functionality via their corresponding "function directives", such as `@strReplaceMultiple`
- When [Multi-Field Directives](https://gatographql.com/guides/special-features/multifield-directives/) is enabled, we can apply a directive to more than one field, indicating the relative position(s) of the additional field(s) via argument `affectAdditionalFieldsUnderPos`
- When applying a directive to some field and then exporting its value, we [must use `@deferredExport` instead of `@export`](https://gatographql.com/guides/schema/executing-multiple-queries-concurrently/#heading-multi-field-directives)
- When using Multi-Field Directives together with `@export`( or `@deferredExport`), the [exported value is a JSON object containing all the fields](https://gatographql.com/guides/schema/executing-multiple-queries-concurrently/#heading-dictionary-type-/-multi-field)
- Mutation `Post.update` is available in the schema only when the Nested Mutations feature is enabled

</div>
