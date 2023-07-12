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

## Option 1: Trigger always by reacting to WordPress hooks

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

This GraphQL query will be executed whenever a new post is created or, to be more precise, whenever PHP function `wp_insert_post` is invoked in the application (as this function triggers hook `wp_insert_post`):

```php
$postID = wp_insert_post([
  'post_title' => 'Hello world!'
]);
```

This is also the case when executing another GraphQL query (for instance, against the single endpoint) that executes the `createPost` mutation, as its resolver (in PHP code) invokes the function `wp_insert_post`:

```graphql
mutation CreatePost {
  createPost(input: {
    title: "Hello world!"
  }) {
    status
    postID
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

If we are executing a GraphQL query against a public endpoint (such as the single endpoint), and it creates a post by executing mutation `createPost`, then the GraphQL servers perform the following sequence of steps:

| GraphQL Server | Internal GraphQL Server |
| --- | --- |
| Execute GraphQL query against single endpoint (using its own Schema Configuration) | |
| Create post, trigger `wp_insert_post` | |
| | React to hook, spin the internal GraphQL server (using its own Schema Configuration) to execute the query to send an email |
| | Send email, end of that query |
| | End execution of server |
| Continue execution of query, end of that query |
| End execution of server | |

</div>

## Option 2: Trigger by chaining GraphQL queries

The [**Automation**](http://localhost:8080/extensions/automation/) extension makes the GraphQL Server trigger a hook when completing the execution of a GraphQL query. This allows us to chain GraphQL queries.

This PHP code executes the `SendEmail` operation (from the same GraphQL query from above), after the GraphQL server has executed some other query with operation `CreatePost` (from the same GraphQL query from above):

```php
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

    $variables = [
      'postTitle' => $post->post_title,
      'postContent' => $post->post_content,
      'postURL' => get_permalink($post->ID),
    ]
    GraphQLServer::executeQuery($query, $variables, 'SendEmail');
  }
);
```
