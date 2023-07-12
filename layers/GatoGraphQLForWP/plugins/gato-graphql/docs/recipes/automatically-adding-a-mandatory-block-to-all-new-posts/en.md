# Automatically adding a mandatory block to all new posts

Whenever a new post is created, we can use the automation features to modify the content of the post, if required.

This recipe checks if a certain mandatory block is present in the post and, if it is not, it adds it.

## GraphQL query to add a block if missing

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have  [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

This GraphQL query checks if the mandatory block `wp:comments` has already been added to the post. If missing, it is added at the bottom of the content.

```graphql
query CheckIfCommentsBlockExists($postId: ID!) {
  posts(
    filter: {
      ids: [$postId]
      search: "\"<!-- /wp:comments -->\""
    }
  ) {
    id
  }
  _notEmpty(value: $__posts)
    @export(as: "blockExists")
}

mutation MaybeInsertCommentsBlock($postId: ID!)
  @depends(on: "CheckIfCommentsBlockExists")
  @skip(if: $blockExists)
{
  post(by: { id: $postId }) {
    id
    contentSource
    adaptedContentSource: _strAppend(
      after: $__contentSource
      append: """

<!-- wp:comments -->
<div class="wp-block-comments"><!-- wp:comments-title /-->

<!-- wp:comment-template -->
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"40px"} -->
<div class="wp-block-column" style="flex-basis:40px"><!-- wp:avatar {"size":40,"style":{"border":{"radius":"20px"}}} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:comment-author-name {"fontSize":"small"} /-->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"flex"}} -->
<div class="wp-block-group" style="margin-top:0px;margin-bottom:0px"><!-- wp:comment-date {"fontSize":"small"} /-->

<!-- wp:comment-edit-link {"fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:comment-content /-->

<!-- wp:comment-reply-link {"fontSize":"small"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:comment-template -->

<!-- wp:comments-pagination -->
<!-- wp:comments-pagination-previous /-->

<!-- wp:comments-pagination-numbers /-->

<!-- wp:comments-pagination-next /-->
<!-- /wp:comments-pagination -->

<!-- wp:post-comments-form /--></div>
<!-- /wp:comments -->   

      """
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
