# Automatically adding a mandatory block

Whenever a new post is created, we can use the automation features to validate and modify the content of the post.

This tutorial lesson checks if a certain mandatory block is present in the post and, whenever missing, it adds it.

## GraphQL query to add a missing block

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

This GraphQL query checks if the mandatory block `wp:comments` has already been added to the post. If missing, it is added at the bottom of the content.

Save this content as a Persisted Query, with slug `insert-mandatory-comments-block-if-missing`:

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
  blockExists: _notEmpty(value: $__posts)
    @export(as: "blockExists")
}

mutation MaybeInsertCommentsBlock($postId: ID!)
  @depends(on: "CheckIfCommentsBlockExists")
  @skip(if: $blockExists)
{
  post(by: { id: $postId }) {
    id
    rawContent
    adaptedRawContent: _strAppend(
      after: $__rawContent
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
      contentAs: { html: $__adaptedRawContent },
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
        rawContent
      }
    }
  }
}
```

## Adding hook to execute Persisted Query

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

The [**Internal GraphQL Server**](https://gatographql.com/extensions/internal-graphql-server/) by default [applies the Schema Configuration defined in its own Settings](https://gatographql.com/guides/config/defining-the-schema-configuration-for-the-internal-graphql-server/).

Then, for this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the **Internal GraphQL Server** needs to have [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled.

</div>

This PHP code hooks into WordPress action `draft_post` to execute the Persisted Query (via the [**Internal GraphQL Server**](https://gatographql.com/extensions/internal-graphql-server/) extension):

```php
use GatoGraphQL\InternalGraphQLServer\GraphQLServer;
use WP_Post;

add_action(
  'draft_post',
  function (int $postID): void {
    GraphQLServer::executePersistedQuery(
      'insert-mandatory-comments-block-if-missing',
      [
        'postId' => $postID,
      ],
      'MaybeInsertCommentsBlock'
    );
  }
);
```
