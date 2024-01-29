=== Gato GraphQL ===
Contributors: gatographql, leoloso
Tags: graphql, automation, notifications, import, export, headless, webhook, rest api, search replace, wp-cli, wget, translation
Requires at least: 5.4
Tested up to: 6.4
Stable tag: 1.6.0
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Interact with all your data in WordPress.

== Description ==

Gato GraphQL is a productivity tool for interacting with data in your WordPress site. It allows you to retrieve, manipulate and store again any piece of data, in any desired way.

Gato GraphQL can handle the functionality from multiple plugins:

- ✅ APIs
- ✅ Automators
- ✅ Bulk editing
- ✅ Code snippets
- ✅ Content distribution
- ✅ Email notifications
- ✅ HTTP client
- ✅ Import/export
- ✅ Search & replace
- ✅ Translation
- ✅ Webhooks

Among others, you can use it to:

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

Gato GraphQL supports Persisted Queries out of the box. Persisted queries are similar to WP REST API endpoints, however they are created and published directly within the wp-admin, using the GraphQL language (and no PHP code at all).

With persisted queries, you can have the great user experience of GraphQL, while having the security from a REST API, limiting clients and visitors to only query the data that you have defined in advance.

You can also create public and private custom endpoints, exposing each of them for some specific target (whether different applications, clients, teams, or other), and have a private endpoint feed data to your custom Gutenberg blocks.

