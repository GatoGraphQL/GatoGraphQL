=== Gato GraphQL ===
Contributors: gatographql, leoloso
Tags: graphql, automation, content sync, import, export, headless, migration, rest api, endpoint, wp-cli, wget, translation
Requires at least: 5.4
Tested up to: 6.4
Stable tag: 1.3.1
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Interact with all your data in WordPress.

== Description ==

Gato GraphQL is a **tool for interacting with data in your WordPress site**. You can think of it as a Swiss Army knife for dealing with data, as it allows you to retrieve, manipulate and store again any piece of data, in any desired way, using the [GraphQL language](https://graphql.org/).

With Gato GraphQL, you can:

- Query data to create headless sites
- Expose public and private APIs
- Map JS components to Gutenberg blocks
- Synchronize content across sites
- Automate tasks
- Complement WP-CLI to execute admin tasks
- Search/replace content for site migrations
- Send notifications when something happens (new post published, new comment added, etc)
- Interact with cloud services
- Convert the data from a 3rd-party API into the required format
- Translate content in the site
- Update thousands of posts with a single action
- Insert or remove Gutenberg blocks in bulk
- Validate that a new post contains a mandatory block
- And much more...

The plugin's "Tutorial" section explains how to achieve all of these objectives, one by one, by exploring all the elements from the GraphQL schema (the types, fields, directives, etc).

Gato GraphQL supports Persisted Queries, which are endpoints where the GraphQL query is predefined and stored in the server. They are similar to WP REST API endpoints, however they are created and published directly within the wp-admin, using the GraphQL language (and no PHP code at all). They allow you to expose data while making your site super secure, as visitors will be restricted from freely browsing your data.

You can also create public and private custom endpoints, exposing each of them for some specific target (whether different applications, clients, teams, or other), and have a private endpoint feed data to your custom Gutenberg blocks, avoiding the need to maintain REST controllers.

Gato GraphQL can be augmented via extensions, including:

- [Access Control](https://gatographql.com/extensions/access-control/): Grant access to your endpoints (by user being logged-in or not, having some role or capability, and others), field by field
- [Cache Control](https://gatographql.com/extensions/cache-control/): Use HTTP caching to make your APIs faster
- [Multiple Query Execution](https://gatographql.com/extensions/multiple-query-execution/): Combine and execute multiple queries into a single query
- [HTTP Client](https://gatographql.com/extensions/http-client/): Interact with external services
- [PHP Functions via Schema](https://gatographql.com/extensions/php-functions-via-schema/): Adapt the data via standard PHP functions, always within the query
- [Automation](https://gatographql.com/extensions/automation/): Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron
- Many more

Extensions have been bundled together, to satisfy several common use cases:

- [“All Extensions” Bundle](https://gatographql.com/bundles/all-extensions): All Gato GraphQL extensions in a single bundle
- [“Application Glue & Automator” Bundle](https://gatographql.com/bundles/application-glue-and-automator): Perform and automate tasks for your application
- [“Content Translation” Bundle](https://gatographql.com/bundles/content-translation): Translate content using the Google Translate API
- [“Public API” Bundle](https://gatographql.com/bundles/public-api): Make your public APIs powerful, fast and secure

Browse all bundles and extensions on the [Gato GraphQL website](https://gatographql.com/extensions).

Do you need an integration with some WordPress plugin? [Let us know](https://github.com/GatoGraphQL/ExtensionsPlanningAndTracking/discussions).

== Source code ==

The source code for the plugin is in GitHub repo [GatoGraphQL/GatoGraphQL](https://github.com/GatoGraphQL/GatoGraphQL).

This JavaScript source code for the blocks is under [layers/GatoGraphQLForWP/plugins/gatographql/blocks](https://github.com/GatoGraphQL/GatoGraphQL/tree/master/layers/GatoGraphQLForWP/plugins/gatographql/blocks).

== Frequently Asked Questions ==

= How does Gato GraphQL complement WP-CLI? =

With Gato GraphQL you can query data from the WordPress database, and then inject the results into a WP-CLI command (either to select a specific resource, or update an option with some value, or other).

Check out tutorial lesson [Complementing WP-CLI](https://gatographql.com/tutorial/complementing-wp-cli/) for a thorough description on how to do it.

= How do I use Gato GraphQL to build headless sites? =

With Gato GraphQL you can create an endpoint that exposes the data from your WordPress site. Then, within some framework (such as [Next.js](https://nextjs.org/) or others) you can query the data and render the HTML using CSR (client-side rendering).

= How do I fetch Gutenberg block data with Gato GraphQL? =

Check guide [Working with (Gutenberg) blocks](https://gatographql.com/guides/interact/working-with-gutenberg-blocks/) for the different ways in which we can query block data, and tutorial lesson [Mapping JS components to (Gutenberg) blocks](https://gatographql.com/tutorial/mapping-js-components-to-gutenberg-blocks/) for an example.

= How do I use Gato GraphQL to feed data to my Gutenberg Blocks? =

Check tutorial lesson [Feeding data to blocks in the editor](https://gatographql.com/tutorial/feeding-data-to-blocks-in-the-editor/) to learn how to create a private endpoint and have the block connect to it via JavaScript.

= How is Gato GraphQL different than the WP REST API? =

With the WP REST API, you expose data via REST endpoints, created via PHP code. Each endpoint has its own URL, and its data is pre-defined (for the corresponding resources, such as posts, users, etc).

Gato GraphQL supports "Persisted Queries", which are also endpoints with pre-defined data, however these can be created and published directly within the wp-admin, using GraphQL language (and without any PHP code).

In addition, with Gato GraphQL you can execute tailored GraphQL queries against an endpoint, indicating what specific data you need, and fetching only that. As a result, you can retrieve all needed data in a single request.

= Can I use Gato GraphQL to migrate my site? =

With Gato GraphQL you can execute queries to update data in your database, converting content into what is required for the new site.

For instance, you can execute a query that replaces "https://my-old-domain.com" with "https://my-new-domain.com" in the content of all posts (even within Gutenberg block properties).

Check tutorial lesson [Site migrations](https://gatographql.com/tutorial/site-migrations/) to learn how to do this.

= How to translate the content in my site using Gato GraphQL? =

The [“Content Translation” Bundle](https://gatographql.com/bundles/content-translation) gives you all the tools needed to create a GraphQL query that extracts text properties from Gutenberg blocks, translates them to another language using the Google Translate API, and then stores the post's content back to the database.

Check tutorial lesson [Translating block content in a post to a different language](https://gatographql.com/tutorial/translating-block-content-in-a-post-to-a-different-language/) for a thorough explanation on how to do this, and [Bulk translating block content in multiple posts to a different language](https://gatographql.com/tutorial/bulk-translating-block-content-in-multiple-posts-to-a-different-language/) on how to do it in bulk.

= How do I execute multiple GraphQL queries in a single request? =

The [Multiple Query Execution](https://gatographql.com/extensions/multiple-query-execution/) extension allows you to combine multiple GraphQL queries into a single one and execute them all in a single request.

Multiple Query Execution is particularly useful when a first query "mutates" data (eg: it creates a new post), and then a second query needs to fetch data from that mutated entity. As both queries will be executed in a single request, the latency will be lower, and your users will wait less time when interacting with your site. Check tutorial lesson [Duplicating a blog post](https://gatographql.com/tutorial/duplicating-a-blog-post/) for an example.

Multiple Query Execution can also help you connect to an external API, retrieve data from it, and do something with that data, all within a single request. Check out tutorial lesson [Creating an API gateway](https://gatographql.com/tutorial/creating-an-api-gateway/) for an example.

= Can Persisted Queries be used as webhooks? =

Yes they can, because Persisted Queries are exposed under their own URL, they can extract the payload data, and then do something with that data (update a post, add a comment, send a notification, etc).

Check tutorial lesson [Interacting with external services via webhooks](https://gatographql.com/tutorial/interacting-with-external-services-via-webhooks/) to learn how to do this.

= How do I use Gato GraphQL to interact with external services? =

The [HTTP Client](https://gatographql.com/extensions/http-client/) extension adds fields to the GraphQL schema to fetch data from any webserver (while providing special support to connect to REST and GraphQL APIs).

Check tutorial lesson [Retrieving data from an external API](https://gatographql.com/tutorial/retrieving-data-from-an-external-api/) to learn about all the available "HTTP client" fields, and examples on how to use them.

= How do I execute GraphQL queries internally within my application? =

The [Internal GraphQL Server](https://gatographql.com/extensions/internal-graphql-server/) extension installs a private GraphQL server, to be invoked via PHP code.

Check tutorial lesson [DRY code for blocks in Javascript and PHP](https://gatographql.com/tutorial/dry-code-for-blocks-in-javascript-and-php/) for an example on fetching data to render Gutenberg blocks on the server-side.

= How can Gato GraphQL help automate tasks? =

The [Automation](https://gatographql.com/extensions/automation/) extension triggers an action hook when a GraphQL query is resolved (whether it was executed via a public or private endpoint, or internally via the [Internal GraphQL Server](https://gatographql.com/extensions/internal-graphql-server/) extension) that you can hook into to execute some custom functionality (and even other GraphQL queries).

This way, you can react to events and do something about them. For instance, whenever a new post is added on the site, you can send a notification by email. Check tutorial lesson [Sending a notification when there is a new post](https://gatographql.com/tutorial/sending-a-notification-when-there-is-a-new-post/) to learn how to do this.

The Automation extension also provides integration with WP-Cron, allowing you to schedule the execution of GraphQL queries, every some period of time.

For instance, you can retrieve data daily and send yourself a summary via email. Check tutorial lesson [Sending a daily summary of activity](https://gatographql.com/tutorial/sending-a-daily-summary-of-activity/) to learn how to do this.

= How can Gato GraphQL synchronize content across sites? =

We can create GraphQL queries that fetch content from a site, and import that content into another site. Check tutorial lesson [Importing a post from another WordPress site](https://gatographql.com/tutorial/importing-a-post-from-another-wordpress-site/) to learn how to do this.

You can even synchronize content across a network of sites, such as from an upstream to multiple downstreams. Check tutorial lesson [Distributing content from an upstream to multiple downstream sites](https://gatographql.com/tutorial/distributing-content-from-an-upstream-to-multiple-downstream-sites/) which explains how to do this.

== Screenshots ==

1. GraphiQL client to execute queries in the wp-admin
2. Interactively browse the GraphQL schema, exploring all connections among entities
3. The GraphiQL client for the single endpoint is exposed to the Internet
4. Interactively browse the GraphQL schema exposed for the single endpoint
5. Persisted queries are pre-defined and stored in the server
6. Requesting a persisted query URL will retrieve its pre-defined GraphQL response
7. We can create multiple custom endpoints, each for a different target
8. Endpoints are configured via Schema Configurations
9. We can create many Schema Configurations, customizing them for different users or applications
10. Custom endpoints and Persisted queries can be public, private and password-protected
11. Manage custom endpoints and persisted queries by adding categories to them
12. We can configure exactly what custom post types, options and meta keys can be queried
13. Configure every aspect from the plugin via the Settings page
14. Modules with different functionalities and schema extensions can be enabled and disabled
15. Augment the plugin functionality and GraphQL schema via extensions
16. The Tutorial section explains how to achieve many objectives, exploring all the elements from the GraphQL schema

== Changelog ==

= 1.4.0 =
* Added predefined custom endpoint "Nested mutations + Entity as mutation payload type"
* Added "Request headers" to GraphiQL clients on single public/private endpoint, and custom endpoints
* Renamed page "Recipes" to "Tutorial", and added settings to hide it
* Fixed HTML codes were printed in select inputs in the WordPress editor, now removed

= 1.3.0 =
* Read `GET` variables when executing Persisted Queries via `POST`
* Pass data via URL params in persisted query "Register a newsletter subscriber from InstaWP to Mailchimp"
* Fixed component docs displayed in the editor were not included in the plugin

= 1.2.0 =
* Added `XML` scalar type
* Added documentation for new field `_strDecodeXMLAsJSON` from the Helper Function Collection extension
* Added documentation for new field `_strParseCSV` from the Helper Function Collection extension
* Added tutorial lesson "Translating content from URL"
* Added predefined Persisted Queries "Translate post (Classic editor)" and "Translate posts (Classic editor)"
* Added predefined Persisted Query "Translate content from URL"
* Added predefined Persisted Query "Import post from WordPress RSS feed"
* Added predefined Persisted Query "Import posts from CSV"
* Added predefined Persisted Query "Fetch post links"
* In predefined persisted queries "Translate post" and "Translate posts", added `failIfNonExistingKeyOrPath: false` when selecting a block's `attributes.{something}` property (as it may sometimes not be defined)
* In predefined persisted query "Import post from WordPress site", added status `any` to select the post
* Renamed persisted query "Translate post" to "Translate post (Gutenberg)", and "Translate posts" to "Translate posts (Gutenberg)"

= 1.1.1 =
* Fixed bug on the caching component (a downgraded `reset` method was called on a non array)

= 1.1.0 =
* Tested with WordPress 6.4 ("Tested up to: 6.4")
* Install initial data: Persisted Queries for common admin tasks
* Added `AnyStringScalar` wildcard scalar type
* Purge container when autoupdating a depended-upon plugin

= 1.0.15 =
* Fixed: Executing introspection query failed in GraphiQL client when passing ?operationName=...

= 1.0.14 =
* Configuration alert in tutorial lesson "Duplicating multiple blog posts at once"

= 1.0.13 =
* Fixed passing dynamic variables 2 levels down
* Fixed not opening link in modal window when current page is in a modal window
* Fixed the newsletter form "action" attribute
* Added scalar type `AnyScalar`
* Added documentation for new field `_arrayGenerateAllCombinationsOfItems` from the "Helper Function Collection" extension

= 1.0.12 =
* Adapted the plugin following the assessment by the WordPress Plugin Review team.
* Recipes: Replace `mysite.com` with the site domain
* Added tutorial lesson: Automatically sending newsletter subscribers from InstaWP to Mailchimp

= 1.0.11 =
* The `operationName` param can be obtained from the URL even when doing `POST`

= 1.0.10 =
* Return `String` in fields `Root.optionValue` and `WithMeta.metaValue`

= 1.0 =
* Plugin is released! Refer to the [changelog in the plugin's repo](https://github.com/GatoGraphQL/GatoGraphQL/blob/master/layers/GatoGraphQLForWP/plugins/gatographql/CHANGELOG.md) for a thorough description of all changes

