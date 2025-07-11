# Release Notes: 13.2

## Added

- Field `CustomPost.rawStatus` (as it exists in the database, eg: `publish` instead of `future`) ([#3142](https://github.com/GatoGraphQL/GatoGraphQL/pull/3142))
- Filter custom posts and media items by "slugs" ([#3143](https://github.com/GatoGraphQL/GatoGraphQL/pull/3143))
- Date parameter to `createMediaItem` mutation ([#3146](https://github.com/GatoGraphQL/GatoGraphQL/pull/3146))
- Option to create media items from unsafe URLs ([#3149](https://github.com/GatoGraphQL/GatoGraphQL/pull/3149))
- Fields `srcs`, `widths`, and `heights` (to fetch data for several `sizes`) to type `Media` ([#3149](https://github.com/GatoGraphQL/GatoGraphQL/pull/3149))

## Improvements

- Added `future` and `inherit` statuses to the `CustomPostStatus` enum ([#3142](https://github.com/GatoGraphQL/GatoGraphQL/pull/3142))
- Updated dependencies to latest version ([#3145](https://github.com/GatoGraphQL/GatoGraphQL/pull/3145))

## Fixed

- Don't validate promises inside array of arrays ([#3144](https://github.com/GatoGraphQL/GatoGraphQL/pull/3144))
- Don't fail validation for already-coerced values on inputs containing both a dynamic variable and a custom scalar ([#3150](https://github.com/GatoGraphQL/GatoGraphQL/pull/3150))
- Exception in Extensions page ([#3151](https://github.com/GatoGraphQL/GatoGraphQL/pull/3151))
