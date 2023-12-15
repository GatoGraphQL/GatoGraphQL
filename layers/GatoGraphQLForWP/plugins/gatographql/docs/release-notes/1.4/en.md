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

## Improvements

- Renamed "Recipes" as "Tutorial"

## Fixed

- HTML codes were printed in select inputs in the WordPress editor, they have now been removed
