# Release Notes: 12.0

## Added

- Documentation for new Elementor Premium Extension ([#3052](https://github.com/GatoGraphQL/GatoGraphQL/pull/3052))

## Improvements

- Convert stdClass to associative array when storing meta in the DB ([#3082](https://github.com/GatoGraphQL/GatoGraphQL/pull/3082))
- Improve error message when casting to `NullableListValueJSONObject` fails ([#3083](https://github.com/GatoGraphQL/GatoGraphQL/pull/3083))
- Added `meta` input field to the `createPage` mutation ([#3085](https://github.com/GatoGraphQL/GatoGraphQL/pull/3085))

## Breaking changes

- Plugin constructor signature receives nullable `commitHash` param ([#3056](https://github.com/GatoGraphQL/GatoGraphQL/pull/3056))
- Store the extension names whose license has just been activated (instead of a timestamp) to install setup data ([#3057](https://github.com/GatoGraphQL/GatoGraphQL/pull/3057))
- Renamed `getCommentPostID` to `getCommentCustomPostID` ([#3073](https://github.com/GatoGraphQL/GatoGraphQL/pull/3073))

## Fixed

- Avoid `_load_textdomain_just_in_time` error message in WP 6.8 ([#3084](https://github.com/GatoGraphQL/GatoGraphQL/pull/3084))
