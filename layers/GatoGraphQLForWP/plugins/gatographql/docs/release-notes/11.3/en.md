# Release Notes: 11.3

## Added

- Fields `meta: ListValueJSONObject!` and `metaKeys: [String!]!` for types `Comment/CustomPost/TaxonomyTerm/User` ([#3060](https://github.com/GatoGraphQL/GatoGraphQL/pull/3060))
- Custom post meta mutations ([#3067](https://github.com/GatoGraphQL/GatoGraphQL/pull/3067))
- Category meta mutations ([#3063](https://github.com/GatoGraphQL/GatoGraphQL/pull/3063))
- Tag meta mutations ([#3064](https://github.com/GatoGraphQL/GatoGraphQL/pull/3064))
- Type `ListValueJSONObject` ([#3060](https://github.com/GatoGraphQL/GatoGraphQL/pull/3060))

## Improvements

- Made meta field `metaValue` handle any scalar type (previously only `String`) ([#3061](https://github.com/GatoGraphQL/GatoGraphQL/pull/3061))
- Made meta field `metaValues` handle any scalar type (previously only built-in ones), such as `JSONObject` ([#3061](https://github.com/GatoGraphQL/GatoGraphQL/pull/3061))
- Allow to hook inputs into tag/category mutations ([#3062](https://github.com/GatoGraphQL/GatoGraphQL/pull/3062))

## Fixed

- Passing a non-`post` CPT to `updatePost` will show an error ([#3070](https://github.com/GatoGraphQL/GatoGraphQL/pull/3070))
