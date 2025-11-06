# Release Notes: 15.2

## Added

- Support `--fail-if-log-notifications` param to WP-CLI commands, to print IDs only when a log entry was added during the execution ([#3221](https://github.com/GatoGraphQL/GatoGraphQL/pull/3221))

### Improvements

- Include block's `innerContent` property for `blockDataItems` and `blockFlattenedDataItems` fields ([#3224](https://github.com/GatoGraphQL/GatoGraphQL/pull/3224))
- `CustomPost.slug` can return empty string if not yet set ([#3228](https://github.com/GatoGraphQL/GatoGraphQL/pull/3228))

## Fixed

- Check slugPath is not empty when adding page parent by slugPath ([#3218](https://github.com/GatoGraphQL/GatoGraphQL/pull/3218))
- Exception when passing field arg with value null ([#3222](https://github.com/GatoGraphQL/GatoGraphQL/pull/3222))
- Querying taxonomy that is assigned to more than 1 CPT ([#3225](https://github.com/GatoGraphQL/GatoGraphQL/pull/3225))
- Errors inside nested directives not passing the fields upwards when `nestErrorsInMetaDirectives` is false ([#3226](https://github.com/GatoGraphQL/GatoGraphQL/pull/3226))
- The link on the `wpAdminEditURL` field to print `&` as `&` (not `&amp`) ([#3227](https://github.com/GatoGraphQL/GatoGraphQL/pull/3227))
