# Sending a notification when there is a new post

Gato GraphQL can help us automate tasks in the application, such as sending a notification email to the admin when there is a new post.

In this recipe we will explore a few examples.

## Reacting to WordPress hooks

Thanks to the **Internal GraphQL Server**, we can react to the resolution of a GraphQL query (whether executed against the internal GraphQL Server, single endpoint, custom endpoint or persisted query), and execute another GraphQL query against the internal GraphQL Server.

An example workflow is:

- Hook into the execution of a GraphQL query, for instance by its operation name (such as `CreatePost`)
- Send a notification to the admin, by executing mutation `_sendEmail` via `GatoGraphQL\InternalGraphQLServer\GraphQLServer::executeQuery`

This PHP code is chaining 2 GraphQL query executions:

```php
GraphQLServer::executeQuery(
  <<<GRAPHQL
    mutation CreatePost(
      \$postTitle: String!,
      \$postContent: String!
    ) {
      createPost(input: {
        title: \$postTitle
        contentAs: { html: \$postContent }
      }) {
        status
        errors {
          __typename
          ...on ErrorPayload {
            message
          }
        }
        postID
      }
    }
  GRAPHQL,
  [
    'postTitle' => 'New post',
    'postContent' => 'Some content',
  ],
  'CreatePost'
);

add_action(
  "gato_graphql__executed_query_CreatePost",
  function (Response $response) {
    /** @var string */
    $responseContent = $response->getContent();
    /** @var array<string,mixed> */
    $responseJSON = json_decode($responseContent, true);
    $postID = $responseJSON['data']['createPost']['postID'] ?? null;
    if ($postID === null) {
      // Do nothing
      return;
    }

    $post = get_post($postID);

    // Execute the chained query!
    GraphQLServer::executeQuery(
      <<<GRAPHQL
        mutation SendEmail(
          \$emailSubject: String!
          \$emailMessage: String!
        ) {
          _sendEmail(
            input: {
              to: "admin@site.com"
              subject: \$emailSubject
              messageAs: {
                html: \$emailMessage
              }
            }
          ) {
            status
          }
        }
      GRAPHQL,
      [
        'emailSubject' => sprintf(__("New post: %s"), $post->post_title),
        'emailMessage' => $post->post_content,
      ]
    );
  }
);
```
