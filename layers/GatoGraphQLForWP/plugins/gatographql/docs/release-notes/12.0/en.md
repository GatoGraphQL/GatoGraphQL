# Release Notes: 12.0

## Added

- Documentation for new Elementor Premium Extension ([#3052](https://github.com/GatoGraphQL/GatoGraphQL/pull/3052))

## Improvements

- Convert stdClass to associative array when storing meta in the DB ([#3082](https://github.com/GatoGraphQL/GatoGraphQL/pull/3082))
- Improve error message when casting to `NullableListValueJSONObject` fails ([#3083](https://github.com/GatoGraphQL/GatoGraphQL/pull/3083))

## Breaking changes

- Plugin constructor signature receives nullable `commitHash` param ([#3056](https://github.com/GatoGraphQL/GatoGraphQL/pull/3056))
- Store the extension names whose license has just been activated (instead of a timestamp) to install setup data ([#3057](https://github.com/GatoGraphQL/GatoGraphQL/pull/3057))
- Renamed `getCommentPostID` to `getCommentCustomPostID` ([#3073](https://github.com/GatoGraphQL/GatoGraphQL/pull/3073))
