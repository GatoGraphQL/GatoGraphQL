# Internal GraphQL Server

Execute GraphQL queries directly within your application, using PHP code

---

This extension installs an internal GraphQL Server, that can be invoked within your application, using PHP code.

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
