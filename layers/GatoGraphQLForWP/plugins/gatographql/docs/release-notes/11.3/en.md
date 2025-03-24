# Release Notes: 11.3

## Added

- Fields `meta: ListValueJSONObject!` and `metaKeys: [String!]!` for types `Comment/CustomPost/TaxonomyTerm/User` ([#3060](https://github.com/GatoGraphQL/GatoGraphQL/pull/3060))
- Type `ListValueJSONObject` ([#3060](https://github.com/GatoGraphQL/GatoGraphQL/pull/3060))

## Improvements

- Made meta field `metaValue` handle any scalar type (previously only `String`) ([#3061](https://github.com/GatoGraphQL/GatoGraphQL/pull/3061))
- Made meta field `metaValues` handle any scalar type (previously only built-in ones), such as `JSONObject` ([#3061](https://github.com/GatoGraphQL/GatoGraphQL/pull/3061))
