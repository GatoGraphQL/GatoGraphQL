# Release Notes: 2.2

## Improvements

- Added "Lesson (number): " in the tutorials (`v2.2.1`)
- Adapted `blocks` field to work with WordPress 6.5 ([#2657](https://github.com/GatoGraphQL/GatoGraphQL/pull/2657)) (`v2.2.2`)
- Tested up WordPress 6.5 (`v2.2.2`)
- Renamed "Tutorial" to "Schema tutorial" (`v2.2.2`)

### Do not include bundles in the Extensions page

In order to simplify the product offering, Gato GraphQL PRO will now be released as a single plugin, containing all the extensions. As such, installing bundles and single extensions are now both deprecated, and not offered anymore.

### Do not print the required extensions on predefined persisted queries

Similar to above, predefined persisted queries are now simplified. These now have "[PRO]" prepended to their title, and the list of required extensions (printed on the body of the query) is removed.
