# Release Notes: 18.0

### Breaking changes

- Changed namespace for packages in Gato GraphQL extensions (HTTP Client/PHP Constants and Environment Variables via Schema)
- Changed the admin endpoint URL (from `wp-admin/edit.php?page=gatographql&action=execute_query` to `wp-admin/edit.php?page=gatographql&action=run_query`) ([#3308](https://github.com/GatoGraphQL/GatoGraphQL/pull/3308))

### Added

- Field `Root.blockTypes` to query data from the Block Type Registry ([#3291](https://github.com/GatoGraphQL/GatoGraphQL/pull/3291))
- Cache compiled queries to disk ([#3303](https://github.com/GatoGraphQL/GatoGraphQL/pull/3303))

### Improvements

- Tested up to WordPress 7.0 ([#3289](https://github.com/GatoGraphQL/GatoGraphQL/pull/3289))
- Support AI Connectors from WordPress 7.0 ([#3290](https://github.com/GatoGraphQL/GatoGraphQL/pull/3290))
- Updated documentation for Translation extension, now supporting Gemini as a translation service provider ([#3292](https://github.com/GatoGraphQL/GatoGraphQL/pull/3292))
- Allow the custom post `author` to be null (e.g. when the post has no assigned user / author ID 0) ([#3293](https://github.com/GatoGraphQL/GatoGraphQL/pull/3293))
- Execute GraphQL queries much faster ([#3294](https://github.com/GatoGraphQL/GatoGraphQL/pull/3294), [#3295](https://github.com/GatoGraphQL/GatoGraphQL/pull/3295), [#3298](https://github.com/GatoGraphQL/GatoGraphQL/pull/3298))
- Consume less memory ([#3296](https://github.com/GatoGraphQL/GatoGraphQL/pull/3296), [#3297](https://github.com/GatoGraphQL/GatoGraphQL/pull/3297))
- Replace deprecations in PHP 8.5 ([#3306](https://github.com/GatoGraphQL/GatoGraphQL/pull/3306))
- Allow disabling the "Block type '...' is not server-side registered" warning ([#3307](https://github.com/GatoGraphQL/GatoGraphQL/pull/3307))

### Fixed

- Handle null response from license API (eg: when access is forbidden via network) ([#3288](https://github.com/GatoGraphQL/GatoGraphQL/pull/3288))
- In field `menus`, return no results when passing empty `filter.slugs` ([#8773b1b](https://github.com/GatoGraphQL/GatoGraphQL/commit/8773b1b))
- Avoid conflict with BBQ Firewall plugin ([#3308](https://github.com/GatoGraphQL/GatoGraphQL/pull/3308))
- Non-nullable parameter in method definition ([#0cbe33e2](https://github.com/GatoGraphQL/GatoGraphQL/commit/0cbe33e2))
- Make sure query from request is a string ([#3309](https://github.com/GatoGraphQL/GatoGraphQL/pull/3309))
- Several bug fixes ([#3310](https://github.com/GatoGraphQL/GatoGraphQL/pull/3310))
