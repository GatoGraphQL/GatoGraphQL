=== Gato GraphQL ===
Contributors: gatographql, leoloso
Tags: graphql, headless, webhook, api, automator, import export, search replace, google translate, wp-cli, external api, wpgraphql, code snippets
Requires at least: 6.0
Tested up to: 6.6
Stable tag: 2.6.1
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The most powerful GraphQL server for WordPress. Do headless, APIs, webhooks, import/export, search & replace, Google translate, and more.

== Description ==

Gato GraphQL is a productivity tool for interacting with data in your WordPress site. It allows you to retrieve, manipulate and store again any piece of data, in any desired way, using the GraphQL language.

Use Gato GraphQL to create headless WordPress sites (using Nextjs or any other JS framework), power your Gutenberg blocks, fetch data for your theme, plugin or website, or expose an API for other applications.

Gato GraphQL supports Persisted Queries out of the box. Persisted queries are similar to WP REST API endpoints, however they are created and published directly within the wp-admin, using the GraphQL language (and no PHP code at all).

With persisted queries, you can have the great user experience of GraphQL, while having the security from a REST API, limiting clients and visitors to only query the data that you have defined in advance.

You can also create public and private custom endpoints, exposing each of them for some specific target (whether different applications, clients, teams, or other), and have a private endpoint feed data to your custom Gutenberg blocks.

