# Sending a notification when there is a new post

Gato GraphQL can help us automate tasks in the application, such as sending a notification email to the admin when there is a new post.

In this recipe we will explore a few examples.

## GraphQL query to send a notification email to the admin

This GraphQL query sends an email to the admin user, notifying of the creation of a new post on the site:

```graphql
query GetEmailData(
  $postTitle: String!,
  $postContent: String!
  $postURL: URL!
) {
  adminEmail: optionValue(name: "admin_email")
    @export(as: "adminEmail")

  emailMessageTemplate: _strConvertMarkdownToHTML(
    text: """

There is a [new post on the site]({$postURL}):

**{$postTitle}**:

{$postContent}

    """
  )
  emailMessage: _strReplaceMultiple(
    search: ["{$postTitle}", "{$postContent}"],
    replaceWith: [$postTitle, $postContent],
    in: $__emailMessageTemplate
  )
    @export(as: "emailMessage")

  emailSubject: _sprintf(
    string: "New post: \"%s\"",
    values: [$postTitle]
  )
    @export(as: "emailSubject")
}

mutation SendEmail @depends(on: "GetEmailData") {
  _sendEmail(
    input: {
      to: $adminEmail
      subject: $emailSubject
      messageAs: {
        html: $emailMessage
      }
    }
  ) {
    status
  }
}
```

Let's see next how to trigger the execution of the GraphQL query.

## Option 1: Reacting to WordPress hooks

We hook into the WordPress core action `wp_insert_post`, retrieve the data from the newly-created post, and execute the GraphQL query defined above against the internal GraphQL server (provided via the [**Internal GraphQL Server**](https://gatographql.com/extensions/internal-graphql-server/) extension):

```php
use GatoGraphQL\InternalGraphQLServer\GraphQLServer;

// GraphQL query, under var `$query`, is the one defined above
add_action(
  'wp_insert_post',
  function (int $postID, WP_Post $post) use ($query) {
    $variables = [
      'postTitle' => $post->post_title,
      'postContent' => $post->post_content,
      'postURL' => get_permalink($post->ID),
    ]
    GraphQLServer::executeQuery($query, $variables, 'SendEmail');
  },
  10,
  2
);
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Class `GatoGraphQL\InternalGraphQLServer\GraphQLServer` provides 3 static methods to execute queries:

- `executeQuery`: Execute a GraphQL query
- `executeQueryInFile`: Execute a GraphQL query contained in a (`.gql`) file
- `executePersistedQuery`: Execute a persisted GraphQL query (providing its ID as an int, or slug as a string)

</div>

## Option 2: Chaining GraphQL queries

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
