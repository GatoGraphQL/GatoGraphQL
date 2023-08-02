=== Gato GraphQL ===
Contributors: gatographql, leoloso
Tags: graphql, automation, content sync, headless, site migrations, rest api, endpoint, wp-cli, notifications, cloud, translation, gutenberg
Requires at least: 5.4
Tested up to: 6.2
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The single tool you need for interacting with all your data in WordPress.

== Description ==

Gato GraphQL is a **tool for interacting with data in your WordPress site** via the [GraphQL language](https://graphql.org/). You can think of it as a Swiss Army Knife for dealing with data in a WordPress site, as it allows you to retrieve, manipulate and store again any piece of data, in any desired way.

Among others, it allows you to:

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

Gato GraphQL supports Persisted Queries, which are endpoints where the GraphQL query is predefined and stored in the server. They are similar to WP REST API endpoints, however they are created and published directly within the wp-admin, using the GraphQL language (and no PHP code at all).

With Persisted Queries you can expose data while making your site super secure, as you will be restricting visitors from accessing your data at their will.

You can create public and private custom endpoints, exposing each of them for some specific target (whether different applications, clients, teams, or other). You can also create a private endpoint to feed data to your custom Gutenberg blocks, avoiding the need to maintain REST controllers.

Gato GraphQL can be augmented via extensions, including:

- [**Access Control**](https://gatographql.com/extensions/access-control/): Grant granular access to your endpoints, field by field
- [**Cache Control**](https://gatographql.com/extensions/cache-control/): Use HTTP caching to make your APIs faster
- [**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/): Combine and execute multiple queries into a single query
- [**HTTP Client**](https://gatographql/extensions/http-client/): Interact with external services
- [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/): Adapt the data via standard PHP functions, always within the query
- [**Automation**](https://gatographql.com/extensions/automation/): Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron
- Many more

Extensions have been bundled together, to satisfy several common use cases:

- [“All Extensions” Bundle](https://gatographql.com/bundles/all-extensions): All Gato GraphQL extensions in a single bundle
- [“Application Glue & Automator” Bundle](https://gatographql.com/bundles/application-glue-and-automator): Perform and automate tasks for your application
- [“Content Translation” Bundle](https://gatographql.com/bundles/content-translation): Translate content using the Google Translate API
- [“Public API” Bundle](https://gatographql.com/bundles/public-api): Make your public APIs powerful, fast and secure

Browse all bundles and extensions in the [Gato GraphQL shop](https://shop.gatographql.com).

Do you need an integration with some WordPress plugin? [Let us know](https://gatographql.com/request-extension).

== Frequently Asked Questions ==

= How does Gato GraphQL complement WP-CLI? =

Gato GraphQL can help you query data from the WordPress database, which is then injected into a WP-CLI command (either to select a specific resource, or update an option with some value, or other). Check out recipe [Complementing WP-CLI](https://gatographql.com/recipes/complementing-wp-cli/) for a thorough description on how to do it.

= Can I use Gato GraphQL to build headless sites? =

Yes, you can. With Gato GraphQL, you can create an endpoint which exposes the data from your WordPress site. A framework to build headless sites (based on Next.js, Gatsby, Hugo, or others) can then query the data from your WordPress site, and render the website as static HTML/JS/CSS code.

= Can I fetch Gutenberg block data with Gato GraphQL? =

Yes, you can. Check recipe [Mapping JS components to (Gutenberg) blocks](https://gatographql.com/recipes/mapping-js-components-to-gutenberg-blocks/) for an example, and guide [Working with (Gutenberg) blocks](https://gatographql.com/guides/interact/working-with-gutenberg-blocks/) for a thorough description on how to do it.

= Can I use Gato GraphQL to feed data to my Gutenberg Blocks? =

Yes, you can. Check recipe [Feeding data to blocks in the editor](https://gatographql.com/recipes/feeding-data-to-blocks-in-the-editor/) to learn how to create a private endpoint and have the block connect to it via JavaScript.

= How is Gato GraphQL different than the WP REST API? =

With the WP REST API, you expose data via REST endpoints, created via PHP code. Each endpoint has its own URL, and its data is pre-defined (for the corresponding resources, such as posts, users, etc).

Gato GraphQL also supports creating endpoints with pre-defined data as "Persisted Queries", however these can be created and published directly within the wp-admin, without any PHP code.

In addition, with Gato GraphQL you can execute tailored GraphQL queries against an endpoint, indicating what specific data you need and fetching only that. As a result, you can retrieve all needed data in a single request.

= Can I use Gato GraphQL to migrate my site? =

You can use Gato GraphQL to update the data in your database, adapting it from the old site to the new site.

For instance, you can execute a GraphQL query to replace "https://my-old-domain.com" to "https://my-new-domain.com" in the content of all posts (even within Gutenberg block properties). Check recipe [Site migrations](https://gatographql.com/recipes/site-migrations/) to learn how to do this.

= Can Gato GraphQL translate content in the site? =

Yes it can, via the extensions provided by the [“Content Translation” Bundle](https://gatographql.com/bundles/content-translation). With this bundle, you can create a GraphQL query that extracts text properties from Gutenberg blocks, translates them to another language using the Google Translate API, and then stores the post back to the database.

Check recipes [Translating block content in a post to a different language](https://gatographql.com/recipes/translating-block-content-in-a-post-to-a-different-language/) for a thorough explanation on how to do this, and [Bulk translating block content in multiple posts to a different language](https://gatographql.com/recipes/bulk-translating-block-content-in-multiple-posts-to-a-different-language/) on how to do it in bulk.

= How do I execute multiple GraphQL queries in a single request? =

If you have the [**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/) extension, you can combine multiple GraphQL queries into a single one, executing all of them in a single request.

This is particularly useful when a first query "mutates" data (eg: it creates a new post), and then a second query needs to fetch data for that mutated entity. With Multiple Query Execution, both queries can be executed together, thus speeding up the application from a reduced latency (which translates in your users waiting less time when interacting with your site).

Multiple Query Execution can also help you connect to an external API, retrieve data from it, and do something with that data, all within a single request. Check out recipe [Creating an API gateway](https://gatographql.com/recipes/creating-an-api-gateway/) for an example.

= Can Persisted Queries be used as webhooks? =

Yes they can, because a persisted query lives on its own URL, it can extract the payload data (directly if passed as URL params, or using the [**HTTP Request via Schema**](https://gatographql.com/extensions/http-request-via-schema/) extension if passed in the body of the request), and then do something with that data (update a post, add a comment, send a notification, etc).

Check recipe [Interacting with external services via webhooks](https://gatographql.com/recipes/interacting-with-external-services-via-webhooks/) to learn how to do this.

= Can I interact with external services? =

Yes you can, via the [**HTTP Client**](https://gatographql/extensions/http-client/) extension, which adds fields to the GraphQL schema to fetch data from any webserver, and provides special support to connect to REST and GraphQL APIs.

Check recipe [Retrieving data from an external API](https://gatographql.com/recipes/retrieving-data-from-an-external-api/) to learn about all the new "HTTP client" fields, and examples on how to use them.

= Can I execute GraphQL queries internally within my application? =

Yes you can, via the [**Internal GraphQL Server**](https://gatographql.com/extensions/internal-graphql-server/) extension, which installs a private GraphQL server, to be invoked via PHP code.

Check recipe [DRY code for blocks in Javascript and PHP](https://gatographql.com/recipes/dry-code-for-blocks-in-javascript-and-php/) for an example on fetching data to render Gutenberg blocks on the server-side.

= How can Gato GraphQL help automate tasks? =

If you have the [**Automation**](https://gatographql.com/extensions/automation/) extension, you can trigger a hook when a GraphQL query is resolved (whether it was executed via a public or private endpoint, or internally via the [**Internal GraphQL Server**](https://gatographql.com/extensions/internal-graphql-server/) extension), and you can use WP-Cron to execute GraphQL queries every some period of time.

This way, you can react to events and do something about them. For instance, whenever a new post is added on the site, you can send a notification by email. Check recipe [Sending a notification when there is a new post](https://gatographql.com/recipes/sending-a-notification-when-there-is-a-new-post/) to learn how to do this.

= How can Gato GraphQL synchronize content across sites? =

We can create GraphQL queries that fetch content from a site, and import that content into another site or even network of sites.

Check recipes [Importing a post from another WordPress site](https://gatographql.com/recipes/importing-a-post-from-another-wordpress-site/) and [Distributing content from an upstream to multiple downstream sites](https://gatographql.com/recipes/distributing-content-from-an-upstream-to-multiple-downstream-sites/) which explain how to do this.

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
11. We can configure exactly what custom post types, options and meta keys can be queried
12. Configure every aspect from the plugin via the Settings page
13. Modules with different functionalities and schema extensions can be enabled and disabled
14. Augment the plugin functionality and GraphQL schema via extensions
15. The Recipes section contains example queries ready to copy/paste and use 

== Changelog ==

= 1.0 =
* Plugin is released! Refer to the [changelog in the plugin's repo](https://github.com/leoloso/PoP/blob/master/layers/GatoGraphQLForWP/plugins/gato-graphql/CHANGELOG.md) for a thorough description of all changes

