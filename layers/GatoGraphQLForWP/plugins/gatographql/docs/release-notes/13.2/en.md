# Release Notes: 13.2

## Added

- Field `CustomPost.rawStatus` (as it exists in the database, eg: `publish` instead of `future`) ([#3142](https://github.com/GatoGraphQL/GatoGraphQL/pull/3142))
- Filter custom posts and media items by "slugs" ([#3143](https://github.com/GatoGraphQL/GatoGraphQL/pull/3143))

## Improvements

- Added `future` and `inherit` statuses to the `CustomPostStatus` enum ([#3142](https://github.com/GatoGraphQL/GatoGraphQL/pull/3142))

## Fixed

- Don't validate promises inside array of arrays ([#3144](https://github.com/GatoGraphQL/GatoGraphQL/pull/3144))
