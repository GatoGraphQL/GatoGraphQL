# Release Notes: 1.4

Here's a description of all the changes.

## Added predefined custom endpoint "Nested mutations + Entity as mutation payload type"

The new predefined custom endpoint "Nested mutations + Entity as mutation payload type", installed as `private`, is useful for executing queries that create resources in bulk.

For instance, the "Import posts from CSV" persisted query would need to be run on that client.

## Added "Request headers" to GraphiQL clients on single public/private endpoint, and custom endpoints

The GraphiQL client on the single public and private GraphQL endpoints now have the "Request headers" input:

<div class="img-width-1024" markdown=1>

![Single private endpoint GraphiQL client with 'Request headers' input](../../images/releases/v1.4/private-single-endpoint-graphiql-with-request-headers.png "Single private endpoint GraphiQL client with 'Request headers' input")

</div>

Same for custom endpoints:

<div class="img-width-1024" markdown=1>

![Custom endpoint GraphiQL client with 'Request headers' input](../../images/releases/v1.4/custom-endpoint-graphiql-with-request-headers.png "Custom endpoint GraphiQL client with 'Request headers' input")

</div>

(GraphiQL clients on Persisted queries do not have this addition.)

## Renamed page "Recipes" to "Tutorial", and added settings to hide it

The page "Recipes" has been renamed to "Tutorial", to better represent its nature. (At the same time, the new ["Queries Library" section at gatographql.com](https://gatographql.com/library/) demonstrates GraphQL queries to solve common use cases.)

An option to hide the Tutorial section has been added to the Settings page, under "Plugin Configuration > General > Hide Tutorial page?":

<div class="img-width-1024" markdown=1>

![Hiding the Tutorial page in the Settings](../../images/settings-hide-tutorial-page.png)

</div>

Alternatively, we can define this value in `wp-config.php`:

```php
define( 'GATOGRAPHQL_HIDE_TUTORIAL_PAGE', true );
```

## Improvements

- Renamed existing bundles:
  - “Application Glue & Automator” Bundle => “Query Functions” Bundle
  - “Content Translation” Bundle => “Polylang Integration” Bundle
  - “Public API” Bundle => “Multiple Query Execution” Bundle
- Added documentation for new bundles:
  - “Automated Content Translation & Sync for WordPress Multisite” Bundle
  - “Caching” Bundle
  - “Custom Endpoints” Bundle
  - “Deprecation” Bundle
  - “Persisted Queries” Bundle
  - “Unhindered WordPress Email Notifications” Bundle
  - “Versatile WordPress Request API” Bundle

## Fixed

- HTML codes were printed in select inputs in the WordPress editor, they have now been removed
