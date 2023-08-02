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
- Synchronize content across sites
- Automate tasks
- Complement WP-CLI to execute admin tasks
- Search/replace content for site migrations
- Send notifications when something happens (new post published, new comment added, etc)
- Interact with cloud services
- Convert the data from a 3rd-party API into the required format
- Translate content in the site
- Insert or remove (Gutenberg) blocks in bulk
- Validate that a new post contains a mandatory block
- And much more...

The plugin's "Recipes" section contains GraphQL queries demonstrating these use cases and many more. Copy the query from a recipe, paste it into the query editor, update it to your needs, publish it, and you're good to go.

Gato GraphQL supports Persisted Queries, which are endpoints where the GraphQL query is predefined and stored in the server. Persisted Queries are similar to WP REST API endpoints, however they are created and published directly within the wp-admin (without any PHP code at all!), using the GraphQL language. With Persisted Queries you can expose data while making your site super secure, as you will be restricting visitors from accessing your data at their will.

You can create multiple public and private custom endpoints, exposing each of them for your specific target (whether different applications, clients, teams, or other), and assign a private endpoint to feed data to your custom Gutenberg blocks, avoiding the need to maintain REST controllers.

Gato GraphQL can be augmented via extensions, including:

- [**Access Control**](https://gatographql.com/extensions/access-control/): Grant granular access to your endpoints, field by field
- [**Cache Control**](https://gatographql.com/extensions/cache-control/): Use HTTP caching to make your APIs faster
- [**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/): Combine and execute multiple queries into a single query
- [**HTTP Client**](https://gatographql/extensions/http-client/): Interact with external services
- [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/): Adapt the data via standard PHP functions, always within the query
- Many more

Several extensions have been bundled together, to satisfy the following needs:

- [“All Extensions” Bundle](https://gatographql.com/bundles/all-extensions): All Gato GraphQL extensions in a single bundle
- [“Application Glue & Automator” Bundle](https://gatographql.com/bundles/application-glue-and-automator): Perform and automate tasks for your application
- [“Content Translation” Bundle](https://gatographql.com/bundles/content-translation): Translate content using the Google Translate API
- [“Public API” Bundle](https://gatographql.com/bundles/public-api): Make your public APIs powerful, fast and secure

Browse all bundles and extensions in the [Gato GraphQL shop](https://shop.gatographql.com).

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

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
