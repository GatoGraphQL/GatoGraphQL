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

The [single endpoint](https://gatographql.com/guides/config/enabling-and-configuring-the-single-endpoint/) is always public, exposed by default under `/graphql`. It is disabled my default, so the "Single Endpoint" module must be enabled.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Gato GraphQL is managed via "modules", each of them offering some functionality or extension of the GraphQL schema, and which [can be enabled and disabled](https://gatographql.com/guides/config/browsing-enabling-and-disabling-modules/) as needed.

To harden up the security of our API, it is a good practice to disable modules that extend the GraphQL schema (such as modules "Posts", "Users", "Comments", "Blocks", etc) when they are not needed, as to make sure that that data will never be exposed in first place.

In particular, we can disable module "Mutations", thus deactivating all extensions that provide some mutation (such as modules "Post Mutations", "Comment Mutations", etc).

</div>

The single endpoint is recommended when:

- We need to retrieve data to power a single feature, and
- The WordPress website is not accessible to the open Internet (i.e. it is running on a development laptop, or behind a firewall)

This is the case, for instance, for building a headless site (using [Next.js](https://nextjs.org/), [Gatsby](https://www.gatsbyjs.com/), etc).

## When to use public custom endpoints

[Custom endpoints](https://gatographql.com/guides/use/creating-a-custom-endpoint/) are similar to the single endpoint, but we can have many of them, each exposed under its own URL `graphql/{custom-endpoint-slug}/`.

Custom endpoints offer security through obscurity, as only the intended target should know about the existence of the custom endpoint and its URL.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

To tighten up the security of the API, we can use the [**Access Control**](https://gatographql.com/extensions/access-control/) extension to [grant access to the endpoint](https://gatographql.com/guides/use/defining-access-control/) only when:

- The user is logged-in (or not)
- The user has some role
- The user has some capability
- The visitor comes from an allowed IP (the [**Access Control: Visitor IP**](https://gatographql.com/extensions/access-control-visitor-ip/) extension is required)

Every custom endpoint can have its own Access Control List, thus being accessible only by its specific intended user.

</div>

Custom endpoints are recommended when we need to manage and customize accesses to the API, whether it is by different applications ("mobile" and "website"), teams ("development" and "marketing"), clients ("client-A" and "client-B") and others.

## When to use private custom endpoints

Gato GraphQL implements custom endpoints via Custom Post Types (CPTs). This allows us to [publish the custom endpoint as `private`](https://gatographql.com/guides/special-features/public-private-and-password-protected-endpoints/#heading-private-endpoints), thus making the custom endpoint accessible only to logged-in users who have the right to access that custom post, and nobody else.

This method is recommended when the logged-in admin of the site is the only user of the GraphQL endpoint (such as when using GraphQL to execute admin tasks). By completely blocking outside visitors from accessing the endpoint, we will be tightening up the security of the site.

You can also [create your own internal endpoint](https://gatographql.com/guides/config/creating-custom-internal-endpoints-for-blocks/), and pre-define whatever specific configuration required for your blocks (enabling nested mutations, enabling namespacing, defining what CPTs can be queried, or anything else available in the Schema Configuration).

## When to use Persisted Queries

Alternatively, you can create Persisted Queries and retrieve data from them (instead of from an endpoint). Check out how the [client code must be adapted](https://gatographql.com/guides/intro/connecting-to-the-graphql-server-from-a-client/#heading-executing-persisted-queries).



<div class="doc-highlight" markdown=1>

🔥 **Tips:**


</div>