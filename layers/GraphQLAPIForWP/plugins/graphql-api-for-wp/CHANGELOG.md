# Changelog

All notable changes to `graphql-api-for-wp` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 0.9.0 - DATE

### GraphQL schema upgrade

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
- Added field `urlAbsolutePath` on several types:
  - `Post.urlAbsolutePath: String!`
  - `Page.urlAbsolutePath: String!`
  - `PostTag.urlAbsolutePath: String!`
  - `PostCategory.urlAbsolutePath: String!`
  - `User.urlAbsolutePath: String!`
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
  - `CustomPost.commentCount: Int!`
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
- Filter users by email (considered as "admin" data)
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
- Added arguments `roles` and `excludeRoles` to filter by user roles ("admin" input fields)
- Fetch children from Categories:
  - `PostCategory.children: [PostCategory]!`
  - `PostCategory.childNames: [String]!`
  - `PostCategory.childCount: Int`
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

### Added

- Support for custom scalar types
  - Implementation of standard custom scalar types
  - Implementation of `Numeric` scalar
  - Support for the new "Specified By URL" meta property
- Support for custom enum types
  - Implementation of several enum types
- Support for input objects
  - Implementation of several input object types
- Support for oneof input objects
  - Implementation of several oneof input objects
- Namespacing is applied to new types
- Allow to use unsafe default settings
- Module "Schema Self Fields"
- Schema Configuration for the Single Endpoint
- Exposed the `__schema` field in the ACLs, allowing us to disable introspection
- Sort fields and connections together, alphabetically
- In the ACLs and CCLs, display fields for the `Root` type only, and automatically replicate the configuration for the `QueryRoot`+`MutationRoot` types
- The entities from the WordPress data model are not namespaced anymore ([#990](https://github.com/leoloso/PoP/pull/990))
- Split options into 2 entries, "Default value for Schema Configuration" and "Apply on Admin clients", for the following settings:
  - Namespacing
  - Nested mutations
  - Admin fields
- Validate constraints for field and directive arguments
- Added options "default limit" and "max limit" for Posts and Pages
- Return an error if access is not allowed for the option name or meta key
- Query `extensions` in the schema introspection
  - Implemented extension `isAdminElement`
- Performance improvement: Avoid regenerating the container when the schema is modified
- Clicking on "Save Changes" on the Settings page will always regenerate the schema
- Prettyprint GraphQL queries in the module docs
- Upgraded GraphiQL to the latest `v1.5.7`
- Finished decoupling the GraphQL server code from WordPress

### Fixed

- Fixed newlines removed from GraphQL query after refreshing browser ([#972](https://github.com/leoloso/PoP/pull/972))

### Breaking changes

- Replaced argument `id` with `by` in fields fetching a single entity
- Must update GraphQL queries to use the new `filter`, `pagination` and `sort` field arguments
- Renamed module "Schema for the Admin" to "Schema Expose Admin Data"
- Renamed scalar type `AnyScalar` to `AnyBuiltInScalar`
- Renamed interface type `Elemental` to `Node`
- Renamed field `Root.option` to `Root.optionValue`
- All `date` fields (such as `Post.date`, `Media.date` and `Comment.date`) and `modified` fields are now of type `DateTime` (before they had type `String`)
- Must update `content(format:PLAIN_TEXT)` to `rawContent`
- Must update the inputs for mutations
- Merged the "admin" fields with the non-admin versions: instead of having fields `posts` and `unrestrainedPosts`, now there is only field `posts`, and its `filter` argument can also receive input `status` when `Schema Expose Admin Data` is enabled
- `User.email` is treated as "admin" field
- The Access Control and Cache Control configuration lists will be broken: all fields for all non-root types broken will appear under "(Undefined entries)". These lists must be recreated
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
  1. `GRAPHQL_API_ADMIN_CONFIGURABLESCHEMA_ENDPOINT`
  2. `GRAPHQL_API_ADMIN_FIXEDSCHEMA_ENDPOINT`
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

- Point GitHub Updater to GraphQLAPI/graphql-api-for-wp-dist

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
- Directive aliases (through trait `AliasSchemaDirectiveResolverTrait`)
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
