=== Gato GraphQL ===
Contributors: gatographql, leoloso
Tags: decoupled, GraphQL, headless, webhook, api, wp-cli, rest, rest-api, react, astro, wpgraphql, Next.js
Requires at least: 6.1
Tested up to: 6.7
Stable tag: 11.1.2
Requires PHP: 8.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Powerful and flexible GraphQL server for WordPress. Access any piece of data (posts, users, comments, tags, etc) from your app via a GraphQL API.

== Description ==

Gato GraphQL is a powerful and flexible GraphQL server for WordPress.

Use it to expose WordPress data via GraphQL. Access any piece of data (posts, users, comments, tags, categories, etc) from your application, and also transform and mutate data.

The [standard use cases](https://gatographql.com) are:

**Code performant apps:** Send a GraphQL query to your API and get exactly what you need, nothing more and nothing less.

**Build dynamic/headless sites:** Use WordPress as the CMS to manage data, and your framework of choice to render the site.

**Speed up creating Gutenberg blocks:** Ship Gutenberg blocks faster, by avoiding creating REST controllers to feed them data.

With Gato GraphQL, you also have the flexibility to migrate your application from WordPress to another PHP-based framework or CMS (if ever needed) with minimal effort: The GraphQL server can run via its standalone PHP component (which doesn't depend on WordPress), and only those resolvers fetching WordPress data (posts, users, comments, etc) used by your application would need to be ported. You can do **Headless WordPress without WordPress**, avoiding the vendor lock-in to WordPress.

== Power Extensions ==

The [Power Extensions](https://gatographql.com/extensions#power-extensions) allow you to augment the server functionality.

[youtube https://www.youtube.com/watch?v=d-Ap9axT7kE]

You can purchase a [bundle containing all the power extensions](https://gatographql.com/pricing#power-extensions).

The available power extensions are:

[Access Control](https://gatographql.com/extensions/access-control): Grant granular access to the schema (based on the user being logged-in, having some role or capability, or by IP), to manage who can access what data.

[Caching](https://gatographql.com/extensions/caching): Make your application faster by providing HTTP Caching for the GraphQL response, and by caching the results of expensive operations.

[Custom Endpoints](https://gatographql.com/extensions/custom-endpoints): Create and expose multiple custom GraphQL schemas under their own URL, for different users, applications, external services, or other.

[Deprecation](https://gatographql.com/extensions/deprecation): Evolve the GraphQL schema by deprecating fields, and explaining how to replace them, through a user interface.

[HTTP Client](https://gatographql.com/extensions/http-client): Connect to and interact with external services via their APIs.

[Internal GraphQL Server](https://gatographql.com/extensions/internal-graphql-server): Execute GraphQL queries directly within your application, using PHP code.

[Multiple Query Execution](https://gatographql.com/extensions/multiple-query-execution): Combine multiple queries into a single query, sharing state across them and executing them in the requested order.

[Persisted Queries](https://gatographql.com/extensions/persisted-queries): Use GraphQL queries to create pre-defined endpoints as in REST, obtaining the benefits from both APIs.

[Query Functions](https://gatographql.com/extensions/query-functions): Manipulate the values of fields within the GraphQL query, via a collection of utilities and special directives providing meta-programming capabilities.

[Schema Functions](https://gatographql.com/extensions/schema-functions): The GraphQL schema is provided with fields and directives which expose functionalities from the PHP programming language.

== Premium Extensions ==

[Premium Extensions](https://gatographql.com/extensions#premium-extensions) allow you to integrate popular services and WordPress plugins into the GraphQL schema, and automate the execution of queries.

You can [purchase each premium extension separately](https://gatographql.com/pricing#premium-extensions).

The available premium extensions are:

[Automation](https://gatographql.com/extensions/automation): Use GraphQL to automate tasks in your app: Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron.

[Elementor](https://gatographql.com/extensions/elementor): Integration with the Elementor plugin, to parse and update data in Elementor pages and templates.

[Events Manager](https://gatographql.com/extensions/events-manager): Integration with the Events Manager plugin, to fetch event data.

[MultilingualPress](https://gatographql.com/extensions/multilingualpress): Integration with the MultilingualPress plugin, to fetch multilingual data.

[Polylang](https://gatographql.com/extensions/polylang): Integration with the Polylang plugin, adding fields and filters to select the language when fetching data on a multilingual site.

[Translation](https://gatographql.com/extensions/translation): Translate a field value using the service provider of your choice, among ChatGPT, Claude, DeepSeek, Mistral AI, DeepL, and Google Translate.

== Built with Gato GraphQL ==

All the plugins by [Gato Plugins](https://gatoplugins.com) use Gato GraphQL under the hook.

You can use Gato GraphQL to do everything offered by Gato Plugins!

The plugins are:

[Gato AI Translations for Polylang](https://gatoplugins.com/plugins/ai-translations-for-polylang): Automatically translate all content on your WordPress site, using the LLM of your choice: ChatGPT, Claude, DeepSeek, Mistral AI, DeepL, and Google Translate.

== Frequently Asked Questions ==

= Does the plugin provide documentation? =

The Gato GraphQL website contains extensive documentation:

- [Guides](https://gatographql.com/guides) on configuring and using the plugin
- [Extensions reference](https://gatographql.com/extensions-reference) docs
- [Queries library](https://gatographql.com/library) with examples of GraphQL queries for many use cases
- [Schema tutorial](https://gatographql.com/tutorial) to learn all the elements of the GraphQL schema

= Can I watch videos demonstrating the plugin? =

Sure you can. There are videos demonstrating how to use Gato GraphQL in:

- The [Demos](https://gatographql.com/demos) page on gatographql.com
- The [@GatoGraphQL YouTube channel](https://www.youtube.com/@GatoGraphQL)

= Can I extend the GraphQL schema with my custom types and fields? =

Yes you can. Use the GitHub template [GatoGraphQL/ExtensionStarter](https://github.com/GatoGraphQL/ExtensionStarter) to create an extension, and follow the documentation there.

= How does Gato GraphQL complement WP-CLI? =

With Gato GraphQL you can query data from the WordPress database, and then inject the results into a WP-CLI command (either to select a specific resource, or update an option with some value, or other).

Check out guide [Complementing WP-CLI](https://gatographql.com/guides/code/complementing-wp-cli) for a thorough description on how to do it.

= How do I use Gato GraphQL to build headless sites? =

With Gato GraphQL you can create an endpoint that exposes the data from your WordPress site. Then, within the framework of your choice, you can query the data and render the HTML.

Among others, you can use any of these libraries/frameworks:

- Apollo
- React
- Vue
- Next.js
- Nuxt
- Hugo
- Astro
- VitePress
- Svelte
- Eleventy

= Can I run Gato GraphQL without WordPress? =

Yes you can. Gato GraphQL can be included within any PHP-based application (not only WordPress), based on Laravel, Symfony, or any other.

Check guide [Running Gato GraphQL without WordPress](https://gatographql.com/guides/interact/running-gatographql-without-wordpress) for the explanation on how to do it.

= Can I fetch Gutenberg block data with Gato GraphQL? =

Yes you can. Check guide [Working with (Gutenberg) blocks](https://gatographql.com/guides/interact/working-with-gutenberg-blocks) for the different ways in which we can query block data, and guide [Mapping JS components to (Gutenberg) blocks](https://gatographql.com/guides/code/mapping-js-components-to-gutenberg-blocks) for an example.

= Does Gato GraphQL support WordPress Multisite? =

Yes, it does. Gato GraphQL can be installed on a multisite network.

In addition, Gato GraphQL provides fields on the GraphQL schema to retrieve multisite network data, allowing to use GraphQL to sync content across the multisite.

= How does Gato GraphQL compare against the WP REST API? =

Check out the [Gato GraphQL vs WP REST API comparison](https://gatographql.com/comparisons/gatographql-vs-wp-rest-api).

= How to replace the WP REST API with Gato GraphQL? =

Check out the [Replacing the WP REST API guide](https://gatographql.com/guides/intro/replacing-the-wp-rest-api-with-gatographql).

= How does Gato GraphQL compare against WPGraphQL? =

Check out the [Gato GraphQL vs WPGraphQL comparison](https://gatographql.com/comparisons/gatographql-vs-wpgraphql).

= How to replace WPGraphQL with Gato GraphQL? =

Check out the [Replacing WPGraphQL guide](https://gatographql.com/guides/intro/replacing-wpgraphql-with-gatographql).

= Can I migrate from WordPress to Laravel/Symfony/etc? =

Yes you can. Even more, only the WordPress-specific packages to fetch WordPress data (posts, users, comments, etc) used by your application would need to be ported to the new framework/CMS, and nothing else.

Check guide [Migrating your app from WordPress to another PHP framework or CMS](https://gatographql.com/guides/interact/migrating-gatographql-from-wordpress-to-another-php-framework-or-cms) to learn more.

= What are Power Extensions needed for? =

Power Extensions are sold in a bundle containing all of them. They are needed to enhance the security of public APIs, add HTTP caching to speed up the application, execute multiple queries in a single request, connect to external services, send emails, and others.

= What are Premium Extensions needed for? =

Premium Extensions are sold individually. Each extension provides some specific functionality, such as an integration with some specific WordPress plugin or external service, or the ability to automate tasks.

= Does Gato GraphQL have a refund policy? =

Yes it does. If you have purchased any Gato GraphQL product and found out that it's not the right tool to satisfy your needs, you have [30 days to ask for a refund](https://gatographql.com/refund-policy).

= Can I try out Gato GraphQL + all extensions? =

Yes, you can [launch your own sandbox site](https://app.instawp.io/launch?t=gatographql-demo&d=v2) to play with Gato GraphQL + all extensions, for free.

= Is the plugin open source? =

Yes it is. The source code for the plugin is in GitHub repo [GatoGraphQL/GatoGraphQL](https://github.com/GatoGraphQL/GatoGraphQL).

The JavaScript source code for the blocks is under [layers/GatoGraphQLForWP/plugins/gatographql/blocks](https://github.com/GatoGraphQL/GatoGraphQL/tree/master/layers/GatoGraphQLForWP/plugins/gatographql/blocks).

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

= 11.2.0 =
* Support fetching Page Builders data in the GraphQL schema (#3051)
* Added documentation for new field `_objectMerge`, from the **Schema Functions** extension (#3050)
 
= 11.1.2 =
* Support `password` type in multi-inputs in Settings (#3047)
* Use `password` type to activate extensions in Settings (#3048)
* Fixed: Hook `template_include` can receive `null` values (error may happen when WooCommerce is installed) (#3049)

= 11.0.4 =
* Fixed: Do not remove the Screen Options tab on the post list page (#3046)

= 11.0.0 =
* Important: If you have any extension installed (eg: the **“Power Extensions” bundle**), you need to update **Gato GraphQL** first! Otherwise the application will throw an exception.
* Breaking change: Register `changelogURL` for the extensions, to show the new version's changelog when updating them (#3042)
* Updated documentation for the **Translation** extension, adding new translation providers: **Mistral AI** (#3043) and **DeepSeek** (#3044)

= 10.5.0 =
* Enable multiple checkbox options in Settings (#3040)
* Fixed: If the GMT date is stored as `"0000-00-00 00:00:00"`, then use the non-GMT date (#3041)

= 10.4.0 =
* Added password input on Settings (#3035)
* Added `date` input on custom post mutations (`createPost`, `updatePost`, `createCustomPost`, and `updateCustomPost`) (#3036)
* Added `gmtDate` input on custom post mutations (`createPost`, `updatePost`, `createCustomPost`, and `updateCustomPost`) (#3038)
* Updated **Translation** extension docs with **Claude** as translation provider (#3039)
* Removed stale polyfill interface `DateTimeInterface` (for PHP 7.2) (#3037)

= 10.3.0 =
* Updated documentation for the **Response Error Trigger** module, adding the new `_warn` field and `@warn` directive (#3034)
* Use the smaller images between PNG and WEBP (#3031)

= 10.2.0 =
* Added documentation for new field `_generateRandomString` in the Helper Function Collection extension (#3027)
* Plugin updates: Use the same icon as the Gato GraphQL plugin for the extensions (#3022)
* Allow to install setup data every time that the plugin is updated (#3028)
* Convert all png images to webp (#3029)
* [Extensions][Helper Function Collection] Added field `_generateRandomString`
* [Extensions][Translation] Pass language + country code on `@strTranslate(to:)`

= 10.1.0 =
* Added YouTube video for the Automation extension doc (#93311e2)
* Completed documentation for extensions:
  * [Schema Functions] New fields `_arrayEncodeAsJSONString` and `_objectEncodeAsJSONString` (#3013)
  * [Helper Function Collection] New field `_arrayOfJSONObjectsExtractPropertiesAndConvertToObject` (#3018)
* Order tabs in Settings by priority (#3014)
* Added documentation for new extension "Translation" (replacing "Google Translate" and "DeepL") (#3016)
* Allow to create custom posts as `private` (#3019)
* Allow printing textarea options in Settings (#3020)
* Fixed: Exception when serializing an array value (#3017)
* [Extensions][Schema Functions] Added fields `_arrayEncodeAsJSONString` and `_objectEncodeAsJSONString`
* [Extensions][Helper Function Collection] Added field `_arrayOfJSONObjectsExtractPropertiesAndConvertToObject`
* [Extensions][Google Translate] Fixed: Make the `@strTranslate` directive send not more than 128 strings to translate

= 10.0.0 =
* Breaking changes: Changed signature of method `validateDirectiveArgValue`, passing the `$directiveArgs` param when validating a single directive arg (#2989)
* Breaking changes: Made params to method `assertIsValid` non-nullable (#2998)
* Added GraphQL type `StringListValueJSONObject` (#2991)
* Added YouTube videos to the extension docs (#9b95be5)
* Added documentation for Premium Extensions (#2994)
* Settings: For entries of the "Key => Label" type (eg: Extension license keys), allow to use a select input to print preselected values (#2988)
* Fixed: Container not generated properly in certain hosts (`@required` attribute disregarded) (#3009)
* Fixed: Use the Gutenberg Editor for the plugin CPTs (when Classic Editor active) (#3011)

= 9.0.0 =
* Breaking change: Changed signature of method `assertCommercialLicenseHasBeenActivated` (#2978)
* Only register block JS scripts when in allowed CPT (#2975)
* Enable updating extensions from Plugins page after major release (#2978)
* Improve performance when caching resolvers for a queried field (#2981)
* Fixed: Catch exception from SymfonyDI on `admin_init` hook (#2974)
* Fixed: Show "Visit plugin site" link instead of "View details" for extensions (#2976)
* Fixed "Deprecated: Calling get_parent_class() without arguments is deprecated" (#2977)
* Fixed Elementor not loading widgets with Gato GraphQL active (#2986)
* [Extensions][Helper Function Collection] Added field `_arrayFlatten`

= 8.0.0 =
* Extensions (eg: the "All Extensions" bundle) can now be updated from the Plugins page (#2972)

= 7.0.8 =
* Fixed: Don't hardcode Gato GraphQL's plugin base name in `plugin_action_links` hook to allow standalone plugins to use it (#2971)

= 7.0.5 =
* When an extension is activated, execute `flush_rewrite_rules` only at the end (or CPTs are not loaded properly) (#2970)

= 7.0.4 =
* Do not print block editor scripts in the frontend (#2966)
* Do not print block editor stylesheets in the frontend (#2967)

= 7.0.3 =
* Allow method handling hook `allowed_block_types_all` to receive `null` (#2965)

= 7.0.2 =
* Changed label in Settings form button to "Save Changes (all from this tab)"

= 7.0.1 =
* Renamed "Gato GraphQL Shop" to "Gato Shop"

= 7.0.0 =
* Breaking change: Bump minimum required PHP version to 7.4 (#2905)
* Breaking change: Allow to include Gato GraphQL as the engine to power another standalone plugin (#2897)
* Breaking change: Renamed env var `CACHE_DIR` to `CONTAINER_CACHE_DIR` (#2923)
* Added convenience class for standalone plugins (#2899)
* Allow to fetch posts with `auto-draft` status (#2911)
* Allow disabling the private endpoint (#2913)
* Added field `useGutenbergEditorWithCustomPostType` (#2960)
* Fixed: Fetching raw attribute sources with multiple nodes in blocks (#2909)
* [Extensions][Persisted Queries] Created a new "Persisted Query Endpoints" module (from "Persisted Queries"), to disable external execution of persisted queries

= 6.0.2 =
* Fixed: Remove global fields from schema when disabled via ACL (#2894)
* Fixed: Global fields duplicated in schema with nested mutations enabled (#2895)

= 6.0.0 =
* Action required: When updating the plugin (i.e. not installing anew), you need to deactivate and then re-activate the plugin. Until then, the "GraphiQL" and "Schema" items won't appear on the menu (due to the newly-added "Schema Editing Access" module, see below)
* Breaking change: Removed custom endpoints and persisted queries (#2852)
* Breaking change: The single endpoint is enabled by default (#2859)
* Breaking change: The single endpoint GraphiQL/Voyager clients are disabled default (#2860)
* Breaking change: The Schema Configuration module is disabled by default (#2848)
* Breaking change: The schema tutorial page is hidden by default (#2856)
* Breaking change: On the settings page, the configuration for items under "Schema Elements Configuration" need to be set again (#2861)
* Tested up to WordPress 6.7 (#2887)
* Do not display Endpoint Categories if there are no endpoint CPTs enabled (#2849)
* Hide "API Hierarchy" module if there are no endpoint CPTs enabled (#2850)
* Hide "Excerpt as description" module if there are no CPTs enabled (#2851)
* Display the "Enable Logs?" settings only when some extension is using it (#2853)
* Hide the Schema tutorial page by default (#2854)
* Reorganized the Settings, splitting "Schema Configuration" into 2 elements: "Schema Configuration" and  "Schema Elements Configuration" (#2861)
* Improved documentation for extensions (#2866)
* Added links to online docs on the Settings page (#2875)
* Added "Schema Editing Access" module (#2877)
* [Extensions][Schema Functions] If `from` email not provided in `_sendEmail` mutation, use the blog's admin email

= 5.0.0 =
* Breaking change: Bumped minimum WordPress version to 6.1 (#2811)
* Breaking change: Return no results when filtering data by an empty array (#2809)
* Increase limit of chars in truncated response by Guzzle (#2800)
* Added field `isGutenbergEditorEnabled` (#2801)
* Use `isGutenbergEditorEnabled` in predefined persisted queries (#2802)
* Added mutations to assign custom tags/categories to custom posts (#2803)
* Added Settings option to enable/disable logs (#2813)
* Application password failed authentication: Show error in GraphQL response (#2817)
* Added predefined persisted queries:
  * [PRO] Import post from WordPress RSS feed and rewrite its content with ChatGPT (#2818)
  * [PRO] Import new posts from WordPress RSS feed (#2819)
  * [PRO] Import HTML from URLs as new posts in WordPress (#2822)
* Support additional taxonomies for mutations on post tags/categories (not only `post_tag` and `category`) (#2823)
* Added taxonomy field also to `PostTag` and `PostCategory` types (#2824)
* Made taxonomy input not mandatory on `Root.tags/categories` and `CustomPost.tags/categories` fields (#2827)
* Fixed: Add `featuredImage` field on `GenericCustomPost` (#2806)
* Fixed: On fields `blocks`, `blockDataItems`, and `blockFlattenedDataItems`, avoid error when post has no content (#2814)
* Fixed: Pass mandatory `attrs` field when creating persisted query blocks (#3adde2e)
* [PRO] Updated mapped WordPress hooks for automation

= 4.2.0 =
* Added mutations for categories (#2764)
* Added mutations for tags (#2765)
* Validate `assign_terms` capability on `setCategory` and `setTag` mutations (#2772)
* Create a media item using the attachment from an existing media item (#2787)
* Added field `Media.parentCustomPost` (#2788)
* Added mutation `Root.updateMediaItem` (#2790)
* Added predefined persisted queries:
  * Create missing translation categories for Polylang (#2774)
  * Create missing translation tags for Polylang (#2774)
  * Translate categories for Polylang (#2774)
  * Translate tags for Polylang (#2774)
  * Translate categories for MultilingualPress (#2774)
  * Translate tags for MultilingualPress (#2774)
  * Create missing translation media for Polylang (#2789)
  * Translate media for Polylang (#2789)
* Added translation language mapping to persisted queries (#2775)
* Fixed exception when initializing the GraphQL Internal Server query on `add_attachment` (#2796)
* [PRO] Define the Polylang language on tag and category mutations
* [PRO] Automation: Store the GraphQL response in the info logs
* [PRO] Added Polylang Mutations for Media Items
* [PRO] Map additional WordPress hooks for Automation
* [PRO] Filter entities by Polylang's default language

= 4.1.0 =
* Send the referer on Guzzle requests (#2754)
* Use `@strQuoteRegex` in predefined persisted queries (#2758)
* [PRO] Polylang: Filter data by language
* [PRO] Use enums types to return Polylang language codes, locales and names
* [PRO] Automation: Handle `new` and `auto-draft` old status in `{$old_status}_to_{$new_status}` hook (#1376)
* [PRO] Predefined automation rules: In addition to `draft_to_publish`, also trigger from `new`, `auto-draft`, `pending`, `future`, and `private` states
* [PRO] Added field `_strQuoteRegex` and directive `@strQuoteRegex`
* [PRO] Fixed: Plugin throwing exception on PHP 7.2

= 4.0.0 =
* Breaking change: Updated internal PHP hook structure for error payloads (#2739)
* Use bulk mutations in predefined persisted queries (#2728)
* Added documentation for new PRO module **Polylang Mutations** (#2733)
* Added documentation for new PRO field `_arrayIntersect` (#2735)
* Added predefined persisted query:
  * [PRO] Create missing translation posts for Polylang (#2740)
* Fixed: Don't replace chars in translation persisted queries (#2731)
* Fixed: "Call to protected method" exception in PHP 8.2 (#2741)
* [PRO] Breaking change: Rename field `_intSubstract` to `_intSubtract`
* [PRO] Breaking change: Rename directive `@intSubstract` to `@intSubtract`
* [PRO] Breaking change: Renamed field `polylangEnabledLanguages` to `polylangLanguages`
* [PRO] Breaking change: Fields `polylangLanguage`, `polylangDefaultLanguage` and `polylangLanguages` return type `PolylangLanguage` instead of `String`
* [PRO] Breaking change: Renamed inputs `language` to `languageBy` and `polylangLanguage` to `polylangLanguageBy`
* [PRO] Added **Polylang Mutations**
* [PRO] Added field `_arrayIntersect`

= 3.0.0 =
* Breaking change: Require at least WordPress v6.0 (#2719)
* Breaking change: Option "Do not use payload types for mutations (i.e. return the mutated entity)" in schema configuration block "Payload Types for Mutations" must be re-selected (#2720)
* Added compatibility with WordPress 6.6 (#2717)
* Added bulk mutation fields (for all mutations in the schema) (#2721)
* Added fields to query the mutation payload objects (#2720)
* Added option to schema configuration block "Payload Types for Mutations" (#2720)
* Added predefined custom endpoint "Bulk mutations" (#2720)
* Removed predefined custom endpoint "Nested mutations + Entity as mutation payload type" (#2720)
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
* Renamed existing bundles (“Application Glue & Automator” bundle => “Tailored WordPress Automator” bundle, “Content Translation” bundle => “Simplest WordPress Content Translation” bundle and “Public API” bundle => “Responsible WordPress Public API” bundle)
* Added documentation for new bundles (“Automated Content Translation & Sync for WordPress Multisite” bundle, “Better WordPress Webhooks” bundle, “Easy WordPress Bulk Transform & Update” bundle, “Private GraphQL Server for WordPress” bundle, “Selective Content Import, Export & Sync for WordPress” bundle, “Unhindered WordPress Email Notifications” bundle and “Versatile WordPress Request API” bundle)
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

