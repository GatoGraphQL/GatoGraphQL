=== Gato GraphQL ===
Contributors: gatographql, leoloso
Tags: graphql, automation, content sync, headless, site migrations, rest api, endpoint, wp-cli, notifications, cloud, translation, gutenberg
Requires at least: 5.4
Tested up to: 6.3
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Interact with all your data in WordPress.

== Description ==

Gato GraphQL is a **tool for interacting with data in your WordPress site**. You can think of it as a Swiss Army Knife for dealing with data, as it allows you to retrieve, manipulate and store again any piece of data, in any desired way, using the [GraphQL language](https://graphql.org/).

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

The plugin's "Recipes" section contains GraphQL queries demonstrating these use cases and many more. Copy the query from a recipe, paste it into the query editor, update it to your needs, publish it, and you're good to go.

Gato GraphQL supports Persisted Queries, which are endpoints where the GraphQL query is predefined and stored in the server. They are similar to WP REST API endpoints, however they are created and published directly within the wp-admin, using the GraphQL language (and no PHP code at all). They allow you to expose data while making your site super secure, as visitors will be restricted from freely browsing your data.

You can also create public and private custom endpoints, exposing each of them for some specific target (whether different applications, clients, teams, or other), and have a private endpoint feed data to your custom Gutenberg blocks, avoiding the need to maintain REST controllers.

Gato GraphQL can be augmented via extensions, including:

- [Access Control](https://gatographql.com/extensions/access-control/): Grant access to your endpoints (by user being logged-in or not, having some role or capability, and others), field by field
- [Cache Control](https://gatographql.com/extensions/cache-control/): Use HTTP caching to make your APIs faster
- [Multiple Query Execution](https://gatographql.com/extensions/multiple-query-execution/): Combine and execute multiple queries into a single query
- [HTTP Client](https://gatographql/extensions/http-client/): Interact with external services
- [PHP Functions via Schema](https://gatographql.com/extensions/php-functions-via-schema/): Adapt the data via standard PHP functions, always within the query
- [Automation](https://gatographql.com/extensions/automation/): Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron
- Many more

Extensions have been bundled together, to satisfy several common use cases:

- [“All Extensions” Bundle](https://gatographql.com/bundles/all-extensions): All Gato GraphQL extensions in a single bundle
- [“Application Glue & Automator” Bundle](https://gatographql.com/bundles/application-glue-and-automator): Perform and automate tasks for your application
- [“Content Translation” Bundle](https://gatographql.com/bundles/content-translation): Translate content using the Google Translate API
- [“Public API” Bundle](https://gatographql.com/bundles/public-api): Make your public APIs powerful, fast and secure

Browse all bundles and extensions on the [Gato GraphQL website](https://gatographql.com/extensions).

Do you need an integration with some WordPress plugin? [Let us know](https://github.com/GatoGraphQL/GatoGraphQLExtensions/discussions).

== Frequently Asked Questions ==

= How does Gato GraphQL complement WP-CLI? =

With Gato GraphQL you can query data from the WordPress database, and then inject the results into a WP-CLI command (either to select a specific resource, or update an option with some value, or other).

Check out recipe [Complementing WP-CLI](https://gatographql.com/recipes/complementing-wp-cli/) for a thorough description on how to do it.

= How do I use Gato GraphQL to build headless sites? =

With Gato GraphQL you can create an endpoint that exposes the data from your WordPress site. Then, within some framework (such as [Next.js](https://nextjs.org/) or others) you can query the data and render the HTML using CSR (client-side rendering).

= How do I fetch Gutenberg block data with Gato GraphQL? =

Check guide [Working with (Gutenberg) blocks](https://gatographql.com/guides/interact/working-with-gutenberg-blocks/) for the different ways in which we can query block data, and recipe [Mapping JS components to (Gutenberg) blocks](https://gatographql.com/recipes/mapping-js-components-to-gutenberg-blocks/) for an example.

= How do I use Gato GraphQL to feed data to my Gutenberg Blocks? =

Check recipe [Feeding data to blocks in the editor](https://gatographql.com/recipes/feeding-data-to-blocks-in-the-editor/) to learn how to create a private endpoint and have the block connect to it via JavaScript.

= How is Gato GraphQL different than the WP REST API? =

With the WP REST API, you expose data via REST endpoints, created via PHP code. Each endpoint has its own URL, and its data is pre-defined (for the corresponding resources, such as posts, users, etc).

Gato GraphQL supports "Persisted Queries", which are also endpoints with pre-defined data, however these can be created and published directly within the wp-admin, using GraphQL language (and without any PHP code).

In addition, with Gato GraphQL you can execute tailored GraphQL queries against an endpoint, indicating what specific data you need, and fetching only that. As a result, you can retrieve all needed data in a single request.

= Can I use Gato GraphQL to migrate my site? =

With Gato GraphQL you can execute queries to update data in your database, converting content into what is required for the new site.

For instance, you can execute a query that replaces "https://my-old-domain.com" with "https://my-new-domain.com" in the content of all posts (even within Gutenberg block properties).

Check recipe [Site migrations](https://gatographql.com/recipes/site-migrations/) to learn how to do this.

= How to translate the content in my site using Gato GraphQL? =

The [“Content Translation” Bundle](https://gatographql.com/bundles/content-translation) gives you all the tools needed to create a GraphQL query that extracts text properties from Gutenberg blocks, translates them to another language using the Google Translate API, and then stores the post's content back to the database.

Check recipes [Translating block content in a post to a different language](https://gatographql.com/recipes/translating-block-content-in-a-post-to-a-different-language/) for a thorough explanation on how to do this, and [Bulk translating block content in multiple posts to a different language](https://gatographql.com/recipes/bulk-translating-block-content-in-multiple-posts-to-a-different-language/) on how to do it in bulk.

= How do I execute multiple GraphQL queries in a single request? =

The [Multiple Query Execution](https://gatographql.com/extensions/multiple-query-execution/) extension allows you to combine multiple GraphQL queries into a single one and execute them all in a single request.

Multiple Query Execution is particularly useful when a first query "mutates" data (eg: it creates a new post), and then a second query needs to fetch data from that mutated entity. As both queries will be executed in a single request, the latency will be lower, and your users will wait less time when interacting with your site. Check recipe [Duplicating a blog post](https://gatographql.com/recipes/duplicating-a-blog-post/) for an example.

Multiple Query Execution can also help you connect to an external API, retrieve data from it, and do something with that data, all within a single request. Check out recipe [Creating an API gateway](https://gatographql.com/recipes/creating-an-api-gateway/) for an example.

= Can Persisted Queries be used as webhooks? =

Yes they can, because Persisted Queries are exposed under their own URL, they can extract the payload data, and then do something with that data (update a post, add a comment, send a notification, etc).

Check recipe [Interacting with external services via webhooks](https://gatographql.com/recipes/interacting-with-external-services-via-webhooks/) to learn how to do this.

= How do I use Gato GraphQL to interact with external services? =

The [HTTP Client](https://gatographql/extensions/http-client/) extension adds fields to the GraphQL schema to fetch data from any webserver (while providing special support to connect to REST and GraphQL APIs).

Check recipe [Retrieving data from an external API](https://gatographql.com/recipes/retrieving-data-from-an-external-api/) to learn about all the available "HTTP client" fields, and examples on how to use them.

= How do I execute GraphQL queries internally within my application? =

The [Internal GraphQL Server](https://gatographql.com/extensions/internal-graphql-server/) extension installs a private GraphQL server, to be invoked via PHP code.

Check recipe [DRY code for blocks in Javascript and PHP](https://gatographql.com/recipes/dry-code-for-blocks-in-javascript-and-php/) for an example on fetching data to render Gutenberg blocks on the server-side.

= How can Gato GraphQL help automate tasks? =

The [Automation](https://gatographql.com/extensions/automation/) extension triggers an action hook when a GraphQL query is resolved (whether it was executed via a public or private endpoint, or internally via the [Internal GraphQL Server](https://gatographql.com/extensions/internal-graphql-server/) extension) that you can hook into to execute some custom functionality (and even other GraphQL queries).

This way, you can react to events and do something about them. For instance, whenever a new post is added on the site, you can send a notification by email. Check recipe [Sending a notification when there is a new post](https://gatographql.com/recipes/sending-a-notification-when-there-is-a-new-post/) to learn how to do this.

The Automation extension also provides integration with WP-Cron, allowing you to schedule the execution of GraphQL queries, every some period of time.

For instance, you can retrieve data daily and send yourself a summary via email. Check recipe [Sending a daily summary of activity](https://gatographql.com/recipes/sending-a-daily-summary-of-activity/) to learn how to do this.

= How can Gato GraphQL synchronize content across sites? =

We can create GraphQL queries that fetch content from a site, and import that content into another site. Check recipe [Importing a post from another WordPress site](https://gatographql.com/recipes/importing-a-post-from-another-wordpress-site/) to learn how to do this.

You can even synchronize content across a network of sites, such as from an upstream to multiple downstreams. Check recipe [Distributing content from an upstream to multiple downstream sites](https://gatographql.com/recipes/distributing-content-from-an-upstream-to-multiple-downstream-sites/) which explains how to do this.

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
16. The Recipes section contains example queries ready to copy/paste and use 

== Changelog ==

= 1.0 =
* Plugin is released! Refer to the [changelog in the plugin's repo](https://github.com/GatoGraphQL/GatoGraphQL/blob/master/layers/GatoGraphQLForWP/plugins/gatographql/CHANGELOG.md) for a thorough description of all changes

