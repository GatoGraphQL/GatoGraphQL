# Release Notes: 2.1

## Added

### Support passing the Schema Configuration to apply when invoking the Internal GraphQL Server

Gato GraphQL now supports indicating what Schema Configuration to apply when executing a query via an internal GraphQL Server (i.e. directly within the PHP application, not via an endpoint).

The [Internal GraphQL Server extension](https://gatographql.com/extensions/internal-graphql-server/) makes use of this feature. It now accepts a `$schemaConfigurationID` parameter on `GraphQLServer::executeQuery` and `GraphQLServer::executeQueryInFile`, and already provides the persisted query's schema configuration on `GraphQLServer::executePersistedQuery`:

```diff
class GraphQLServer {
  
  public static function executeQuery(
    string $query,
    array $variables = [],
    ?string $operationName = null,
+   // Accept parameter 
+   int|string|null $schemaConfigurationIDOrSlug = null,
  ): Response {
    // ...
  }

  public static function executeQueryInFile(
    string $file,
    array $variables = [],
    ?string $operationName = null,
+   // Accept parameter 
+   int|string|null $schemaConfigurationIDOrSlug = null,
  ): Response {
    // ...
  }

+ // Schema Configuration is taken from the Persisted Query
  public static function executePersistedQuery(
    string|int $persistedQueryIDOrSlug,
    array $variables = [],
    ?string $operationName = null,
  ): Response {
    // ...
  }
```
