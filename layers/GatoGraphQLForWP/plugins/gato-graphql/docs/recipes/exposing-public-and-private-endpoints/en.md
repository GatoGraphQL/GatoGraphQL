# Exposing public and private endpoints

GraphQL is traditionally about exposing a single endpoint, usually under `https://mysite.com/graphql`.

Gato GraphQL expands this notion, allowing us to expose multiple custom endpoints, each of them tailored to some specific need. For instance, we can expose endpoints:

- `/internal` and `/public`
- `/apps/mobile` and `/apps/website`
- `/clients` and `/visitors`
- `/development`, `/staging` and `/production`
- `/teams/development`, `/teams/testing` and `/teams/marketing`
- `/clients/A`, `/clients/B` and `clients/Z`
- any combination of them

Gato GraphQL also natively supports Persisted Queries, which are endpoints where the GraphQL query is predefined and stored in the server.

This recipe presents suggestions on how and when to use each endpoint.

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

If we have a configuration that we want to apply to all or most endpoints, we can define a [default Schema Configuration](https://gatographql.com/guides/config/defining-the-default-schema-configuration/).

</div>

## When to use the single endpoint

The [single endpoint](https://gatographql.com/guides/config/enabling-and-configuring-the-single-endpoint/) is always public, exposed by default under `/graphql`, and disabled by default (so the "Single Endpoint" module must be enabled).

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Gato GraphQL is managed via "modules", each of them offering some functionality or extension of the GraphQL schema, and which [can be enabled and disabled](https://gatographql.com/guides/config/browsing-enabling-and-disabling-modules/) as needed.

To harden up the security of our API, it is a good practice to disable modules that extend the GraphQL schema (such as modules "Posts", "Users", "Comments", "Blocks", etc) when they are not needed, as to make sure that that data will never be exposed in first place.

In particular, if the API is not meant for mutating data (i.e. creating or updating resources), it is a good practice to disable module "Mutations". Doing so will in turn disable all extensions that provide some mutation (such as modules "Post Mutations", "Comment Mutations", etc), and these mutations will never be exposed in the GraphQL schema.

</div>

The single endpoint is recommended when:

- We need to retrieve data to power a single feature, and
- The WordPress website is not accessible to the open Internet (i.e. it is running on a development laptop, or behind a firewall)

This is the case, for instance, for building a headless site (using frameworks such as [Next.js](https://nextjs.org/) or others).

## When to use public custom endpoints

[Custom endpoints](https://gatographql.com/guides/use/creating-a-custom-endpoint/) are similar to the single endpoint, but we can have many of them, each exposed under its own URL `graphql/{custom-endpoint-slug}/`, with each of them having a different configuration.

Custom endpoints offer security through obscurity, as only the intended target should know about the existence of the custom endpoint and its URL.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

To tighten up the security of the API, we can use the [**Access Control**](https://gatographql.com/extensions/access-control/) extension to [grant access to the endpoint](https://gatographql.com/guides/use/defining-access-control/) only when:

- The user is logged-in (or not)
- The user has some role
- The user has some capability
- The visitor comes from an allowed IP (via the [**Access Control: Visitor IP**](https://gatographql.com/extensions/access-control-visitor-ip/) extension)

Every custom endpoint can have its own Access Control List, thus being accessible only by its specific intended user.

</div>

Custom endpoints are recommended when we need to manage and customize accesses to the API, whether it is by different applications, teams, clients, or any other.

## When to use private custom endpoints

Gato GraphQL implements custom endpoints via Custom Post Types (CPTs). This allows us to [publish the custom endpoint as `private`](https://gatographql.com/guides/special-features/public-private-and-password-protected-endpoints/#heading-private-endpoints) (and also as `password-protected`), thus making the custom endpoint accessible only to logged-in users who have the right to access that custom post, and nobody else.

This method is recommended when the GraphQL endpoint is inteded to be used by the admin of the site only (such as when using GraphQL to execute admin tasks). By completely blocking outside visitors from accessing the endpoint, we will be tightening up the security of the site.

## When to use public Persisted Queries

[Persisted queries](https://gatographql.com/guides/use/creating-a-persisted-query/) are endpoints, each having its own URL, but the GraphQL query is already defined on the server-side, hence the response is also predefined (it can be made dynamic by defining variables, to be satisfied by URL params).

Persisted queries are similar to REST endpoints, but we use the GraphQL language to compose the query, and we can publish it straight from the wp-admin. There is no need to deploy any PHP code to publish a persisted query.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Because persisted queries do not require passing the GraphQL query in the body of the request, they are naturally suited to be accessed via `GET` instead of `POST`.

(The single endpoint and custom endpoints can also be accessed via `GET` by appending param `?query={ GraphQL query }` to the endpoint.)

We can benefit from this and make the API faster via standard [HTTP Caching](https://gatographql.com/guides/use/adding-http-caching/), caching the GraphQL response on the client-side or intermediate stages between the client and the server (such as a CDN).

This is accomplished through the [**Cache Control**](https://gatographql.com/extensions/cache-control/) extension, which automatically calculates and outputs the response's `max-age` value based on the fields and directives present in the query.

</div>

It is recommended to use persisted queries whenever possible, as they substantially increase the security of our sites.

This is because as all data that needs to be made available for our application can already be exposed via persisted queries. Then, we can skip exposing the GraphQL single endpoint (or any custom endpoint), thus removing the chance that users could access private data that we left exposed (by mistake or otherwise).

## When to use private Persisted Queries

Similar to custom endpoints, persisted queries are CPTs, then we can publish them as `private` (or `password-protected`), making them accessible only to the logged-in users who have the right to access it and nobody else.

It is recommended to use these whenever the query is meant for internal use only, such as when searching WordPress data for our own use (as demonstrated in a previous recipe).
