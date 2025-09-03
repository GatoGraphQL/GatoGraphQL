# Release Notes: 14.0

## Breaking changes

- Pass `object` param to `resolveMetaKeysValue` ([#3177](https://github.com/GatoGraphQL/GatoGraphQL/pull/3177))

## Added

- Added fields `GenericCustomPost.ancestors` and `Page.ancestors` ([#3167](https://github.com/GatoGraphQL/GatoGraphQL/pull/3167))
- Added fields `children` and `childCount` to `GenericCustomPost` ([#3168](https://github.com/GatoGraphQL/GatoGraphQL/pull/3168))
- Added field `Category.ancestors` ([#3169](https://github.com/GatoGraphQL/GatoGraphQL/pull/3169))
- Added field `parent` to `CustomPost` ([#3159](https://github.com/GatoGraphQL/GatoGraphQL/pull/3159))
- Added field `slugPath` to CustomPost (comprising the slug for all ancestor posts) ([#3163](https://github.com/GatoGraphQL/GatoGraphQL/pull/3163))
- Added field `CustomPost.menuOrder` ([#3172](https://github.com/GatoGraphQL/GatoGraphQL/pull/3172))
- Added `menuOrder` arg to custom post mutations ([#3173](https://github.com/GatoGraphQL/GatoGraphQL/pull/3173))
- Pass parent on custom post mutations ([#3160](https://github.com/GatoGraphQL/GatoGraphQL/pull/3160))

## Improvements

- Print body of failing requests in error logs ([#3155](https://github.com/GatoGraphQL/GatoGraphQL/pull/3155))
- Support executing bulk actions with custom settings ([#3156](https://github.com/GatoGraphQL/GatoGraphQL/pull/3156))
- Support reading just-updated plugin settings in options.php ([#3157](https://github.com/GatoGraphQL/GatoGraphQL/pull/3157))
- Append selected option value in dropdown in Settings if it doesn't exist ([#3178](https://github.com/GatoGraphQL/GatoGraphQL/pull/3178))
- Allow storing JSON data for options in Settings ([#3179](https://github.com/GatoGraphQL/GatoGraphQL/pull/3179))
- Added convenient method to handle Ajax Requests ([#3180](https://github.com/GatoGraphQL/GatoGraphQL/pull/3180))
- Allow executing further functionality on the Settings page ([#3181](https://github.com/GatoGraphQL/GatoGraphQL/pull/3181))
- Show notification labels in different colors for different severities ([#3184](https://github.com/GatoGraphQL/GatoGraphQL/pull/3184))
- Enable log notifications for warnings by default ([#3185](https://github.com/GatoGraphQL/GatoGraphQL/pull/3185))
- Support executing WP-CLI commands ([#3188](https://github.com/GatoGraphQL/GatoGraphQL/pull/3188))
- Validate the domain in the active license corresponds to the current site (for commercial extensions) ([#3192](https://github.com/GatoGraphQL/GatoGraphQL/pull/3192))
- Don't trigger license check if never run before ([#3194](https://github.com/GatoGraphQL/GatoGraphQL/pull/3194))
- Set `WithMeta.metaKeys` as sensitive field ([#3197](https://github.com/GatoGraphQL/GatoGraphQL/pull/3197))

## Fixed

- Querying `post(by: { id: 0 })` returns `null` (instead of the post in the loop) ([#3165](https://github.com/GatoGraphQL/GatoGraphQL/pull/3165))
- Plugin not working with PHP 7.4 ([#3182](https://github.com/GatoGraphQL/GatoGraphQL/pull/3182))
- Updating posts storing wrong date ([#3186](https://github.com/GatoGraphQL/GatoGraphQL/pull/3186))
- User authentication when executing query triggered via the WP REST API ([#3187](https://github.com/GatoGraphQL/GatoGraphQL/pull/3187))
- Requesting GraphQL endpoint using Application Passwords didn't work when WooCommerce is installed ([#3195](https://github.com/GatoGraphQL/GatoGraphQL/pull/3195))
