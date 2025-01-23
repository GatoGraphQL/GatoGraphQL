# Internal GraphQL Server

This extension installs an internal GraphQL Server, that can be invoked within your application, using PHP code.

Among other use cases, you can trigger the execution of a GraphQL query whenever some action happens, to perform some related task (such as sending a notification, adding a log entry, validating a condition, etc).

## Description

The internal GraphQL server is accessed via class `GatoGraphQL\InternalGraphQLServer\GraphQLServer`, through these three methods:

- `executeQuery`: Execute a GraphQL query
- `executeQueryInFile`: Execute a GraphQL query contained in a (`.gql`) file
- `executePersistedQuery`: Execute a persisted GraphQL query (providing its ID as an int, or slug as a string) (the **Persisted Queries** extension is required)

These are the method signatures:

```php
namespace GatoGraphQL\InternalGraphQLServer;

use PoP\Root\HttpFoundation\Response;

class GraphQLServer {
  /**
   * Execute a GraphQL query
   */
  public static function executeQuery(
    string $query,
    array $variables = [],
    ?string $operationName = null,
    int|string|null $schemaConfigurationIDOrSlug = null,
  ): Response {
    // ...
  }


  /**
   * Execute a GraphQL query contained in a (`.gql`) file
   */
  public static function executeQueryInFile(
    string $file,
    array $variables = [],
    ?string $operationName = null,
    int|string|null $schemaConfigurationIDOrSlug = null,
  ): Response {
    // ...
  }


  /**
   * Execute a persisted GraphQL query (providing its object
   * of type WP_Post, ID as an int, or slug as a string)
   */
  public static function executePersistedQuery(
    WP_Post|string|int $persistedQuery,
    array $variables = [],
    ?string $operationName = null
  ): Response {
    // ...
  }
}
```

To execute a GraphQL query and obtain the response content:

```php
use GatoGraphQL\InternalGraphQLServer\GraphQLServer;

// Provide the GraphQL query
$query = "{ ... }";

// Execute the query against the internal server
$response = GraphQLServer::executeQuery($query);

// Get the content and decode it
$responseContent = json_decode($response->getContent(), true);

// Access the data and errors from the response
$responseData = $responseContent["data"] ?? [];
$responseErrors = $responseContent["errors"] ?? [];
```

The Response object also contains any produced header (eg: if some Cache Control List was applied, it would add the `Cache-Control` header):

```php
$responseHeaders = $response->getHeaders();
$responseCacheControlHeader = $response->getHeaderLine('Cache-Control');
```

Please notice that class `GraphQLServer` is not ready before the WordPress core `init` hook.

<!-- ## Schema Configuration

By default, the internal GraphQL Server applies the Schema Configuration selected in the Settings page, under tab "Server Configuration > Internal GraphQL Server".

<div class="img-width-1024" markdown=1>

![Configuring the Internal GraphQL Server in the Settings](../../images/settings-schema-configuration-for-internal-graphql-server.webp "Configuring the Internal GraphQL Server in the Settings")

</div>

This configuration also applies whenever the query executed against the internal GraphQL server was triggered by some other GraphQL query while being resolved in an endpoint with a different configuration (such as the public endpoint `graphql/`).

For instance, let's say that we have configured the single endpoint `graphql/` to apply an Access Control List to validate users by IP, and we execute mutation `createPost` against this endpoint:

```graphql
mutation {
  createPost(input: {...}) {
    # ...
  }
}
```

As such, only visitors from that IP will be able to execute this mutation.

Then there is a hook on `new_to_publish` that executes some query against the internal GraphQL server (eg: to send a notification to the site admin):

```php
add_action(
  "new_to_publish",
  fn (int $post_id) => GraphQLServer::executeQuery("...", ["postID" => $post_id])
);
```

This GraphQL query will be resolved using the schema configuration applied to the internal GraphQL server, and not to the single endpoint `graphql/`.

As a result, the validation by user IP will not take place (that is, unless that Access Control List was also applied to the internal GraphQL server). -->

## Example

In this example workflow (which also uses **Multiple Query Execution**, **Helper Function Collection** and **Field to Input** modules), when a new post is created in the site, we send a notification to the admin user.

We hook into the WordPress core action `new_to_publish`, retrieve the data from the newly-created post, and call `GraphQLServer::executeQuery`:

```php
add_action(
  'new_to_publish',
  function (WP_Post $post) {
    if ($post->post_type !== 'post') {
      return;
    }
    // Check the contents of the query below
    $query = ' ... ';
    $variables = [
      'postTitle' => $post->post_title,
      'postContent' => $post->post_content,
    ];
    GraphQLServer::executeQuery($query, $variables, 'SendEmail');
  }
);
```

...with this GraphQL query:

```graphql
query GetEmailData(
  $postTitle: String!,
  $postContent: String!
) {
  emailMessageTemplate: _strConvertMarkdownToHTML(
    text: """

There is a new post on the site: 

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
}

mutation SendEmail @depends(on: "GetEmailData") {
  _sendEmail(
    input: {
      to: "admin@site.com"
      subject: "There is a new post"
      messageAs: {
        html: $emailMessage
      }
    }
  ) {
    status
  }
}
```
