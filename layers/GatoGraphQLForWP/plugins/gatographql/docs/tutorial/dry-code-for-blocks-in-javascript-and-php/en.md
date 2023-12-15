# DRY code for blocks in Javascript and PHP

[Dynamic (Gutenberg) blocks](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/) are blocks that build their structure and content on the fly when the block is rendered on the front end.

Rendering a dynamic block in the front-end (to display it in the WordPress editor) and in the server-side (to generate the HTML for the blog post) will typically fetch its data in two different ways:

- Connecting to the API on the client-side (JavaScript)
- Calling WordPress functions on the server-side (PHP)

With Gato GraphQL and extensions, it is possible to make this logic DRY, having a single source of truth to fetch data for both the client and server-sides. Let's explore how to do this.

## Storing GraphQL queries in `.gql` files

In the previous tutorial lesson (which explained how to connect to the GraphQL server from the client), the GraphQL query to be executed was embedded within the JavaScript code:

```js
const response = await fetch(endpoint, {
  body: JSON.stringify( {
    query: `
      query {
        posts {
          id
          title
          author {
            id
            name
          }
        }
      }
    `
  } )
} );
```

However, we can also store the GraphQL query in a `.gql` (or `.graphql`) file, and import its contents using Webpack's [`raw-loader`](https://v4.webpack.js.org/loaders/raw-loader/):

```js
// File webpack.config.js
const config = require( '@wordpress/scripts/config/webpack.config' );

config.module.rules.push(
  {
    test: /\.(gql|graphql)$/i,
    use: 'raw-loader',
  },
);

module.exports = config;
```

_(This code works for Webpack v4; for v5, we must use [Asset Modules](https://webpack.js.org/guides/asset-modules/) instead.)_

Next, we place the GraphQL query inside a `.gql` file:

```gql
# File graphql-documents/fetch-posts-with-author.gql
query {
  posts {
    id
    title
    author {
      id
      name
    }
  }
}
```

Finally, within the block's code, we import the file and pass its contents to `fetch`:

```js
import graphQLQuery from './graphql-documents/fetch-posts-with-author.gql';

// ...

const response = await fetch(endpoint, {
  body: JSON.stringify( {
    query: graphQLQuery
  } )
} );
```

## Resolving `.gql` files in the server-side

The GraphQL file we created above will be our single source of truth to fetch data for the block. It already satisfies this for the client-side; let's now see do it for the server-side.

The [**Internal GraphQL Server**](https://gatographql.com/extensions/internal-graphql-server/) extension installs a server that can be invoked within our application, using PHP code. It offers method `executeQueryInFile`:

```php
namespace GatoGraphQL\InternalGraphQLServer;

class GraphQLServer {
  /**
   * Execute a GraphQL query contained in a (`.gql`) file
   */
  public static function executeQueryInFile(
      string $file,
      array $variables = [],
      ?string $operationName = null
  ): Response {
    // ...
  }
}
```

By invoking this method passing the `.gql` file created earlier on, we retrieve the data when rendering the dynamic block:

```php
$block = [
  'render_callback' => function(array $attributes, string $content): string {
    // Provide the GraphQL query file
    $file = __DIR__ . '/blocks/my-block/graphql-documents/fetch-posts-with-author.gql';

    // Execute the query against the internal server
    $response = \GatoGraphQL\InternalGraphQLServer\GraphQLServer::executeQueryInFile($file);

    // Get the content and decode it
    $responseContent = json_decode($response->getContent(), true);

    // Access the data and errors from the response
    $data = $responseContent["data"] ?? [];
    $errors = $responseContent["errors"] ?? [];

    // Do something with the data
    // $content = $this->useGraphQLData($content, $data, $errors);
    // ...

    return $content;
  },
];
register_block_type("namespace/my-block", $block);
```

That's it. Now, a single `.gql` file retrieves the data to power blocks on both the client and server-sides.
