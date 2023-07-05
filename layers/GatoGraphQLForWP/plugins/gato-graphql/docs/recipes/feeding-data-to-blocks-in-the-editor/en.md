# Feeding data to blocks in the editor

Content in the the WordPress editor is created via (Gutenberg) blocks, which fetch their data from the server via an API. WordPress core uses the WP REST API, but we can also use Gato GraphQL to power our own blocks.

Let's explore how the block can fetch data from the GraphQL server.

## Endpoint

Because blocks are used within the context of the WordPress editor, the user is already logged-in, and hence we can connect to an internal GraphQL endpoint (accessible within the wp-admin only) instead of a public endpoint.

This internal `blockEditor` endpoint is accessible under:

```
https://mysite.com/wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=blockEditor
```

This endpoint has a pre-defined configuration (i.e. it does not have the user preferences from the plugin applied to it), so its behavior is consistent.

Conveniently, we can also point to JavaScript global variable `GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT`, which contains the endpoint URL.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

If you need to apply some specific configuration for your blocks, such as:

- Using nested mutations or not
- Using namespacing or not
- Pre-defining CPTs that can be queried
- Any other configuration available in the Schema Configuration

...you can also create your own internal endpoint. Check out guide [Creating Custom Internal Endpoints for Blocks](https://gatographql.com/guides/config/creating-custom-internal-endpoints-for-blocks/) to learn how to do it.

</div>

## Connecting via `fetch`

We can use the standard [`fetch` method](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch) to connect to the server.

This JavaScript code submits a query with variables to the GraphQL server, and prints the response to console.

```js
(async function () {
  const limit = 3;
  const data = {
    query: `
query GetPostsWithAuthor($limit: Int) {
  posts(pagination: { limit: $limit }) {
    id
    title
    author {
      id
      name
    }
  }
}
    `,
    variables: {
      limit: `${ limit }`
    },
  };

  const response = await fetch(
    GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT,
    {
      method: 'post',
      body: JSON.stringify(data),
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'Content-Length': data.length,
      },
      credentials: 'include',
    }
  );

  /**
   * Execute the query, and await the response
   */
  const json = await response.json();

  /**
   * Check if the query produced errors, otherwise use the results
   */
  if (json.errors) {
    console.log(JSON.stringify(json.errors));
  } else {
    console.log(JSON.stringify(json.data));
  }
})();
```

You can use any client library to fetch

```js

```

submodules/PoP/layers/GatoGraphQLForWP/plugins/gato-graphql/blocks/schema-configuration/src/store/controls.js

`GATO_GRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT`

Document in some recipe:
    `GATO_GRAPHQL_ADMIN_ENDPOINT`
    `GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT`
Can use?:
    ## Added JS variable `GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT` with URL of internal block-editor endpoint

    An internal GraphQL endpoint called `blockEditor` is accessible within the wp-admin, to allow developers to fetch data for their Gutenberg blocks. This endpoint has a pre-defined configuration (i.e. it does not have the user preferences from the plugin applied to it), so its behavior is consistent.

    A new global JS variable `GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT` prints the URL for this endpoint in the wp-admin editor for all users who can access the GraphQL schema, making it easier to point to this endpoint within the block's JavaScript code.

    Inspecting the source code in the wp-admin, you will find the following HTML:

    ```html
    <script type="text/javascript">
    var GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT = "https://mysite.com/wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=blockEditor"
    </script>
    ```


Also:

How developers can "lock" behavior for a specific wp-admin endpoint

Use code in layers/GatoGraphQLForWP/phpunit-plugins/gato-graphql-testing/src/Hooks/AddDummyCustomAdminEndpointHook.php

