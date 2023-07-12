# Automatically adding a mandatory block to all new posts

Whenever a new post is created, we can use the automation features to modify the content of the post, if required.

This recipe checks if a certain mandatory block is present in the post. If it is not, it is then added at the bottom of the content.

## GraphQL query to add a block if missing

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have  [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

This GraphQL query checks if the mandatory block `mycompany:black-friday-campaign-video` has already been added to the post. If missing, it is added at the bottom of the content.

```graphql
query CheckIfBlockExists($postId: ID!) {
  posts(
    filter: {
      ids: [$postId]
      search: "\"<!-- /mycompany:black-friday-campaign-video -->\""
    }
  ) {
    id
  }
  _notEmpty(value: $__posts)
    @export(as: "blockExists")
}

mutation MaybeInsertBlock($postId: ID!)
  @depends(on: "CheckIfBlockExists")
  @skip(if: $blockExists)
{
  post(by: { id: $postId }) {
    id
    contentSource
    adaptedContentSource: _strAppend(
      after: $__contentSource
      append: "<!-- mycompany:black-friday-campaign-video -->\n<figure class=\"wp-block-video\"><video controls src=\"https://mysite.com/videos/BlackFriday2023.mp4\"></video></figure>\n<!-- /mycompany:black-friday-campaign-video -->"
    )
    update(input: {
      contentAs: { html: $__adaptedContentSource },
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        id
        contentSource
      }
    }
  }
}
```