[youtube https://www.youtube.com/watch?v=uabFL_CoEWo]

=== Architectural foundation ===

Gato GraphQL is **optimized for speed:** The query is resolved on linear time complexity, and does not suffer the "n+1" problem. The GraphQL schema is generated only the first time, and cached. The response can be cached, using standard HTTP Caching. And installing additional CPTs does not affect the speed of creating the schema.

Gato GraphQL is also **fully extensible**, so you can create your own extensions and integrations, to extend the GraphQL schema for your own CPTs and plugins.

Gato GraphQL is **super configurable**, to suit your specific needs, even endpoint by endpoint.

And finally, Gato GraphQL is **forward-looking**, already providing features that have been proposed for the GraphQL spec but not yet released.

=== Features ===

Gato GraphQL provides [all these features](https://gatographql.com/features):

- **Security:** Multiple mechanisms are provided to help protect your data.
- **Custom Endpoints:** Create and expose multiple custom GraphQL schemas under their own URL, for different users, applications, external services, or other.
- **Persisted Queries:** GraphQL queries which are stored in the server and accessed under their own URL, thus emulating a REST endpoint.
- **Predefined Persisted Queries:** Tackle admin tasks that are common to WordPress sites, by executing an already-installed persisted query.
- **Public, Private & Password-Protected Endpoints:** In addition to creating and exposing public endpoints, create private endpoints, and protect a public endpoint with a password.
- **Internal Endpoint for Blocks:** Fetch data for your Gutenberg blocks, via an internal GraphQL endpoint accessible within the wp-admin only.
- **API Hierarchy:** Organize endpoints hierarchically, to group and expose related endpoints under a logical structure.
- **Endpoint Management:** Organize custom endpoint and persisted queries by assigning them custom categories, similar to posts in WordPress.
- **Schema namespacing:** Avoid conflicts in the schema, by having all type names automatically namespaced.
- **Nested mutations:** Execute a mutation within a queried entity (similar to accessing a field), and not only on the root type in the GraphQL query.
- **“Sensitive” data:** Decide if to expose or not private data in a public API.
- **Global Fields:** Common fields added to all types of the GraphQL schema (while being defined only once).
- **Composable directives:** Expose directives that augment other directives, modifying their behavior or filling a gap.
- **Multi-Field Directives:** Have directives applied to multiple fields (instead of only one), for performance and extended use cases.
- **'oneOf' Input Object:** Input object where exactly one of the input fields must be provided as input, or the server returns a validation error.
- **Returning different types on mutations:** Mutation fields can be configured to return either a payload object type, or directly the mutated entity.
- **Field and directive-based versioning:** Version fields and directives independently from the overall schema.
- **Proactive feedback:** Use the top-level entry 'extensions' to send data concerning deprecations and warnings in the response to the query.

== Go PRO ==

Unleash your capabilities with **[Gato GraphQL PRO](https://gatographql.com)**, which contains all our PRO extensions for Gato GraphQL.

Use Gato GraphQL PRO to:

- Expose public and private APIs
- Complement WP-CLI to execute admin tasks
- Update posts in bulk
- Search/replace content for site migrations
- Send an email/notifications when something happens (new post published, new comment added, etc)
- Synchronize content across sites, or a multisite network
- Insert or remove Gutenberg blocks in bulk
- Automatically insert a mandatory block when creating a new post
- Translate content in the site using the Google Translate API
- Automatically translate new posts to all different languages for Polylang
- Generate an image using OpenAI's DALL-E or Stable Diffusion, and set it as featured image
- Send new posts to Facebook, Instagram, LinkedIn, or other social media platforms
- Import a post from another WordPress site
- Import all posts from a JSON or CSV file (including from Google Sheets)
- Export all posts to a JSON or CSV file
- Import a post from an RSS feed
- Create posts from static html files
- Interact with cloud services via an HTTP client
- Convert the data from a 3rd-party API into the required format
- Call external APIs to validate your data
- Automate tasks and content workflows when some event happens (eg: `wp_insert_post`), like Zapier for WordPress
- Use WP-Cron to regularly execute GraphQL queries
- And much more...

Gato GraphQL PRO can help you simplify your tech stack, handling the functionality from multiple plugins (so you need to install fewer plugins in your site, and remove bloat), including:

- ✅ APIs
- ✅ Automator
- ✅ Bulk editing/Post duplicator
- ✅ Code snippets
- ✅ Content distribution
- ✅ Email notifications
- ✅ HTTP client
- ✅ Import/export
- ✅ Search & replace
- ✅ Translation
- ✅ Webhooks

As new extensions are created, they are added to Gato GraphQL PRO.

Gato GraphQL PRO clients have access to all **product updates** and **premium support**, and can ask the Gato GraphQL team to work on integrations with popular WordPress plugins.

Check out the [demos section on Gato GraphQL](https://gatographql.com/demos) to watch videos demonstrating the multiple use cases for this plugin.

=== Features ===

Gato GraphQL PRO provides [all these features](https://gatographql.com/features#pro):

- **Enhanced security:** Additional mechanisms to help protect that your data is safe, accessible only to the intended targets.
- **Automation:** Automatically execute a GraphQL Persisted Query when some event happens on the site, creating automations via a user interface.
- **Access Control:** Avoid visitors accessing private data from your site, by granting granular access to the schema, based on the user being logged-in or not, having a certain role or capability, and more.
- **Public/Private Schema:** Control the desired behavior when a user without access to some field or directive in the schema attempts to access it.
- **HTTP Caching:** Cache the GraphQL response via standard HTTP caching, with the max-age value automatically calculated.
- **Integrations with 3rd-party plugins:** Extend the GraphQL schema to access data from popular WordPress plugins.
- **Field Deprecation via UI:** Deprecate fields on the GraphQL schema by using a user interface, without the need to deploy any code.
- **Multiple Query Execution:** Combine multiple queries into a single one, share state across them, and execute it in only one request.
- **Field to Input:** Obtain the value of a field, manipulate it, and input it into another field, all within the same query.
- **Function Fields:** Expose functionalities commonly found in programming languages (such as PHP) via GraphQL fields.
- **Function Directives:** Expose functionalities commonly found in programming languages (such as PHP) via GraphQL directives.
- **Helper Fields:** Set of fields added to the schema, providing commonly-used helper functionality.
- **Environment Fields:** In your GraphQL document, query a value from an environment variable, or from a PHP constant.
- **HTTP Client:** Execute HTTP requests against a webserver and fetch their response.
- **Schema Editing Access:** Grant non-admin users access to the clients in the admin, and access to editing the GraphQL schema and its configuration.

== Source code ==

Gato GraphQL is open source, under the GPLv2 license.

The source code for the plugin is in GitHub repo [GatoGraphQL/GatoGraphQL](https://github.com/GatoGraphQL/GatoGraphQL).

The JavaScript source code for the blocks is under [layers/GatoGraphQLForWP/plugins/gatographql/blocks](https://github.com/GatoGraphQL/GatoGraphQL/tree/master/layers/GatoGraphQLForWP/plugins/gatographql/blocks).

== Frequently Asked Questions ==

= Can I extend the GraphQL schema with my custom types and fields? =

Yes you can. Use the GitHub template [GatoGraphQL/ExtensionStarter](https://github.com/GatoGraphQL/ExtensionStarter) to create an extension, and follow the documentation there.

= How does Gato GraphQL complement WP-CLI? =

With Gato GraphQL you can query data from the WordPress database, and then inject the results into a WP-CLI command (either to select a specific resource, or update an option with some value, or other).

Check out guide [Complementing WP-CLI](https://gatographql.com/guides/code/complementing-wp-cli) for a thorough description on how to do it.

= How do I use Gato GraphQL to build headless sites? =

With Gato GraphQL you can create an endpoint that exposes the data from your WordPress site. Then, within some framework (such as [Next.js](https://nextjs.org) or others) you can query the data and render the HTML.

= Can I fetch Gutenberg block data with Gato GraphQL? =

Yes you can. Check guide [Working with (Gutenberg) blocks](https://gatographql.com/guides/interact/working-with-gutenberg-blocks) for the different ways in which we can query block data, and guide [Mapping JS components to (Gutenberg) blocks](https://gatographql.com/guides/code/mapping-js-components-to-gutenberg-blocks) for an example.

= How is Gato GraphQL different than the WP REST API? =

With the WP REST API, you expose data via REST endpoints, created via PHP code. Each endpoint has its own URL, and its data is pre-defined (for the corresponding resources, such as posts, users, etc).

Gato GraphQL supports "Persisted Queries", which are also endpoints with pre-defined data, however these can be created and published directly within the wp-admin, using GraphQL language (and without any PHP code).

With Gato GraphQL you can also execute tailored GraphQL queries against an endpoint, indicating what specific data you need, and fetching only that within a single request.

= What's the difference between the Gato GraphQL plugin and its PRO version? =

The Gato GraphQL plugin maps the WordPress schema, and is enough to use GraphQL as an API, such as for building headless sites.

Gato GraphQL PRO is needed for enhanced security for public APIs, adding HTTP caching, sending emails, executing updates in bulk, connecting to external services, and automating tasks (among others).

= Does Gato GraphQL support WordPress Multisite? =

Yes, it does. Gato GraphQL can be installed on a multisite network.

In addition, Gato GraphQL provides fields on the GraphQL schema to retrieve multisite network data, allowing to use GraphQL to sync content across the multisite (for instance, to translate posts when using MultilingualPress, as described below).

= How to use Gato GraphQL with Polylang? =

Gato GraphQL PRO provides an integration with Polylang, allowing you to automatically translate a post's content using the Google Translate API, and store it on all translation posts, as defined and managed via Polylang.

You can also synchronize the tags, categories, and featured image, querying the values from the origin post, and setting the corresponding translated values on all the translation posts.

And you can set it all up via automation, so that whenever a post in the default language is published, it is automatically translated to all languages, and stored in the corresponding translation post by Polylang.

Both Polylang and Polylang PRO are supported.

Check out the [Gato GraphQL with Polylang demos](https://gatographql.com/demos?tag=Polylang) to learn more.

= How to use Gato GraphQL with MultilingualPress? =

Gato GraphQL PRO provides an integration with MultilingualPress, allowing you to translate a post's content to all languages defined in a multisite network, and store the translated content (from Google Translate) on the corresponding translation sites.

And you can set it all up via automation, so that whenever a post is published, it is automatically translated to all languages, and stored in the corresponding sites in the network.

Check out the [Gato GraphQL with MultilingualPress demos](https://gatographql.com/demos?tag=MultilingualPress) to learn more.

= How is Gato GraphQL different than WPGraphQL? =

If you just need to build a headless WordPress site and deploy it as static, and you're currently using WPGraphQL, switching to Gato GraphQL will not make any difference.

Otherwise, switching to Gato GraphQL provides many benefits:

The "n+1" query problem just doesn't happen, by design.

When using persisted queries to expose predefined data, you can completely disable the GraphQL single endpoint, so that it is not accessible even to authenticated users.

When caching the GraphQL response using standard HTTP caching, the `max-age` header is calculated automatically, from all the fields present in the GraphQL query (PRO).

You can provide multiple custom endpoints, each of them customized to a specific customer or application, protecting them via a password. And you can add custom categories to them, and give them a hierarchy (such as `/graphql/customers/some-customer` and `/graphql/customers/another-customer`).

You can execute updates in bulk. For instance, you can delete all comments on the site, or assign a tag or category to all your posts. And you can search and replace a string, even using a regex, on hundreds of posts (PRO).

You will have access to novel GraphQL features, proposed for the spec but not yet released, including nested mutations, schema namespacing and the 'oneOf' Input Object. And also dynamic variables and Multiple Query Execution (PRO).

You can validate that only logged-in users, or users with a certain role or capability, or visitors from a certain IP range, can access data, on a field-by-field basis (PRO).

You can compose fields, so that a foundational set of field resolvers can cover an unlimited number of use cases, and compose directives, so that a directive can be applied on an inner property from the field's value.

You can expose private endpoints, accessible only within the wp-admin, to power your Gutenberg blocks. And you can access a private GraphQL server, to fetch data for your application using PHP code, without exposing any public-facing endpoint (PRO).

You can use GraphQL to retrieve, modify and finally store again the content in your site, all within a single GraphQL document (PRO). For instance, you can fetch all the Gutenberg blocks in a post, extract their properties, translate those strings via Google Translate API, insert those strings back into the block, and store the post again.

You can use GraphQL to import posts from another WordPress site, from an RSS feed, from a CSV, or from any REST or GraphQL API. And you can export content as JSON and CSV. (PRO)

You can invoke the API of any external service via an HTTP client (PRO). For instance, you can subscribe your WordPress users to your Mailchimp email list.

You can receive and process incoming data from any service via a dedicated webhook (PRO). For instance, you can capture the newsletter emails registered in an InstaWP sandbox site and send them automatically to Mailchimp.

You can use GraphQL to automate tasks and content workflows (PRO). For instance, when a new post is created (event via hook `draft_post`) you can execute a persisted query that checks if the post does not have a thumbnail and, in that case, generates one by calling the Stable Diffusion API, compresses the image via TinyPng, and finally inserts the image as the post's featured image.

And all of these additional uses cases can be achieved directly within the wp-admin, providing the GraphQL query via a user interface, without having to deploy any PHP code.

= Does Gato GraphQL PRO have a refund policy? =

Yes it does. If you have purchased Gato GraphQL PRO and found out that it's not the right tool to satisfy your needs, you have [30 days to ask for a refund](https://gatographql.com/refund-policy).

= Can I try out Gato GraphQL PRO? =

Yes, you can [launch your own sandbox site](https://app.instawp.io/launch?t=gatographql-demo&d=v2) to play with Gato GraphQL PRO, for free, for 7 days.

= Do I need to be a developer to use Gato GraphQL? =

Gato GraphQL has been designed to be as easy to use as possible, accessible directly within the wp-admin via several user interfaces, powered by the WordPress editor.

The only requirement is to learn the [GraphQL language](https://graphql.org). If you are able to learn this language, then you are able to use Gato GraphQL.

= Why would I want to use Gato GraphQL to manage my WordPress site? =

GraphQL is a super powerful language`, that can achieve a lot more than just building headless sites, and it's easy to learn.

Gato GraphQL is a generic tool that extracts all the power from GraphQL. You provide the GraphQL query directly via the wp-admin, and dozens of use cases (normally provided by dedicated plugins) become available.

Gato GraphQL is not a duplicator plugin, but you can duplicate posts with it. It is not a translation plugin, but you can translate any content, including the properties inside Gutenberg blocks. It is not an automator plugin, but you can automate your tasks, without any restriction. It is not a backup plugin, but you can import and export posts. It is not a search and replace plugin, but you can modify your posts in bulk, with a single query. And it is not webhook plugin or HTTP client, but you can both send a request to any API, and receive and process incoming requests from any service.

What you can use Gato GraphQL for, literally depends on your imagination.

= Are there predefined GraphQL queries for common use cases? =

Gato GraphQL's [queries library](https://gatographql.com/library) contains a collection of GraphQL queries for common use cases, which you can copy/paste and customize to your needs.

This library is continually growing.

= Does the plugin provide documentation? =

The Gato GraphQL website contains extensive documentation, including [guides](https://gatographql.com/guides) on configuring and using the plugin, the [extensions reference](https://gatographql.com/extensions-reference) docs, and a [tutorial](https://gatographql.com/tutorial) to learn all the elements of the GraphQL schema.

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

= 3.0.0 =
* Added compatibility with WordPress 6.6 (#2717)
* Fixed bug: Catch exception if dependency version is not semver (#2712)
* Fixed bug: Convert entries in JSON dictionary of variables in persisted query from array to object (#2715)

= 2.6.0 =
* Added `siteURL` field (#2697)
* Added fields to fetch multisite data (#2698)
* Added documentation for PRO integration with MultilingualPress (#2699)
* Added documentation for new PRO field `_strRegexFindMatches` (#2708)
* Added GraphQL variables `$translateFromLanguage`, `$includeLanguagesToTranslate` and `$excludeLanguagesToTranslate` to persisted queries (#2694 / #2700):
  * [PRO] Translate posts for Polylang (Gutenberg)
  * [PRO] Translate posts for Polylang (Classic editor)
* Added scalar types to the GraphQL schema:
  * `IntValueJSONObject` (#2703)
  * `IDValueJSONObject` (#2704)
* Added predefined persisted queries:
  * [PRO] Translate posts for MultilingualPress (Gutenberg) (#2706)
  * [PRO] Translate posts for MultilingualPress (Classic editor) (#2706)
  * [PRO] Translate Poedit file content (#2709)
* [PRO] Added integration with MultilingualPress
* [PRO] Added input `valueWhenNonExistingKeyOrPath` to field `_objectProperty`
* [PRO] Added automation rules:
  * MultilingualPress: When publishing a post, translate it to all languages (Gutenberg)
  * MultilingualPress: When publishing a post, translate it to all languages (Classic editor)
* Added Gato GraphQL intro video to documentation (#2707)
* Fixed identifying extension in `createMediaItem` when filename has more then one dot

= 2.5.2 =
* Fixed bug: Initialize blocks only after their corresponding CPTs (#2693)

= 2.5.1 =
* Fixed tabs in Markdown in new persisted query **[PRO] Send email to users about post**

= 2.5.0 =
* Added predefined persisted queries
  * [PRO] Send email to users about post (#2692)
* Added documentation for "WordPress hook mapping" for the [PRO] Automation extension (#2691)

= 2.4.1 =
* Fixed bug: Internal server error from passing string when expected int 

= 2.4.0 =
* Added page mutations to the GraphQL schema (#2682)
* Added fields to fetch the logged-in user's pages (#2682)
* Added fields to fetch the site's locale and language (#2685)
* Install "internal" private custom endpoint (#2684)
* Support Application Passwords (#2672)
* Added documentation for new PRO field `_strBase64Encode` (#2673)
* Link extensions to the Extensions Reference in gatographql.com (#2675)
* Added YouTube channel link to About page (#2676)
* Added predefined persisted queries:
  * [PRO] Translate and create all pages for a multilingual site (Multisite / Gutenberg) (#2688)
  * [PRO] Translate and create all pages for a multilingual site (Multisite / Classic editor) (#2688)
* Fixed: Open GraphiQL/Voyager clients in subfolder-based Multisite network (#2677)
* Fixed: Highlight extensions and enable link to visit in website (#2674)
* Fixed: GraphiQL client (for LocalWP) now uses site URL as endpoint (#2686)

= 2.3.0 =
* Added fields `GenericCustomPost.update`, `Root.updateCustomPost` and `Root.createCustomPost` (#2663)
* Added documentation for integration with Polylang (#2664)
* Added module type "Integrations" (#2665)
* Return an EnumString type on `GenericCategory.taxonomy` and `GenericTag.taxonomy` (#2666)
* Fix bug: Updated the Support form's action URL (#2662)
* Added predefined persisted queries: "[PRO] Translate posts for Polylang (Gutenberg)" (#2667), "[PRO] Translate posts for Polylang (Classic editor)" (#2667), "[PRO] Sync featured image for Polylang" (#2669) and "[PRO] Sync tags and categories for Polylang" (#2670)
* Support alternative filenames from 3rd-party plugins for extensions (#2671)
* [PRO] Added integration with Polylang
* [PRO] Added automation rules: "Polylang: When publishing a post, translate it to all languages (Gutenberg)", "Polylang: When publishing a post, translate it to all languages (Classic editor)", "Polylang: When publishing a post, set the featured image for each language on all translation posts" and "Polylang: When publishing a post, set the tags and categories for each language on all translation posts"

= 2.2.3 =
* Bug parsing `@export(as: $someVar)` (#2661)

= 2.2.2 =
* Adapted `blocks` field to work with WordPress 6.5 (#2657)
* Tested up WordPress 6.5
* Renamed "Tutorial" to "Schema tutorial"

= 2.2.1 =
* Added "Lesson (number): " in the tutorials

= 2.2.0 =
* Do not include bundles in the Extensions page
* Do not print the required extensions on predefined persisted queries

= 2.1.3 =
* Added documentation for fields `_arrayFlipToObject` and `_objectIntersectKey` from the PHP Functions via Schema extension
* Added documentation for field `_arrayOfJSONObjectsExtractProperty` from the Helper Function Collection extension

= 2.1.0 =
* Support providing the Schema Configuration to apply when invoking the Internal GraphQL Server
* Added predefined persisted query "Insert block in post"
* If initializing the service container from the cache fails, fallback to initializing PHP object from memory (#2638)
* Give unique operationName to all predefined persisted queries (#2644)
* Improved error message when fetching blocks from a post, and the block content has errors
* Completed documentation for the Automation extension (#2651)
* On the "Generate a post's featured image using AI and optimize it" predefined persisted query, execute logic only if the post title is not empty (#ec931dd)
* Fixed bug in multi-control JS component used by extensions (Access Control, Cache Control, and Field Deprecation) showing "undefined" on the block on the Schema Configuration (#2639)
* Fixed bug in regex replacements in predefined persisted queries (#2649)
* Avoid reinstalling plugin setup data if deactivating/reactivating the plugin (#2641)
* Handle error from passing WP_Post as GraphQL variable to the Internal GraphQL Server (#2652)

= 2.0.0 =
* Added new module Media Mutations
* Added mutation `createMediaItem`
* Added fields `myMediaItemCount`, `myMediaItems` and `myMediaItem`
* Added predefined persisted query "Generate a post's featured image using AI and optimize it"
* Added documentation for new field `_dataMatrixOutputAsCSV` from the Helper Function Collection extension
* Validate the license keys when updating the plugin
* Simplified the Tutorial section
* Prevent max execution time issues when installing plugin on (cheap) shared hosting (#2631)
* Fixed bug where a syntax error on a variable definition in the GraphQL query was not validated
* Breaking change: Field resolver's `validateFieldArgValue` method receives extra argument `$fieldArgs`

= 1.5.4 =
* Fixed bug in resolver where innerBlocks is not set

= 1.5.3 =
* Point the "Missing an extension?" link to the Contact us page
* Add link to bundles on the Extensions page
* Fixed typo in readme.txt

= 1.5.2 =
* Active bundle or extension, with different version than main plugin, did not show "Active" button in Extensions page

= 1.5.1 =
* Improved description on readme.txt (for the WordPress plugin directory)

= 1.5.0 =
* Added video to documentation for bundle
* Added new predefined persisted queries: "Send email to admin about post" and "Add comments block to post"

= 1.4.0 =
* Added predefined custom endpoint "Nested mutations + Entity as mutation payload type"
* Added "Request headers" to GraphiQL clients on single public/private endpoint, and custom endpoints
* Renamed page "Recipes" to "Tutorial", and added settings to hide it
* Renamed existing bundles (“Application Glue & Automator” Bundle => “Tailored WordPress Automator” Bundle, “Content Translation” Bundle => “Simplest WordPress Content Translation” Bundle and “Public API” Bundle => “Responsible WordPress Public API” Bundle)
* Added documentation for new bundles (“Automated Content Translation & Sync for WordPress Multisite” Bundle, “Better WordPress Webhooks” Bundle, “Easy WordPress Bulk Transform & Update” Bundle, “Private GraphQL Server for WordPress” Bundle, “Selective Content Import, Export & Sync for WordPress” Bundle, “Unhindered WordPress Email Notifications” Bundle and “Versatile WordPress Request API” Bundle)
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

