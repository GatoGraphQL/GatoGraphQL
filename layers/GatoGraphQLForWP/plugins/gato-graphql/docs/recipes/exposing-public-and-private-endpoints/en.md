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

Gato GraphQL also natively supports Persisted Queries, which are endpoints where the GraphQL query is predefined and stored in the server.

In this recipe there is no code, but suggestions on how and when to use each endpoint.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Endpoints are configured via a [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/), where we define:

- Setting the schema as public or private
- Enabling “sensitive” data elements
- Namespacing the schema
- Using nested mutations
- Defining response headers
- Granting access to the schema elements via Access Control Lists
- Setting-up HTTP caching
- Many others

We can also define a [default Schema Configuration](https://gatographql.com/guides/config/defining-the-default-schema-configuration/).

</div>

## When to use the single endpoint

The [single GraphQL endpoint](https://gatographql.com/guides/config/enabling-and-configuring-the-single-endpoint/) is always public, exposed by default under `/graphql`. It is disabled my default, so the "Single Endpoint" module must be enabled.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Gato GraphQL is managed via "modules", each of them offering some functionality or extension of the GraphQL schema.

[Modules can be enabled and disabled](https://gatographql.com/guides/config/browsing-enabling-and-disabling-modules/) as needed. It is a good practice to disable modules that extend the GraphQL schema (such as modules "Posts", "Users", "Comments", "Blocks", etc) when they are not needed, as to make sure that that data will never be exposed in first place.

In particular, we can disable module "Mutations", thus deactivating all extensions that provide some mutation (such as modules "Post Mutations", "Comment Mutations", etc).

</div>



building headless sites

## When to use public custom endpoints


## When to use private private endpoints

You can also [create your own internal endpoint](https://gatographql.com/guides/config/creating-custom-internal-endpoints-for-blocks/), and pre-define whatever specific configuration required for your blocks (enabling nested mutations, enabling namespacing, defining what CPTs can be queried, or anything else available in the Schema Configuration).

## When to use Persisted Queries

Alternatively, you can create Persisted Queries and retrieve data from them (instead of from an endpoint). Check out how the [client code must be adapted](https://gatographql.com/guides/intro/connecting-to-the-graphql-server-from-a-client/#heading-executing-persisted-queries).

