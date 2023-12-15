# Feeding data to blocks in the editor

Content in the the WordPress editor is created via (Gutenberg) blocks, which fetch their data from the server via an API. WordPress core uses the WP REST API, but we can also use Gato GraphQL to power our own blocks.

Let's explore how the block can fetch data from the GraphQL server.

## Endpoint

Because blocks are used within the context of the WordPress editor, the user is already logged-in, and hence we can connect to an internal GraphQL endpoint (accessible within the wp-admin only) instead of a public endpoint.

This internal `blockEditor` endpoint is accessible under:

```apacheconf
https://mysite.com/wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=blockEditor
```

This endpoint has a pre-defined configuration (i.e. it does not have the user preferences from the plugin applied to it), so its behavior is consistent.

Conveniently, we can also point to JavaScript global variable `GATOGRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT`, which contains the endpoint URL.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

You can also [create your own internal endpoint](https://gatographql.com/guides/config/creating-custom-internal-endpoints-for-blocks/), and pre-define whatever specific configuration required for your blocks (enabling nested mutations, enabling namespacing, defining what CPTs can be queried, or anything else available in the Schema Configuration).

Alternatively, you can create Persisted Queries and retrieve data from them (instead of from an endpoint). Check out how the [client code must be adapted](https://gatographql.com/guides/intro/connecting-to-the-graphql-server-from-a-client/#heading-executing-persisted-queries).

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
    GATOGRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT,
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

### Sending the REST nonce header

If you need to execute an operation including REST nonce, add the `X-WP-Nonce` header.

Print a JS variable containing the nonce, via PHP code:

```php
// Generate HTML in the editor:
// <script type="text/javascript">var WP_REST_NONCE = "{ Nonce value }"</script>
add_action(
  'admin_print_scripts',
  function(): void {
    printf(
      '<script type="text/javascript">var %s = "%s"</script>',
      'WP_REST_NONCE',
      wp_create_nonce('wp_rest')
    );
  }
);
```

Then include the nonce value in the headers to `fetch`:

```js
// ...
  headers: {
    'X-WP-Nonce': `${ WP_REST_NONCE }`,
    // ...
  };
```

## Connecting via a GraphQL client library

You can also use the GraphQL client library of your choice to connect to the server. Some options are:

- [GraphQL Request](https://github.com/jasonkuhrt/graphql-request)
- [urql](https://github.com/urql-graphql/urql)
- [Apollo client](https://github.com/apollographql/apollo-client)

Here is an [example using GraphQL request](https://github.com/jasonkuhrt/graphql-request/blob/6b3396bbd4c3b678f84abe8bcf697a26e563721c/examples/other-package-commonjs.ts):

```js
/* eslint-disable */

const { request, gql } = require(`graphql-request`)

main()

async function main() {
  const query = gql`
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

  const data = await request(GATOGRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT, query)
  console.log(data)
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

The Gato GraphQL plugin itself powers its blocks via GraphQL, using the `graphql-request` library.

Check out the source code for the ["Schema Configuration" block](https://github.com/GatoGraphQL/GatoGraphQL/tree/1.0.11/layers/GatoGraphQLForWP/plugins/gatographql/blocks/schema-configuration/) and its [data store](https://github.com/GatoGraphQL/GatoGraphQL/tree/1.0.11/layers/GatoGraphQLForWP/plugins/gatographql/blocks/schema-configuration/src/store).

</div>
