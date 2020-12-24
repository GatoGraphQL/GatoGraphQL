# Changelog

All notable changes to `graphql-api` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 0.8.0 - DATE

### Added

- Composable directives

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
- Field aliases on the server (through trait `AliasSchemaFieldResolverTrait`)

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
