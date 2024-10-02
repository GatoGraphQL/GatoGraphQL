# Lesson 16: Sending a notification when there is a new post

Gato GraphQL can help us automate tasks in the application, such as sending a notification email to the admin when there is a new post.

In this tutorial lesson we will explore two ways to achieve this.

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
    search: ["{$postTitle}", "{$postContent}", "{$postURL}"],
    replaceWith: [$postTitle, $postContent, $postURL],
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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

To send the email in plain text:

- Use input `messageAs: { text: ... }` in the `_sendEmail` mutation
- Remove the HTML tags from the post's content using global field `_htmlStripTags` (provided by the [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension)

</div>

<!-- {/* Ignore Automation guides */} -->
<!-- Let's see next how to trigger the execution of the GraphQL query.

## Option 1: Trigger always by reacting to WordPress hooks

We hook into the WordPress core action `new_to_publish`, retrieve the data from the newly-created post, and execute the GraphQL query defined above against the internal GraphQL server (provided via the [**Internal GraphQL Server**](https://gatographql.com/extensions/internal-graphql-server/) extension):

```php
use GatoGraphQL\InternalGraphQLServer\GraphQLServer;
use WP_Post;

// The GraphQL query, under var `$query`, is the one defined above
// $query = '...';
add_action(
  'new_to_publish',
  function (WP_Post $post) use ($query) {
    if ($post->post_type !== 'post') {
      return;
    }
    $variables = [
      'postTitle' => $post->post_title,
      'postContent' => $post->post_content,
      'postURL' => get_permalink($post->ID),
    ];
    GraphQLServer::executeQuery($query, $variables, 'SendEmail');
  }
);
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Class `GatoGraphQL\InternalGraphQLServer\GraphQLServer` is not accessible as an external API. Instead, it is to be used by the application via PHP code, for executing/automating admin tasks via GraphQL queries.

This class provides 3 static methods to execute queries:

- `executeQuery`: Execute a GraphQL query
- `executeQueryInFile`: Execute a GraphQL query contained in a (`.gql`) file
- `executePersistedQuery`: Execute a persisted GraphQL query (providing its ID as an int, or slug as a string)

</div>

This GraphQL query will be executed whenever a new post is created or, to be more precise, whenever WordPress function `wp_insert_post` is invoked (as this function triggers hook `new_to_publish`):

```php
$postID = wp_insert_post([
  'post_title' => 'Hello world!'
]);
```

This is also the case when executing another GraphQL query that executes the `createPost` mutation (as its resolver, in PHP code, invokes function `wp_insert_post`):

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

The GraphQL Server (which is "external", accessed as an API via HTTP) and the Internal GraphQL Server will execute their queries applying their own Schema Configuration, even when their execution is intertwined.

For instance, let' say we are executing a GraphQL query against the single endpoint, and it creates a post by executing mutation `createPost`. Then the following sequence of steps takes place:

| **(External) GraphQL Server** | **Internal GraphQL Server** |
| --- | --- |
| Execute GraphQL query against the single endpoint, using its own Schema Configuration | _(not active)_ |
| Create a post; this triggers `new_to_publish` | _(not active)_ |
| _(waiting...)_ | React to `new_to_publish` hook: Spin the Internal GraphQL server, using its own Schema Configuration |
| _(waiting...)_ | Execute the query to send an email |
| _(waiting...)_ | Send email, end of that query |
| _(waiting...)_ | Shutdown server |
| Continue execution of query, end of that query | _(not active)_ |
| Shutdown server | _(not active)_ |

<br/>

</div>

## Option 2: Trigger by chaining GraphQL queries

The [**Automation**](https://gatographql.com/extensions/automation/) extension makes the GraphQL Server trigger a hook after completing the execution of a GraphQL query. This allows us to chain GraphQL queries.

This PHP code executes the `SendEmail` operation (GraphQL query defined above), after the GraphQL server has executed some other query with operation `CreatePost` (GraphQL query defined above):

```php
// The GraphQL query, under var `$query`, is the one defined above
// $query = '...';
add_action(
  "gatographql__executed_query:CreatePost",
  function (Response $response) use ($query) {
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
    ];
    GraphQLServer::executeQuery($query, $variables, 'SendEmail');
  }
);
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Chaining GraphQL queries allows us to execute a single query only, even when many resources were mutated.

For instance, this GraphQL query updates many posts:

```graphql
mutation ReplaceDomains {
  posts {
    id
    rawContent
    adaptedRawContent: _strReplace(
      search: "https://my-old-domain.com"
      replaceWith: "https://my-new-domain.com"
      in: $__rawContent
    )
    update(input: {
      contentAs: { html: $__adaptedRawContent }
    }) {
      status
      postID
    }
  }
}
```

Depending on our strategy, we can trigger the execution of one or multiple additional GraphQL queries:

| **Hooking into...** | **Triggers number of GraphQL queries...** |
| --- | --- |
| `post_updated` (by WordPress core) | One for every updated post |
| `gatographql__executed_query:ReplaceDomains` (by [**Automation**](https://gatographql.com/extensions/automation/) extension) | One in total (it will receive the data for all updated posts) |

<br/>

</div> -->
