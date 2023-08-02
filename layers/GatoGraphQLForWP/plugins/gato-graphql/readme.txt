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
- Update thousands of posts with a single action and no PHP code
- Insert or remove Gutenberg blocks in bulk
- Validate that a new post contains a mandatory block
- And much more...

The plugin's "Recipes" section contains GraphQL queries demonstrating these use cases and many more. Copy the query from a recipe, paste it into the query editor, update it to your needs, publish it, and you're good to go.

Gato GraphQL supports Persisted Queries, which are endpoints where the GraphQL query is predefined and stored in the server. Persisted Queries are similar to WP REST API endpoints, however they are created and published directly within the wp-admin (without any PHP code at all!), using the GraphQL language. With Persisted Queries you can expose data while making your site super secure, as you will be restricting visitors from accessing your data at their will.

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

Gato GraphQL can help you query data from the WordPress database, which is then injected into a WP-CLI command (either to select a specific resource, or update an option with some value, or other). Check out recipe [Complementing WP-CLI](http://localhost:8080/recipes/complementing-wp-cli/) for a thorough description on how to do it.

= Can I fetch Gutenberg block data with Gato GraphQL? =

Yes, you can. Check recipe [Mapping JS components to (Gutenberg) blocks](http://localhost:8080/recipes/mapping-js-components-to-gutenberg-blocks/) for an example, and guide [Working with (Gutenberg) blocks](http://localhost:8080/guides/interact/working-with-gutenberg-blocks/) for a thorough description on how to do it.

= Can I use Gato GraphQL to feed data to my Gutenberg Blocks? =

Yes, you can. Check recipe [Feeding data to blocks in the editor](http://localhost:8080/recipes/feeding-data-to-blocks-in-the-editor/) to learn how to create a private endpoint and have the block connect to it via JavaScript.

= How is Gato GraphQL different than the WP REST API? =

With the WP REST API, you expose data via REST endpoints, created via PHP code. Each endpoint has its own URL, and its data is pre-defined (for the corresponding resources, such as posts, users, etc).

Gato GraphQL also supports creating endpoints with pre-defined data as "Persisted Queries", however these can be created and published directly within the wp-admin, without any PHP code.

In addition, with Gato GraphQL you can execute tailored GraphQL queries against an endpoint, indicating what specific data you need and fetching only that. As a result, you can retrieve all needed data in a single request.

= Can I use Gato GraphQL to migrate my site? =

You can use Gato GraphQL to update the data in your database, adapting it from the old site to the new site.

For instance, you can execute a GraphQL query to replace "https://my-old-domain.com" to "https://my-new-domain.com" in the content of all posts (even within Gutenberg block properties).

= How do I execute multiple GraphQL queries in a single request? =

If you have the [**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/) extension, you can combine multiple GraphQL queries into a single one, executing all of them in a single request.

This is particularly useful when a first query "mutates" data (eg: it creates a new post), and then a second query needs to fetch data for that mutated entity. With Multiple Query Execution, both queries can be executed together, thus speeding up the application from a reduced latency (which translates in your users waiting less time when interacting with your site).

Multiple Query Execution can also help you connect to an external API, retrieve data from it, and do something with that data, all within a single request. Check out recipe [Creating an API gateway](http://localhost:8080/recipes/creating-an-api-gateway/) for an example.

= Can I execute GraphQL queries internally within my application? =

Yes you can, but you need the [**Internal GraphQL Server**](https://gatographql.com/extensions/internal-graphql-server/) extension, which installs a private GraphQL server, to be invoked via PHP code.

Check recipe [DRY code for blocks in Javascript and PHP](http://localhost:8080/recipes/dry-code-for-blocks-in-javascript-and-php/) for an example on fetching data to render Gutenberg blocks on the server-side.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Screenshots are stored in the /assets directory.
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== A brief Markdown Example ==

Markdown is what the parser uses to process much of the readme file.

[markdown syntax]: https://daringfireball.net/projects/markdown/syntax

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Links require brackets and parenthesis:

Here's a link to [WordPress](https://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax]. Link titles are optional, naturally.

Blockquotes are email style:

> Asterisks for *emphasis*. Double it up  for **strong**.

And Backticks for code:

`<?php code(); ?>`