Browse the [Queries Library](https://gatographql.com/library/) for ready-to-use queries for your WordPress site. (The more popular queries are already created as Persisted Queries when installing the plugin.) The library is growing constantly, with new queries added on a regular basis.

=== Augment Gato GraphQL via bundles and extensions ===

[Extensions](https://gatographql.com/extensions/) provide additional functionality and expand the GraphQL schema.

[Bundles](https://gatographql.com/bundles/) are sets of extensions that satisfy predefined use cases.

The bundle containing all extensions is:

👉🏽 **[“All in One Toolbox for WordPress” Bundle](https://gatographql.com/bundles/all-in-one-toolbox-for-wordpress/)**: As it contains all of Gato GraphQL extensions, it supports all promoted features and use cases.

The bundles for specific use cases are:

👉🏽 [“Automated Content Translation & Sync for WordPress Multisite” Bundle](https://gatographql.com/bundles/automated-content-translation-and-sync-for-wordpress-multisite/): Automatically create a translation of a newly-published post using the Google Translate API, for every language site on a WordPress multisite.

👉🏽 [“Better WordPress Webhooks” Bundle](https://gatographql.com/bundles/better-wordpress-webhooks/): Easily create webhooks to process incoming data from any source or service using advanced tools, directly within the wp-admin.

👉🏽 [“Easy WordPress Bulk Transform & Update” Bundle](https://gatographql.com/bundles/easy-wordpress-bulk-transform-and-update/): Transform hundreds of posts with a single operation (replacing strings, adding blocks, adding a thumbnail, and more), and store them again on the DB.

👉🏽 [“Private GraphQL Server for WordPress” Bundle](https://gatographql.com/bundles/private-graphql-server-for-wordpress/): Use GraphQL to power your application (blocks, themes and plugins), internally fetching data without exposing a public endpoint.

👉🏽 [“Responsible WordPress Public API” Bundle](https://gatographql.com/bundles/responsible-wordpress-public-api/): Enhance your public APIs with additional layers of security, speed, power, schema evolution and control.

👉🏽 [“Selective Content Import, Export & Sync for WordPress” Bundle](https://gatographql.com/bundles/selective-content-import-export-and-sync-for-wordpress/): Import hundreds of records into your WordPress site from another site or service (such as Google Sheets), and selectively export entries to another site.

👉🏽 [“Simplest WordPress Content Translation” Bundle](https://gatographql.com/bundles/simplest-wordpress-content-translation/): Translate your content into over 130 languages using the Google Translate API, without adding extra tables or inner joins to the DB.

👉🏽 [“Tailored WordPress Automator” Bundle](https://gatographql.com/bundles/tailored-wordpress-automator/): Create workflows to automate tasks (to transform data, automatically caption images, send notifications, and more).

👉🏽 [“Unhindered WordPress Email Notifications” Bundle](https://gatographql.com/bundles/unhindered-wordpress-email-notifications/): Send personalized emails to all your users, and notifications to the admin, without constraints on what data can be added to the email.

👉🏽 [“Versatile WordPress Request API” Bundle](https://gatographql.com/bundles/versatile-wordpress-request-api/): Interact with any external API and cloud service, posting and fetching data to/from them.

The Gato GraphQL team is continually working on new extensions and bundles. If you need an integration with some WordPress plugin, or some missing functionality, [let us know](https://gatographql.com/contact/) and we'll work on it.

== Source code ==

The source code for the plugin is in GitHub repo [GatoGraphQL/GatoGraphQL](https://github.com/GatoGraphQL/GatoGraphQL).

The JavaScript source code for the blocks is under [layers/GatoGraphQLForWP/plugins/gatographql/blocks](https://github.com/GatoGraphQL/GatoGraphQL/tree/master/layers/GatoGraphQLForWP/plugins/gatographql/blocks).

== Frequently Asked Questions ==

= Can I extend the GraphQL schema with my custom types and fields? =

Yes you can. Use the GitHub template [GatoGraphQL/ExtensionStarter](https://github.com/GatoGraphQL/ExtensionStarter) to create an extension, and follow the documentation there.

= How does Gato GraphQL complement WP-CLI? =

With Gato GraphQL you can query data from the WordPress database, and then inject the results into a WP-CLI command (either to select a specific resource, or update an option with some value, or other).

Check out tutorial lesson [Complementing WP-CLI](https://gatographql.com/guides/code/complementing-wp-cli/) for a thorough description on how to do it.

= How do I use Gato GraphQL to build headless sites? =

With Gato GraphQL you can create an endpoint that exposes the data from your WordPress site. Then, within some framework (such as [Next.js](https://nextjs.org/) or others) you can query the data and render the HTML using CSR (client-side rendering).

= How do I fetch Gutenberg block data with Gato GraphQL? =

Check guide [Working with (Gutenberg) blocks](https://gatographql.com/guides/interact/working-with-gutenberg-blocks/) for the different ways in which we can query block data, and guide [Mapping JS components to (Gutenberg) blocks](https://gatographql.com/guides/code/mapping-js-components-to-gutenberg-blocks/) for an example.

= How do I use Gato GraphQL to feed data to my Gutenberg blocks? =

Check tutorial lesson [Feeding data to blocks in the editor](https://gatographql.com/guides/code/feeding-data-to-blocks-in-the-editor/) to learn how to create a private endpoint and have the block connect to it via JavaScript.

= How is Gato GraphQL different than the WP REST API? =

With the WP REST API, you expose data via REST endpoints, created via PHP code. Each endpoint has its own URL, and its data is pre-defined (for the corresponding resources, such as posts, users, etc).

Gato GraphQL supports "Persisted Queries", which are also endpoints with pre-defined data, however these can be created and published directly within the wp-admin, using GraphQL language (and without any PHP code).

In addition, with Gato GraphQL you can execute tailored GraphQL queries against an endpoint, indicating what specific data you need, and fetching only that. As a result, you can retrieve all needed data in a single request.

= Can I use Gato GraphQL to migrate my site? =

With Gato GraphQL you can execute queries to update data in your database, converting content into what is required for the new site.

For instance, you can execute a query that replaces "https://my-old-domain.com" with "https://my-new-domain.com" in the content of all posts (even within Gutenberg block properties).

Check tutorial lesson [Site migrations](https://gatographql.com/tutorial/site-migrations/) to learn how to do this.

= Can Persisted Queries be used as webhooks? =

Yes they can, because Persisted Queries are exposed under their own URL, they can extract the payload data, and then do something with that data (update a post, add a comment, send a notification, etc).

Check tutorial lesson [Interacting with external services via webhooks](https://gatographql.com/tutorial/interacting-with-external-services-via-webhooks/) to learn how to do this.

= Can I use Gato GraphQL to interact with external services? =

Yes, you can. Check tutorial lesson [Retrieving data from an external API](https://gatographql.com/tutorial/retrieving-data-from-an-external-api/) to see examples on how to do this.

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

= 1.6.0 =
* Added new module Media Mutations
* Added mutation `createMediaItem`
* Added fields `myMediaItemCount`, `myMediaItems` and `myMediaItem`
* Added predefined persisted query "Generate a post's featured image using AI and optimize it"
* Added documentation for new field `_dataMatrixOutputAsCSV` from the Helper Function Collection extension
* Validate the license keys when updating the plugin
* Simplified the Tutorial section
* Prevent max execution time issues when installing plugin on (cheap) shared hosting (#2631)
* Fixed bug where a syntax error on a variable definition in the GraphQL query was not validated

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

