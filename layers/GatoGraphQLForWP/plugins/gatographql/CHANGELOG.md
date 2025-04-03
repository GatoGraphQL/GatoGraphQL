# Changelog

All notable changes to `gatographql` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 12.0.0 - DATE

### Added

- Documentation for new Elementor Premium Extension (#3052)

### Breaking changes

- Plugin constructor signature receives nullable `commitHash` param (#3056)
- Store the extension names whose license has just been activated (instead of a timestamp) to install setup data (#3057)
- Renamed `getCommentPostID` to `getCommentCustomPostID` (#3073)

## 11.3.0 - DATE

### Added

- Fields `meta: ListValueJSONObject!` and `metaKeys: [String!]!` for types `Comment/CustomPost/TaxonomyTerm/User` (#3060)
- Meta mutations
  - Custom posts (#3067)
  - Categories (#3063)
  - Tags (#3064)
  - Users (#3072)
  - Comments (#3072)
- Type `ListValueJSONObject` (#3060)
- Documentation for new field `_objectRecursiveReplace`, from the **Schema Functions** extension (#3074)

### Improvements

- Made meta field `metaValue` handle any scalar type (previously only `String`) (#3061)
- Made meta field `metaValues` handle any scalar type (previously only built-in ones), such as `JSONObject` (#3061)
- Allow to hook inputs into tag/category mutations (#3062)

### Fixed

- Passing a non-`post` CPT to `updatePost` will show an error (#3070)

## 11.2.0 - 15/03/2025

### Added

- Support fetching Page Builders data in the GraphQL schema (#3051)
- Documentation for new field `_objectMerge`, from the **Schema Functions** extension (#3050)

### Fixed

- Malcare deactivate triggers Exception (#3054)
- Install plugin setup data on first request after activating license (#3055)

## 11.1.2 - 07/03/2025

### Fixed

- Hook `template_include` can receive `null` values (error may happen when WooCommerce is installed) (#3049)

### Improvements

- Support `password` type in multi-inputs in Settings (#3047)
- Use `password` type to activate extensions in Settings (#3048)

## 11.1.0 - 03/03/2025

(Empty)

## 11.0.4 - 03/03/2025

### Fixed

- Do not remove the Screen Options tab on the post list page (#3046)

## 11.0.0 - 27/02/2025

### Required actions

- **Important:** If you have any extension installed (eg: the **“Power Extensions” bundle**), you need to update **Gato GraphQL** first! Otherwise the application will throw an exception.

### Breaking changes

- Register `changelogURL` for the extensions, to show the new version's changelog when updating them (#3042)

### Added

- Updated documentation for the **Translation** extension, adding new translation providers:
  - **Mistral AI** (#3043)
  - **DeepSeek** (#3044)

## 10.5.0 - 25/02/2025

### Improvements

- Enable multiple checkbox options in Settings (#3040)

### Fixed

- If the GMT date is stored as `"0000-00-00 00:00:00"`, then use the non-GMT date (#3041)

## 10.4.0 - 18/02/2025

### Added

- Added password input on Settings (#3035)
- Added `date` input on custom post mutations (`createPost`, `updatePost`, `createCustomPost`, and `updateCustomPost`) (#3036)
- Added `gmtDate` input on custom post mutations (`createPost`, `updatePost`, `createCustomPost`, and `updateCustomPost`) (#3038)
- Updated **Translation** extension docs with **Claude** as translation provider (#3039)

### Improvements

- Removed stale polyfill interface `DateTimeInterface` (for PHP 7.2) (#3037)

## 10.3.1 - 15/02/2025

### Fixed

- Deploy plugin to wp.org (#bb5414d)

## 10.3.0 - 14/02/2025

### Added

- Updated documentation for the **Response Error Trigger** module, adding the new `_warn` field and `@warn` directive (#3034)

### Improvements

- Use the smaller images between PNG and WEBP (#3031)

## 10.2.0 - 07/02/2025

### Added

- Documentation for new field `_generateRandomString` in the Helper Function Collection extension (#3027)

### Improvements

- Plugin updates: Use the same icon as the Gato GraphQL plugin for the extensions (#3022)
- Allow to install setup data every time that the plugin is updated (#3028)
- Convert all png images to webp (#3029)

## 10.1.0 - 08/01/2025

### Added

- YouTube video for the Automation extension doc (#93311e2)
- Documentation for new "Schema Functions extension" fields `_arrayEncodeAsJSONString` and `_objectEncodeAsJSONString` (#3013)
- Order tabs in Settings by priority (#3014)
- Documentation for new extension "Translation" (replacing "Google Translate" and "DeepL") (#3016)

### Improvements

- Allow to create custom posts as `private` (#3019)
- Allow printing textarea options in Settings (#3020)

### Fixed

- Exception when serializing an array value (#3017)

## 10.0.0 - 30/12/2024

### Breaking changes

- Changed signature of method `validateDirectiveArgValue`, passing the `$directiveArgs` param when validating a single directive arg (#2989)
- Made params to method `assertIsValid` non-nullable (#2998)

### Added

- GraphQL type `StringListValueJSONObject` (#2991)
- YouTube videos to the extension docs (#9b95be5)
- Documentation for Premium Extensions (#2994)

### Improvements

- Settings: For entries of the "Key => Label" type (eg: Extension license keys), allow to use a select input to print preselected values (#2988)

### Fixed

- Container not generated properly in certain hosts (`@required` attribute disregarded) (#3009)
- Use the Gutenberg Editor for the plugin CPTs (when Classic Editor active) (#3011)

## 9.0.0 - 16/12/2024

### Breaking changes

- Changed signature of method `assertCommercialLicenseHasBeenActivated` (#2978)

### Improvements

- Only register block JS scripts when in allowed CPT (#2975)
- Enable updating extensions from Plugins page after major release (#2978)
- Improve performance when caching resolvers for a queried field (#2981)

### Fixed

- Catch exception from SymfonyDI on `admin_init` hook (#2974)
- Show "Visit plugin site" link instead of "View details" for extensions (#2976)
- Fixed "Deprecated: Calling get_parent_class() without arguments is deprecated" (#2977)
- Elementor not loading widgets with Gato GraphQL active (#2986)

## 8.0.0 - 30/11/2024

### Improvements

- Extensions (eg: the "All Extensions" bundle) can now be updated from the Plugins page (#2972)

## 7.0.8 - 28/11/2024

### Fixed

- Don't hardcode Gato GraphQL's plugin base name in `plugin_action_links` hook to allow standalone plugins to use it (#2971)

## 7.0.5 - 25/11/2024

### Fixed

- When an extension is activated, execute `flush_rewrite_rules` only at the end (or CPTs are not loaded properly) (#2970)

## 7.0.4 - 20/11/2024

### Fixed

- Do not print block editor scripts in the frontend (#2966)
- Do not print block editor stylesheets in the frontend (#2967)

## 7.0.3 - 15/11/2024

### Fixed

- Allow method handling hook `allowed_block_types_all` to receive `null` (#2965)

## 7.0.2 - 13/11/2024

### Fixed

- Changed label in Settings form button to "Save Changes (all from this tab)"

## 7.0.1 - 11/11/2024

### Fixed

- Renamed "Gato GraphQL Shop" to "Gato Shop"

## 7.0.0 - 08/11/2024

### Breaking changes

- Bump minimum required PHP version to 7.4 (#2905)
- Allow to include Gato GraphQL as the engine to power another standalone plugin (#2897)
- Renamed env var `CACHE_DIR` to `CONTAINER_CACHE_DIR` (#2923)

### Improvements

- Added convenience class for standalone plugins (#2899)
- Allow to fetch posts with `auto-draft` status (#2911)
- Allow disabling the private endpoint (#2913)
- Added field `useGutenbergEditorWithCustomPostType` (#2960)

### Fixed

- Fetching raw attribute sources with multiple nodes in blocks (#2909)

## 6.0.2 - 11/10/2024

### Fixed

- Remove global fields from schema when disabled via ACL (#2894)
- Global fields duplicated in schema with nested mutations enabled (#2895)

## 6.0.0 - 06/10/2024

### Breaking changes

**Action required:** When updating the plugin (i.e. not installing anew), you need to deactivate and then re-activate the plugin. Until then, the "GraphiQL" and "Schema" items won't appear on the menu (due to the newly-added "Schema Editing Access" module, see below)

- Removed custom endpoints and persisted queries (#2852)
- The single endpoint is enabled by default (#2859)
- The single endpoint GraphiQL/Voyager clients are disabled default (#2860)
- The Schema Configuration module is disabled by default (#2848)
- The schema tutorial page is hidden by default (#2856)
- On the settings page, the configuration for items under "Schema Elements Configuration" need to be set again (#2861)

### Improvements

- Tested up to WordPress 6.7 (#2887)
- Do not display Endpoint Categories if there are no endpoint CPTs enabled (#2849)
- Hide "API Hierarchy" module if there are no endpoint CPTs enabled (#2850)
- Hide "Excerpt as description" module if there are no CPTs enabled (#2851)
- Display the "Enable Logs?" settings only when some extension is using it (#2853)
- Hide the Schema tutorial page by default (#2854)
- Reorganized the Settings, splitting "Schema Configuration" into 2 elements: "Schema Configuration" and  "Schema Elements Configuration" (#2861)
- Improved documentation for extensions (#2866)
- Added links to online docs on the Settings page (#2875)
- Added "Schema Editing Access" module (#2877)

## 5.0.0 - 07/09/2024

### Breaking changes

- Breaking change: Bumped minimum WordPress version to 6.1 (#2811)
- Return no results when filtering data by an empty array (#2809)

### Improvements

- Increase limit of chars in truncated response by Guzzle (#2800)
- Added field `isGutenbergEditorEnabled` (#2801)
- Use `isGutenbergEditorEnabled` in predefined persisted queries (#2802)
- Added mutations to assign custom tags/categories to custom posts (#2803)
- Added Settings option to enable/disable logs (#2813)
- Application password failed authentication: Show error in GraphQL response (#2817)
- Added predefined persisted queries:
  - [PRO] Import post from WordPress RSS feed and rewrite its content with ChatGPT (#2818)
  - [PRO] Import new posts from WordPress RSS feed (#2819)
  - [PRO] Import HTML from URLs as new posts in WordPress (#2822)
- Support additional taxonomies for mutations on post tags/categories (not only `post_tag` and `category`) (#2823)
- Added taxonomy field also to `PostTag` and `PostCategory` types (#2824)
- Made taxonomy input not mandatory on `Root.tags/categories` and `CustomPost.tags/categories` fields (#2827)

### Fixed

- Add `featuredImage` field on `GenericCustomPost` (#2806)
- On fields `blocks`, `blockDataItems`, and `blockFlattenedDataItems`, avoid error when post has no content (#2814)
- Pass mandatory `attrs` field when creating persisted query blocks (#3adde2e)

## 4.2.0 - 21/08/2024

### Improvements

- Added mutations for categories (#2764)
- Added mutations for tags (#2765)
- Validate `assign_terms` capability on `setCategory` and `setTag` mutations (#2772)
- Create a media item using the attachment from an existing media item (#2787)
- Added field `Media.parentCustomPost` (#2788)
- Added mutation `Root.updateMediaItem` (#2790)
- Added predefined persisted queries:
  - [PRO] Create missing translation categories for Polylang (#2774)
  - [PRO] Translate categories for Polylang (#2774)
  - [PRO] Translate tags for Polylang (#2774)
  - [PRO] Create missing translation tags for Polylang (#2774)
  - [PRO] Translate categories for MultilingualPress (#2774)
  - [PRO] Translate tags for MultilingualPress (#2774)
  - [PRO] Create missing translation media for Polylang (#2789)
  - [PRO] Translate media for Polylang (#2789)
- Added translation language mapping to persisted queries (#2775)

### Fixed

- Exception when initializing the GraphQL Internal Server query on `add_attachment` (#2796)

## 4.1.0 - 26/07/2024

### Improvements

- Send the referer on Guzzle requests (#2754)
- Use `@strQuoteRegex` in predefined persisted queries (#2758)

## 4.0.0 - 24/07/2024

### Breaking changes

- Updated internal PHP hook structure for error payloads (#2739)

### Improvements

- Use bulk mutations in predefined persisted queries (#2728)
- Added documentation for new PRO module **Polylang Mutations** (#2733)
- Added documentation for new PRO field `_arrayIntersect` (#2735)
- Added predefined persisted query:
  - [PRO] Create missing translation posts for Polylang (#2740)

### Fixed

- Don't replace chars in translation persisted queries (#2731)
- "Call to protected method" exception in PHP 8.2 (#2741)

## 3.0.0 - 10/07/2024

### Breaking changes

- Require at least WordPress v6.0 (#2719)
- Option "Do not use payload types for mutations (i.e. return the mutated entity)" in schema configuration block "Payload Types for Mutations" must be re-selected (#2720)

### Improvements

- Added compatibility with WordPress 6.6 (#2717)
- Added bulk mutation fields (for all mutations in the schema) (#2721)
- Added fields to query the mutation payload objects (#2720)
- Added option to schema configuration block "Payload Types for Mutations" (#2720)
- Added predefined custom endpoint "Bulk mutations" (#2720)
- Removed predefined custom endpoint "Nested mutations + Entity as mutation payload type" (#2720)

### Fixed

- Catch exception if dependency version is not semver (#2712)
- Convert entries in JSON dictionary of variables in persisted query from array to object (#2715)

## 2.6.0 - 01/07/2024

### Improvements

- Added `siteURL` field (#2697)
- Added fields to fetch multisite data (#2698)
- Added documentation for PRO integration with MultilingualPress (#2699)
- Added documentation for new PRO field `_strRegexFindMatches` (#2708)
- Added GraphQL variables `$translateFromLanguage`, `$includeLanguagesToTranslate` and `$excludeLanguagesToTranslate` to persisted queries (#2694 / #2700):
  - [PRO] Translate posts for Polylang (Gutenberg)
  - [PRO] Translate posts for Polylang (Classic editor)
- Added scalar types to the GraphQL schema:
  - `IntValueJSONObject` (#2703)
  - `IDValueJSONObject` (#2704)
- Added predefined persisted queries:
  - [PRO] Translate posts for MultilingualPress (Gutenberg) (#2706)
  - [PRO] Translate posts for MultilingualPress (Classic editor) (#2706)
  - [PRO] Translate Poedit file content (#2709)
- Added Gato GraphQL intro video to documentation (#2707)

### Fixed

- Identifying extension in `createMediaItem` when filename has more then one dot

### [PRO] Improvements

- Added input `valueWhenNonExistingKeyOrPath` to field `_objectProperty`
- Added integration with MultilingualPress
- Added automation rules:
  - MultilingualPress: When publishing a post, translate it to all languages (Gutenberg)
  - MultilingualPress: When publishing a post, translate it to all languages (Classic editor)
- Added field `_strRegexFindMatches`

## 2.5.2 - 06/06/2024

### Fixed

- Initialize blocks only after their corresponding CPTs (#2693)

## 2.5.1 - 05/06/2024

### Fixed

- Fixed tabs in Markdown in new persisted query **[PRO] Send email to users about post**

## 2.5.0 - 05/06/2024

### Improvements

- Added predefined persisted queries
  - [PRO] Send email to users about post (#2692)
- Added documentation for "WordPress hook mapping" for the [PRO] Automation extension (#2691)

## 2.4.0 - 23/05/2024

### Improvements

- Added page mutations to the GraphQL schema (#2682)
- Added fields to fetch the logged-in user's pages (#2682)
- Added fields to fetch the site's locale and language (#2685)
- Install "internal" private custom endpoint (#2684)
- Support Application Passwords (#2672)
- Added documentation for new PRO field `_strBase64Encode` (#2673)
- Link extensions to the Extensions Reference in gatographql.com (#2675)
- Added YouTube channel link to About page (#2676)
- Added predefined persisted queries:
  - [PRO] Translate and create all pages for a multilingual site (Multisite / Gutenberg) (#2688)
  - [PRO] Translate and create all pages for a multilingual site (Multisite / Classic editor) (#2688)
  
### Fixed

- Open GraphiQL/Voyager clients in subfolder-based Multisite network (#2677)
- Highlight extensions and enable link to visit in website (#2674)
- GraphiQL client (for LocalWP) now uses site URL as endpoint (#2686)
- Internal server error from passing string when expected int (v2.4.1)

## 2.3.0 - 08/05/2024

### Improvements

- Added fields `GenericCustomPost.update`, `Root.updateCustomPost` and `Root.createCustomPost` (#2663)
- Added documentation for integration with Polylang (#2664)
- Added module type "Integrations" (#2665)
- Return an EnumString type on `GenericCategory.taxonomy` and `GenericTag.taxonomy` (#2666)
- Added predefined persisted queries:
  - [PRO] Translate posts for Polylang (Gutenberg) (#2667)
  - [PRO] Translate posts for Polylang (Classic editor) (#2667)
  - [PRO] Sync featured image for Polylang (#2669)
  - [PRO] Sync tags and categories for Polylang (#2670)
- Support alternative filenames from 3rd-party plugins for extensions (#2671)

### Fixed

- Updated the Support form's action URL (#2662)

### [PRO] Improvements

- Added integration with Polylang
- Added automation rules:
  - Polylang: When publishing a post, translate it to all languages (Gutenberg)
  - Polylang: When publishing a post, translate it to all languages (Classic editor)
  - Polylang: When publishing a post, set the featured image for each language on all translation posts
  - Polylang: When publishing a post, set the tags and categories for each language on all translation posts

## 2.2.3 - 26/04/2024

### Fixed

- Bug parsing `@export(as: $someVar)` (#2661)

## 2.2.2 - 27/03/2024

### Improvements

- Adapted `blocks` field to work with WordPress 6.5 (#2657)
- Tested up WordPress 6.5
- Renamed "Tutorial" to "Schema tutorial"

## 2.2.1 - 14/03/2024

### Improvements

- Added "Lesson (number): " in the tutorials

## 2.2.0 - 06/03/2024

### Improvements

- Do not include bundles in the Extensions page
- Do not print the required extensions on predefined persisted queries

## 2.1.3 - 16/02/2024

### Improvements

- Added documentation for fields `_arrayFlipToObject` and `_objectIntersectKey` from the PHP Functions via Schema extension
- Added documentation for field `_arrayOfJSONObjectsExtractProperty` from the Helper Function Collection extension

## 2.1.0 - 15/02/2024

### Added

- Support providing the Schema Configuration to apply when invoking the Internal GraphQL Server
- Predefined persisted query "Insert block in post"

### Improvements

- If initializing the service container from the cache fails, fallback to initializing PHP object from memory (#2638)
- Give unique operationName to all predefined persisted queries (#2644)
- Improved error message when fetching blocks from a post, and the block content has errors
- Completed documentation for the Automation extension (#2651)
- On the "Generate a post's featured image using AI and optimize it" predefined persisted query, execute logic only if the post title is not empty (#ec931dd)

### Fixed

- Bug in multi-control JS component used by extensions (Access Control, Cache Control, and Field Deprecation) showing "undefined" on the block on the Schema Configuration (#2639)
- Bug in regex replacements in predefined persisted queries (#2649)
- Avoid reinstalling plugin setup data if deactivating/reactivating the plugin (#2641)
- Handle error from passing WP_Post as GraphQL variable to the Internal GraphQL Server (#2652)

## 2.0.0 - 29/01/2014

### Added

- Module "Media Mutations"
- Mutation `createMediaItem`
- Fields `myMediaItemCount`, `myMediaItems` and `myMediaItem`
- Predefined persisted query "Generate a post's featured image using AI and optimize it"
- Documentation for new field `_dataMatrixOutputAsCSV` from the Helper Function Collection extension

### Improvements

- Validate the license keys when updating the plugin
- Simplified the Tutorial section
- Prevent max execution time issues when installing plugin on (cheap) shared hosting (#2631)
  - Validate that the PHP memory limit is at least 64MB to load Gato GraphQL
  - Store the new plugin versions to DB only after generating the service container cache
  - Disable the max execution time when compiling the container

### Fixed

- Bug where a syntax error on a variable definition in the GraphQL query was not validated

### Breaking changes

- Field resolver's `validateFieldArgValue` method receives extra argument `$fieldArgs`

## 1.5.4 - 11/01/2024

### Fixed

- Bug in resolver where innerBlocks is not set

## 1.5.3 - 09/01/2024

### Fixed

- Point the "Missing an extension?" link to the Contact us page
- Add link to bundles on the Extensions page
- Fixed typo in readme.txt

## 1.5.2 - 08/01/2024

### Fixed

- Active bundle or extension, with different version than main plugin, did not show "Active" button in Extensions page

## 1.5.1 - 08/01/2024

### Improvements

- Description on readme.txt (for the WordPress plugin directory)

## 1.5.0 - 08/01/2024

### Automatically deploy plugin to WordPress plugin directory SVN

Whenever creating a new release of the Gato GraphQL plugin, automatically deploy it to the WordPress plugin directory SVN (via [`10up/action-wordpress-plugin-deploy`](https://github.com/10up/action-wordpress-plugin-deploy)).

### Improvements

- Added video to documentation for bundle
- Added new predefined persisted queries:
  - "Send email to admin about post"
  - "Add comments block to post"

## 1.4.0 - 04/01/2014

### Added

- Predefined custom endpoint "Nested mutations + Entity as mutation payload type"

### Improvements

- Added "Request headers" to GraphiQL clients on single public/private endpoint, and custom endpoints
- Renamed page "Recipes" to "Tutorial", and added settings to hide it
- Renamed existing bundles:
  - “Application Glue & Automator” bundle => “Tailored WordPress Automator” bundle
  - “Content Translation” bundle => “Simplest WordPress Content Translation” bundle
  - “Public API” bundle => “Responsible WordPress Public API” bundle
- Added documentation for new bundles:
  - “Automated Content Translation & Sync for WordPress Multisite” bundle
  - “Better WordPress Webhooks” bundle
  - “Easy WordPress Bulk Transform & Update” bundle
  - “Private GraphQL Server for WordPress” bundle
  - “Selective Content Import, Export & Sync for WordPress” bundle
  - “Unhindered WordPress Email Notifications” bundle
  - “Versatile WordPress Request API” bundle

### Fixed

- HTML codes were printed in select inputs in the WordPress editor, now removed

## 1.3.0 - 07/12/2013

### Improvements

- Read `GET` variables when executing Persisted Queries via `POST`
- Pass data via URL params in persisted query "Register a newsletter subscriber from InstaWP to Mailchimp"

### Fixed

- Component docs displayed in the editor were not included in the plugin

## 1.2.0 - 28/11/2013

### Added

- `XML` scalar type
- Documentation for new field `_strDecodeXMLAsJSON` from the Helper Function Collection extension
- Documentation for new field `_strParseCSV` from the Helper Function Collection extension
- Recipe "Translating content from URL"
- Persisted Queries "Translate post (Classic editor)" and "Translate posts (Classic editor)"
- Predefined Persisted Query "Translate content from URL"
- Predefined Persisted Query "Import post from WordPress RSS feed"
- Predefined Persisted Query "Import posts from CSV"
- Predefined Persisted Query "Fetch post links"

### Fixed

- In predefined persisted queries "Translate post" and "Translate posts", added `failIfNonExistingKeyOrPath: false` when selecting a block's `attributes.{something}` property (as it may sometimes not be defined)
- In predefined persisted query "Import post from WordPress site", added status `any` to select the post
- Renamed persisted query "Translate post" to "Translate post (Gutenberg)", and "Translate posts" to "Translate posts (Gutenberg)"

## 1.1.1 - 21/11/2013

### Fixed

- Bug on the caching component (a downgraded `reset` method was called on a non array)

## 1.1.0 - 20/11/2013

### Improvements

- Tested with WordPress 6.4 ("Tested up to: 6.4")
- Install setup data: Private "Nested mutations" Custom Endpoint
- Install setup data: Private Persisted Queries for common admin tasks
- Added Settings to enable or disable installing the setup data
- Added `AnyStringScalar` wildcard scalar type

### Fixed

- Purge container when autoupdating a depended-upon plugin

## 1.0.15 - 30/10/2013

### Fixed

- Executing introspection query failed in GraphiQL client when passing ?operationName=...

## 1.0.14 - 29/10/2013

### Fixed

- Configuration alert in recipe "Duplicating multiple blog posts at once"

## 1.0.13 - 29/10/2013

### Added

- Scalar type `AnyScalar`
- Documentation for new field `_arrayGenerateAllCombinationsOfItems` from the "Helper Function Collection" extension

### Fixed

- Passing dynamic variables 2 levels down
- Do not open link in modal window when current page is in a modal window
- Newsletter form "action" attribute

## 1.0.12 - 26/10/2023

### Fixed

- Adapted the plugin following the assessment by the WordPress Plugin Review team.

### Improvements

- Recipes: Replace `mysite.com` with the site domain
- Added recipe: Automatically sending newsletter subscribers from InstaWP to Mailchimp

## 1.0.11 - 24/10/2023

### Improvements

- The `operationName` param can be obtained from the URL even when doing `POST`

## 1.0.10 - 24/10/2023

### Improvements

- Return `String` in fields `Root.optionValue` and `WithMeta.metaValue`

## 1.0.7 - 22/09/2023

### Fixed

- Constants in `wp-config.php` must start with `GATOGRAPHQL_` (previously was `GATO_GRAPHQL_`)

## 1.0.0/1.0.6 - 07/09/23

### Improvements

- Integration of Gutenberg blocks into the GraphQL schema
- Support for private and password-protected endpoints
- Browse and install Gato GraphQL extensions
- Browse Recipes, providing examples of GraphQL queries for different use cases
- Browse "Additional Documentation" when editing a Schema Configuration
- Inspect properties when editing Custom Endpoints and Persisted Query Endpoints
- Added support for the "Global Fields" custom feature
- Added support for the "Composable Directives" custom feature
- Added support for the "Multi-Field Directives" custom feature
- Send custom headers in the GraphQL response
- Added field `Category.slugPath`
- Added field `CustomPost.wpAdminEditURL`
- Added several "raw content" fields, made them all “sensitive”
- Mutations `createPost`, `updatePost`, `addCommentToCustomPost` (and others) now receive a oneof input object for content
- Mutations `createPost` and `updatePost` now have input `authorBy`, as a “sensitive” data element
- Mutations setting tags and categories on custom posts can now receive IDs or slugs via a oneof input
- Filter custom posts by `any` status
- Related inputs in filters have been grouped under input objects
- The Settings page has been re-designed, featuring 2-level organization and tabs displayed vertically
- Reset settings, and choose to use restrictive or non-restrictive default settings
- Configuration blocks in the the Schema Configuration CPT editor can be removed (and added again)
- GraphiQL clients now accept sending headers
- Display warnings in the GraphQL response
- Implementation of standard custom scalar types
- Added internal endpoints to feed data to (Gutenberg) blocks
  - Added JS variable `GATOGRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT` with URL of internal `blockEditor` endpoint
- Create custom internal endpoints to feed data to (Gutenberg) blocks
- Endpoints: Use 🟢 (green) to denote "public", 🟡 (yellow) to denote "private"
- Sort the Schema Configuration entries by name
- Configure returning a payload object or the mutated entity for mutations
- Added "State" and "Schema Configuration" columns to tables for Custom Endpoints and Persisted Queries
- Saving the Settings is faster, as it does not regenerate the service container anymore
- Only activating/deactivating Gato GraphQL extension plugins will regenerate the service container
- Generating the service container is faster (after upgrading to Symfony v6.3)
- Upgraded Voyager client to v1.3

### Fixed

- Made field `Comment.type` of type `CommentTypeEnum` (previously was `String`)
- Avoid error from loading non-existing translation files
- Updated all documentation images

### Breaking changes

- Upgraded minimum PHP version to 7.2
- Must update mutations `createPost`, `updatePost`, `addCommentToCustomPost` (and others)
- Must update mutations `setTagsOnPost`, `createPost` and `updatePost`
- Must adapt filtering data for `posts`, `customPosts` and `comments`
- Non-restrictive Settings values are used by default
- Env var `ENABLE_UNSAFE_DEFAULTS` has been removed and `SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR` added in its place, to indicate to use the restrictive Settings values by default
- Renamed plugin to "Gato GraphQL"

## 0.10.2 - 24/02/2023

### Fixed

- Performance issue where elements to resolve AST were duplicated ([#2039](https://github.com/GatoGraphQL/GatoGraphQL/pull/2039))

## 0.10.0 - 16/02/2023

### Added

- Configure several modules via the Schema Configuration:
  - Custom Posts
  - Tags
  - Categories
  - Settings
  - Custom Post Meta
  - Comment Meta
  - Taxonomy Meta
  - User Meta
- Docs for modules "Tags" and "Categories"

### Breaking changes

- Modified value for `allow` behavior option in settings

## 0.9.9 - 16/01/2023

### Enhancement

- Improved query-resolution performance (by caching method results in PHP)

## 0.9.3 - 12/01/2023

### GraphQL schema upgrade

- Added field `globalID` to all types in the schema
- In addition to `id`, fetch single entities by `slug`, `path` and other properties, on fields:
  - `Root.customPost`
  - `Root.genericCustomPost`
  - `Root.mediaItem`
  - `Root.menu`
  - `Root.page`
  - `Root.postCategory`
  - `Root.postTag`
  - `Root.post`
  - `Root.user`
- Filter elements via the new `filter` field argument
- Pagination and sorting fields are accessed via `pagination` and `sort` field args
- `customPosts` fields now also retrieve data from CPTs which are not mapped to the GraphQL schema
- Filter custom post fields (`Root.posts`, `User.posts`, etc) via new inputs:
  - `tagIDs: [ID]`
  - `tagSlugs: [String]`
  - `categoryIDs: [ID]`
  - `authorIDs: [ID]`
  - `authorSlug: String`
  - `excludeAuthorIDs: [ID]`
  - `hasPassword: Bool`
  - `password: String`
- Filter by `metaQuery`
- Exclude results via field arg `excludeIDs`
- Added field `urlPath` on several types:
  - `Post.urlPath: String!`
  - `Page.urlPath: String!`
  - `PostTag.urlPath: String!`
  - `PostCategory.urlPath: String!`
  - `User.urlPath: String!`
- `content` fields (for types `Post`, `Page` and `Comment`) are now of type `HTML`, and a new `rawContent` field of type `String` was added
- Converted from string to Enum type whenever possible
  - Custom post type and status
  - Comment type and status
  - "Order by" property, for all entities
  - Menu locations
- Added fields to retrieve the logged-in user's custom posts:
  - `Root.myCustomPost: CustomPostUnion`
  - `Root.myCustomPosts: [CustomPostUnion]!`
  - `Root.myCustomPostCount: Int!`
- Query properties for custom post fields:
  - `modified: DateTime`
  - `modifiedDateStr: String`
- Query properties for posts:
  - `Post.postFormat: String!`
  - `isSticky: Bool!`
- Fetch a page's parent and children, and the menu order:
  `Page.parent: Page`
  `Page.children: [Page]!`
  `Page.childCount: Int!`
  `Page.menuOrder: Int!`
- Filter field `pages` via new arguments:
  - `parentIDs: [ID]`
  - `parentID: ID`
- Added fields to retrieve comments and their number:
  - `Root.comment: Comment`
  - `Root.comments: [Comment]!`
  - `Root.commentCount: Int!`
  - `Root.myComment: Comment`
  - `Root.myComments: [Comment]!`
  - `Root.myCommentCount: Int!`
  - `Commentable.commentCount: Int!` (`Commentable` is an interface, implemented by types `Post`, `Page` and `GenericCustomPost`)
  - `Comment.responseCount: Int!`
- Added field arguments to filter `comments`:
  - `authorIDs: [ID!]`
  - `customPostID: ID!`
  - `customPostIDs: [ID!]`
  - `excludeCustomPostIDs: [ID]`
  - `customPostAuthorIDs: [ID!]`
  - `excludeCustomPostAuthorIDs: [ID]`
  - `customPostTypes: [String!]`
  - `dateFrom: Date`
  - `dateTo: Date`
  - `excludeAuthorIDs: [ID]`
  - `excludeIDs: [ID!]`
  - `ids: [ID!]`
  - `parentID: ID!`
  - `parentIDs: [ID!]`
  - `excludeParentIDs: [ID]`
  - `excludeIDs: [ID!]`
  - `search: String`
  - `types: [String!]`
- Comment mutations: support creating comments by non logged-in users
- Filter users by email (considered as “sensitive” data)
- Query properties for users:
  - `User.nicename: String!`
  - `User.nickname: String!`
  - `User.locale: String!`
  - `User.registeredDate: String!`
- Added utility fields to better operate with user roles:
  - `User.roleNames: [String]!`
  - `User.hasRole: Bool!`
  - `User.hasAnyRole: Bool!`
  - `User.hasCapability: Bool!`
  - `User.hasAnyCapability: Bool!`
- Added arguments `roles` and `excludeRoles` to filter by user roles (“sensitive” input fields)
- Fetch children from Categories:
  - `PostCategory.children: [PostCategory]!`
  - `PostCategory.childNames: [String]!`
  - `PostCategory.childCount: Int`
- Added filter input `hideEmpty` to fields `postTags` and `postCategories` to fetch entries with/out any post
- Added types `GenericTag` and `GenericCategory` to query any non-mapped custom taxonomy (tags and categories), and fields:
  - `Root.categories(taxonomy: String!): [GenericCategory!]`
  - `Root.tags(taxonomy: String!): [GenericTag!]`
  - `GenericCustomPost.categories(taxonomy: String!): [GenericCategory!]`
  - `GenericCustomPost.tags(taxonomy: String!): [GenericTag!]`
- Filter custom posts by their associated taxonomy (tags and categories) via new `filter` input properties:
  - `categoryTaxonomy`
  - `tagTaxonomy`
- Added fields for Menus:
  - `Root.menus: [Menu]!`
  - `Root.menuCount: Int!`
  - `Menu.name: String`
  - `Menu.slug: String`
  - `Menu.count: Int`
  - `Menu.locations: [String]!`
  - `Menu.items: [MenuItem]!`
  - `MenuItem.children: [MenuItem]!`
- Added type `UserAvatar`, and fields:
  - `User.avatar: [UserAvatar]`
  - `UserAvatar.src: String!`
  - `UserAvatar.size: Int!`
- Added field arguments to `Root.mediaItems` for filtering results
- Added media field:
  - `Root.imageSizeNames: [String]!` to retrieve the list of the available intermediate image size names
  - `Root.mediaItemCount: Int!` to count the number of media items
- Added fields for media items:
  - `Media.srcSet: String`
  - `Media.url: String!`
  - `Media.localURLPath: String`
  - `Media.slug: String!`
  - `Media.title: String`
  - `Media.caption: String`
  - `Media.altText: String`
  - `Media.description: String`
  - `Media.date: DateTime`
  - `Media.dateStr: String`
  - `Media.modified: DateTime`
  - `Media.modifiedDateStr: String`
  - `Media.mimeType: String`
  - `Media.sizes: String`
- Added fields to query options:
  - `Root.optionValues: [AnyBuiltInScalar]`
  - `Root.optionObjectValue: JSONObject`
  - `Root.optionValue: AnyBuiltInScalar` (renamed from `Root.option`)
- Mutations now return "Payload" types:
  - `Comment.reply: CommentReplyMutationPayload!`
  - `Commentable.addComment: CustomPostAddCommentMutationPayload!`
  - `Post.setCategories: PostSetCategoriesMutationPayload!`
  - `Post.setTags: PostSetTagsMutationPayload!`
  - `Post.update: PostUpdateMutationPayload!`
  - `Root.addCommentToCustomPost: RootAddCommentToCustomPostMutationPayload!`
  - `Root.createPost: RootCreatePostMutationPayload!`
  - `Root.loginUser: RootLoginUserMutationPayload!`
  - `Root.logoutUser: RootLogoutUserMutationPayload!`
  - `Root.replyComment: RootReplyCommentMutationPayload!`
  - `Root.removeFeaturedImageFromCustomPost: RootRemoveFeaturedImageFromCustomPostMutationPayload!`
  - `Root.setCategoriesOnPost: RootSetCategoriesOnPostMutationPayload!`
  - `Root.setFeaturedImageOnCustomPost: RootSetFeaturedImageOnCustomPostMutationPayload!`
  - `Root.setTagsOnPost: RootSetTagsOnPostMutationPayload!`
  - `Root.updatePost: RootUpdatePostMutationPayload!`
  - `WithFeaturedImage.removeFeaturedImage: CustomPostRemoveFeaturedImageMutationPayload!`
  - `WithFeaturedImage.setFeaturedImage: CustomPostSetFeaturedImageMutationPayload!`
- `Commentable` and `WithFeaturedImage` interfaces are only added to CPTs that support the feature

### Added

- Support for custom scalar types
  - Implementation of standard custom scalar types
  - Implementation of `Numeric` scalar
  - Support for the new "Specified By URL" meta property
- Support for custom enum types
  - Implementation of several enum types
- Support for "enum string" types
  - Implementation of several "enum string" types
- Support for input objects
  - Implementation of several input object types
- Support for oneof input objects
  - Implementation of several oneof input objects
- Support for operation directives
- Restrict field directives to specific types
- Added module "Self Fields"
- Link to the online documentation of the GraphQL errors
- Namespacing is applied to new types
- Print the full path to the GraphQL query node producing errors
- Allow to use unsafe default settings
- Schema Configuration for the Single Endpoint
- Display `"causes"` for errors in response ([#893](https://github.com/graphql/graphql-spec/issues/893))
- Sort fields and connections together, alphabetically
- The entities from the WordPress data model are not namespaced anymore ([#990](https://github.com/GatoGraphQL/GatoGraphQL/pull/990))
- Split options into 2 entries, "Default value for Schema Configuration" and "Apply on Admin clients", for the following settings:
  - Namespacing
  - Nested mutations
  - Admin fields
- Validate constraints for field and directive arguments
- Added options "default limit" and "max limit" for Posts and Pages
- Return an error if access is not allowed for the option name or meta key
- Completed all remaining query validations defined by the GraphQL spec
  - No cyclical fragment references
  - No duplicate fragment names
  - Fragment spread type existence
  - Support fragment spread on unions
  - Variables are input types
  - Queried fields are unambiguous
- Organize custom endpoints and persisted queries by category
- Support block string characters
- Query schema extensions via introspection
  - Implemented extension `isSensitiveDataElement`
- Performance improvement: Avoid regenerating the container when the schema is modified
- Clicking on "Save Changes" on the Settings page will always regenerate the schema
- Prettyprint GraphQL queries in the module docs
- Upgraded GraphiQL to `v1.5.7`
- Finished decoupling the GraphQL server code from WordPress
- Browse documentation when editing a Schema Configuration, Custom Endpoint and Persisted Query

### Fixed

- Fixed newlines removed from GraphQL query after refreshing browser ([#972](https://github.com/GatoGraphQL/GatoGraphQL/pull/972))

### Improvements in Development and Testing

- Created several hundred new unit and integration tests
- Upgraded all code to PHPStan's level 8
- Bumped the min PHP version to 8.1 for development

### Breaking changes

- Replaced argument `id` with `by` in fields fetching a single entity
- Must update GraphQL queries to use the new `filter`, `pagination` and `sort` field arguments
- Renamed module "Schema for the Admin" to "Expose Sensitive Data in the Schema"
- Renamed scalar type `AnyScalar` to `AnyBuiltInScalar`
- Renamed interface type `Elemental` to `IdentifiableObject`
- Renamed field `Root.option` to `Root.optionValue`
- Removed the `genericCustomPosts` fields, unifying their logic into `customPosts`
- All `date` fields (such as `Post.date`, `Media.date` and `Comment.date`) and `modified` fields are now of type `DateTime` (before they had type `String`)
- Must update `content(format:PLAIN_TEXT)` to `rawContent`
- Must update the inputs for mutations
- Merged the “sensitive” fields with the non-admin versions: instead of having fields `posts` and `unrestrainedPosts`, now there is only field `posts`, and its `filter` argument can also receive input `status` when `Expose Sensitive Data in the Schema` is enabled
- `User.email` is treated as “sensitive” field
- Mutations now return a "Payload" type
- Removed modules: Access Control, Cache Control, Public/Private Schema Mode, and Low-Level Persisted Query Editing
- Module "GraphiQL Explorer" has been hidden
- Settings for several modules must be set again
- Must re-set options "default limit" and "max limit" for Posts and Pages

## 0.8.1 - 21/07/2021

### Fixed

- Field `myPosts` retrieving posts for all users, not logged-in user

## 0.8.0 - 19/07/2021

### Added

- Support for WordPress 5.8 (switched to newly-introduced hooks)
- Simplified the codebase, using container services everywhere
- Further completed the WordPress schema
  - Categories
  - Meta values
  - Menus
  - Settings
  - Logged-in user's posts
- "Schema for the Admin" module, exposing "unrestricted" admin fields to the GraphQL schema
- Introduced scalar type `AnyScalar`, representing any of the built-in scalars (`String`, `Int`, `Boolean`, `Float` and `ID`)
- Cache is saved under the `wp-content`
- Split the GraphQL endpoint for accessing data for the WordPress editor into two:
  1. `GATOGRAPHQL_ADMIN_CONFIGURABLESCHEMA_ENDPOINT`
  2. `GATOGRAPHQL_ADMIN_FIXEDSCHEMA_ENDPOINT`
- Option to display the Settings page in long form, or using tabs
- Further support of field types in the schema:
  - Lists with non-null items (`[String!]`)
  - Lists of lists (`[[String]]`)
- Input coercion: accept a single value when a list is expected 
- Avoid deprecation notices in WordPress 5.8, by using the new filter hooks:
  - `block_categories_all`
  - `allowed_block_types_all`

### Backwards-breaking changes:

- Use bitwise operations and flags to augment a field's type (eg: `[String]!` => `SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY`)
- Schema Configurations, Custom Endpoints and Persisted Queries have their Option blocks rebuilt, and they must be filled again.

### Fixed:

- Improved support for PHP 8.0 (fixed several issues)
- PHP notice `"is_singular was called incorrectly"` in WordPress 5.8
- When a directive fails, the field is set to `null` in the response (previously the directive was ignored, and the field still printed)
- A failing input coercion produces an error (previously it produced a warning, printed under `extensions`)

## 0.7.12/13 - 2021-02-23

### Fixed

- GraphiQL client returning 406 status error with ModSecurity (#436)

## 0.7.11 - 2021-02-22

### Enhancement

- Improved initialization speed (#412)

### Fixed

- Markdown pages not working (#433)

## 0.7.10 - 2021-02-01

### Fixed

- Removed file `rector-test-scoping.php` from the plugin

## 0.7.8/9 - 2021-01-30

### Fixed

- Scoped external dependencies (#246)

## 0.7.7 - 2021-01-25

### Fixed

- Point GitHub Updater to GatoGraphQL/gatographql-dist

## 0.7.5 to 0.7.6 - 2021-01-09

### Fixed

- Compatibility with PHP 8.0

## 0.7.1 to 0.7.4 - 2020-12-17

### Fixed

- Compatibility with PHP 8.0

## 0.7.0 - 2020-12-17

### Added

- Mutations and nested mutations ([#28](https://github.com/GraphQLAPI/graphql-api-for-wp/issues/28)) - Thanks to [WPKube](https://www.wpkube.com/) for sponsoring this feature ❤️
- Mutations in the schema:
  - `Root.createPost`
  - `Root.updatePost`
  - `Root.setFeaturedImageforCustomPost`
  - `Root.removeFeaturedImageforCustomPost`
  - `Root.addCommentToCustomPost`
  - `Root.replyComment`
  - `Root.loginUser`
  - `Root.logoutUser`
  - `CustomPost.update` (nested)
  - `CustomPost.setFeaturedImage` (nested)
  - `CustomPost.removeFeaturedImage` (nested)
  - `CustomPost.addComment` (nested)
  - `Comment.reply` (nested)
- Support for PHP 8.0

### Updated

- Replaced the About page with the Support page
- Decreased the size of the plugin's `.zip` file:
  - Images are referenced from the GitHub repo, and not packed within the plugin anymore
  - Removed all `package-lock.json` files from the plugin

### Fixed

- Automatic namespacing: retrieve the namespace from the called class
- GraphiQL in Persisted query editor: use schema from selected Schema configuration
- Array unpacking can't be used with string keys
- Filtering of generic custom posts when passing non whitelisted CPT

## 0.6.4 - 2020-10-09

### Fixed

- Renamed field `echo` as `echoStr` to avoid conflict with global field `echo`

### Enhancement (for development)

- Use newly downgradable PHP 7.4 and 7.3 features (list reference assignment, array spread, `_` in numeric literal)

## 0.6.3 - 2020-09-25

### Fixed

- No need to duplicate asset for GitHub Updater
- Renamed verbose options for the GraphiQL Explorer settings (if any option had been disabled using `v0.6.2` or below, the form needs to be submitted again, to use the new option names)
- Store plugin version to detect updates (plugin can be updated in many ways, eg: using Composer, so using hook "upgrader_process_complete" doesn't always work)
- Added option to disable admin notice in settings, and link in the admin notice

## 0.6.2 - 2020-09-22

### Fixed

- Set the right version in plugin main file

## 0.6.1 - 2020-09-22

### Fixed

- Made GraphiQL client in admin show the default GraphQL query

## 0.6.0 - 2020-09-22

### Added

- Upgraded PHPStan to level 8
- Plugin for production can run with PHP 7.1
- Added Embeddable Fields (#41)
- Support for GitHub Updater (#53)
- Use the GraphiQL Explorer in the public clients (#23)
- About page
- After updating plugin, show an admin notice with link to Release notes

## 0.5.0 - 2020-09-01

### Added

- Code now supports typed properties from PHP 7.4, and it uses Rector to convert it to PHP 7.2 when generating the plugin for production

## 0.4.2 - 2020-08-31

### Fixed

- Add the plugin version to the cache timestamp, to avoid configuration caching conflicts when developing the plugin (whenever this happens, upgrading the version solves the issue)

## 0.4.1 - 2020-08-26

### Fixed

- If the plugin is installed more than once (eg: by mistake because the .zip file has been renamed) then load only one version

## 0.4 - 2020-08-26

### Added

- Lazy-load the documentation inside blocks

### Fixed

- `is_admin()` or not affects the configuration, so this value must be accounted for when generating the cache
- Logic for `options.php` is not executed when WP core or other plugins save their own settings

## 0.3 - 2020-08-24

### Added

- Filter modules by type
- Use different colors to distinguish modules by type
- Documentation for all modules, accessible clicking on "View details" on each module
- Documentation for modals inside blocks
- Module "Remove if Null" to add directive `@removeIfNull`
- Module "Proactive Feedback" to send data about deprecations, warnings, logs, notices and traces in the response to the query
- Module "Multiple Query Execution" to enable/disable functionality
- If module "Multiple Query Execution" is disabled:
    - Directive `@export` is also disabled
    - The server respects the GraphQL spec concerning `operationName` (https://spec.graphql.org/draft/#GetOperation()) 

## 0.2.1 - 2020-08-07

### Added

- Process only the operation indicated in `operationName` in the GraphQL payload, as sent by GraphiQL
- Hack to add support for query batching from GraphiQL: When in GraphiQL running query ```query __ALL { id }```, it will execute all the other queries in the document

## 0.2.0 - 2020-08-06

### Added

- Query Batching
- Directive aliases (through trait `AliasSchemaFieldDirectiveResolverTrait`)
- Field aliases on the server (through trait `AliasSchemaObjectTypeFieldResolverTrait`)

### Fixed

- Enabled variables as expressions for `@export`

## 0.1.22 - 2020-08-04

### Fixed

- Non-default endpoints did not work after re-activating the plugin, WP requires to add hack to execute `flush_rewrite_rules` in first request after plugin is activated

## 0.1.21 - 2020-08-04

### Fixed

- Exception was thrown when executing a query, and option `"Enable to select the visibility for a set of fields/directives when editing the Access Control List"` was disabled

## 0.1.20 - 2020-07-31

### Added

- Added a GitHub action that, whenever the source code is tagged, creates the installable plugin and uploads it as a release asset

## 0.1.1 - 2020-07-31

### Fixed

- GraphiQL client retrieves domain using $_SERVER['HTTP_HOST'] instead of $_SERVER['SERVER_NAME'], for if configuration in server is not correct
- Ignore port 443 from the URL retrieved `fullUrl` for SSL
- Fixed issue to query users by email

## 0.1.0 - 2020-07-22

### Added

- Launched project
