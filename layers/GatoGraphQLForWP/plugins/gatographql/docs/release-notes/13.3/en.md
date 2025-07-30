# Release Notes: 13.3

## Added

- Added fields `GenericCustomPost.ancestors` and `Page.ancestors` ([#3167](https://github.com/GatoGraphQL/GatoGraphQL/pull/3167))
- Added fields `children` and `childCount` to `GenericCustomPost` ([#3168](https://github.com/GatoGraphQL/GatoGraphQL/pull/3168))
- Added field `Category.ancestors` ([#3169](https://github.com/GatoGraphQL/GatoGraphQL/pull/3169))
- Added field `parent` to `CustomPost` ([#3159](https://github.com/GatoGraphQL/GatoGraphQL/pull/3159))
- Added field `slugPath` to CustomPost (comprising the slug for all ancestor posts) ([#3163](https://github.com/GatoGraphQL/GatoGraphQL/pull/3163))
- Added field `CustomPost.menuOrder` ([#3172](https://github.com/GatoGraphQL/GatoGraphQL/pull/3172))
- Pass parent on custom post mutations ([#3160](https://github.com/GatoGraphQL/GatoGraphQL/pull/3160))
- Print body of failing requests in error logs ([#3155](https://github.com/GatoGraphQL/GatoGraphQL/pull/3155))
- Support executing bulk actions with custom settings ([#3156](https://github.com/GatoGraphQL/GatoGraphQL/pull/3156))

## Improvements

- Support reading just-updated plugin settings in options.php ([#3157](https://github.com/GatoGraphQL/GatoGraphQL/pull/3157))

## Fixed

- Querying `post(by: { id: 0 })` returns `null` (instead of the post in the loop) ([#3165](https://github.com/GatoGraphQL/GatoGraphQL/pull/3165))
