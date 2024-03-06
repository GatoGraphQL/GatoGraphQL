=== Gato GraphQL ===
Contributors: gatographql, leoloso
Tags: graphql, headless, webhook, api, automator, import export, search replace, bulk update, wpcli, wget, translation, notifications
Requires at least: 5.4
Tested up to: 6.4
Stable tag: 2.2.0
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The most powerful GraphQL server for WordPress.

== Description ==

Gato GraphQL is a productivity tool for interacting with data in your WordPress site. It allows you to retrieve, manipulate and store again any piece of data, in any desired way, using the GraphQL language.

Use Gato GraphQL to create headless sites, power your Gutenberg blocks, fetch data for your theme, plugin or website, or expose an API for other applications.

Gato GraphQL supports Persisted Queries out of the box. Persisted queries are similar to WP REST API endpoints, however they are created and published directly within the wp-admin, using the GraphQL language (and no PHP code at all).

With persisted queries, you can have the great user experience of GraphQL, while having the security from a REST API, limiting clients and visitors to only query the data that you have defined in advance.

You can also create public and private custom endpoints, exposing each of them for some specific target (whether different applications, clients, teams, or other), and have a private endpoint feed data to your custom Gutenberg blocks.

=== Go PRO ===

Unleash your capabilities with **[Gato GraphQL PRO](https://gatographql.com)**, which contains all our PRO extensions for Gato GraphQL.

Among others, you can use Gato GraphQL PRO to:

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

Gato GraphQL PRO can handle the functionality from multiple plugins:

- ✅ APIs
- ✅ Automator
- ✅ Bulk editing/Post duplicator
- ✅ Content distribution
- ✅ Email notifications
- ✅ HTTP client
- ✅ Import/export
- ✅ Search & replace
- ✅ Translation
- ✅ Webhooks

As new extensions are created, they are added to Gato GraphQL PRO.

Gato GraphQL PRO clients have access to all product updates and premium support, and can ask the Gato GraphQL team to work on integrations with popular WordPress plugins.

== Source code ==

The source code for the plugin is in GitHub repo [GatoGraphQL/GatoGraphQL](https://github.com/GatoGraphQL/GatoGraphQL).

The JavaScript source code for the blocks is under [layers/GatoGraphQLForWP/plugins/gatographql/blocks](https://github.com/GatoGraphQL/GatoGraphQL/tree/master/layers/GatoGraphQLForWP/plugins/gatographql/blocks).

== Frequently Asked Questions ==

= Can I extend the GraphQL schema with my custom types and fields? =

Yes you can. Use the GitHub template [GatoGraphQL/ExtensionStarter](https://github.com/GatoGraphQL/ExtensionStarter) to create an extension, and follow the documentation there.

= How does Gato GraphQL complement WP-CLI? =

With Gato GraphQL you can query data from the WordPress database, and then inject the results into a WP-CLI command (either to select a specific resource, or update an option with some value, or other).

Check out guide [Complementing WP-CLI](https://gatographql.com/guides/code/complementing-wp-cli/) for a thorough description on how to do it.

= How do I use Gato GraphQL to build headless sites? =

With Gato GraphQL you can create an endpoint that exposes the data from your WordPress site. Then, within some framework (such as [Next.js](https://nextjs.org/) or others) you can query the data and render the HTML.

= Can I fetch Gutenberg block data with Gato GraphQL? =

Yes you can. Check guide [Working with (Gutenberg) blocks](https://gatographql.com/guides/interact/working-with-gutenberg-blocks/) for the different ways in which we can query block data, and guide [Mapping JS components to (Gutenberg) blocks](https://gatographql.com/guides/code/mapping-js-components-to-gutenberg-blocks/) for an example.

= How is Gato GraphQL different than the WP REST API? =

With the WP REST API, you expose data via REST endpoints, created via PHP code. Each endpoint has its own URL, and its data is pre-defined (for the corresponding resources, such as posts, users, etc).

Gato GraphQL supports "Persisted Queries", which are also endpoints with pre-defined data, however these can be created and published directly within the wp-admin, using GraphQL language (and without any PHP code).

With Gato GraphQL you can also execute tailored GraphQL queries against an endpoint, indicating what specific data you need, and fetching only that within a single request.

= What's the difference between the Gato GraphQL plugin and its PRO version? =

The Gato GraphQL plugin maps the WordPress schema, and is enough to use GraphQL as an API, such as for building headless sites.

Gato GraphQL PRO is needed for enhanced security for public APIs, adding HTTP caching, sending emails, executing updates in bulk, connecting to external services, and automating tasks (among others).

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

