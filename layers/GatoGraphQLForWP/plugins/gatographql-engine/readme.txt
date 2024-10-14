=== Gato GraphQL Engine ===
Contributors: gatographql, leoloso
Tags: decoupled, GraphQL, headless, webhook, api, wp-cli, rest, rest-api, react, astro, nextjs, Next.js
Requires at least: 6.1
Tested up to: 6.7
Stable tag: 6.0.2
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Standalone engine for Gato GraphQL, the powerful and flexible GraphQL server for WordPress. Access any piece of data (posts, users, comments, tags, categories, etc) from your app via a GraphQL API.

== Description ==

Gato GraphQL Engine is a simpler version of Gato GraphQL, the powerful and flexible GraphQL server for WordPress: https://wordpress.org/plugins/gatographql/.

Gato GraphQL Engine provides a standalone engine to execute GraphQL queries, without the user interface and additional modules provided by Gato GraphQL.

It already maps the WordPress database into the GraphQL schema (`Post`, `User`, `Comment`, `Block`, etc), so you can fetch any piece of data from your WordPress site.

Use it to retrieve data for your Gutenberg blocks using GraphQL, to render them in the WordPress editor.

Starting from WordPress 6.5, you can include it as a dependency for your plugin, by adding the `Requires Plugins` header to your plugin file:

```php
<?php
/*
Requires Plugins: gatographql-engine
*/
```

Then, access the GraphQL endpoint under:

```
wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=blockEditor
```

(Notice that the user needs to be logged-in to access the endpoint, and able to access the wp-admin.)

The GraphQL single endpoint is not exposed. Hence, visitors cannot query your data.

== Changelog ==

= 7.0.0 =
* Released plugin!
