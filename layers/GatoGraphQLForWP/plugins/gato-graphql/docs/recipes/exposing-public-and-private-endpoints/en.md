# Exposing Public and Private endpoints

GraphQL is traditionally about exposing a single endpoint, usually under `https://mysite.com/graphql`.

Gato GraphQL expands this notion, allowing us to expose multiple custom endpoints, each of them tailored to some specific need. For instance, we can expose endpoints:

- `/mobile-app` and `/website`
- `/internal` and `/public`
- `/clients` and `/visitors`
- `/development`, `/staging` and `/production`
- `/development-team`, `/testing-team` and `/marketing-team`
- `/client-A`, `/client-B` and `client-Z`
- any combination of them

In addition, Gato GraphQL natively supports Persisted Queries, which are endpoints where the query is predefined and stored in the server.

In this recipe there is no code, but suggestions on when to use each endpoint.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

All endpoints are configured via [Schema Configurations]()

You can also [create your own internal endpoint](https://gatographql.com/guides/config/creating-custom-internal-endpoints-for-blocks/), and pre-define whatever specific configuration required for your blocks (enabling nested mutations, enabling namespacing, defining what CPTs can be queried, or anything else available in the Schema Configuration).

Alternatively, you can create Persisted Queries and retrieve data from them (instead of from an endpoint). Check out how the [client code must be adapted](https://gatographql.com/guides/intro/connecting-to-the-graphql-server-from-a-client/#heading-executing-persisted-queries).

</div>

## When to use the single endpoint

The single GraphQL endpoint is always public, exposed by default under `/graphql`.

should be used when 

building headless sites

## When to use public custom endpoints


## When to use private private endpoints


## When to use Persisted Queries

