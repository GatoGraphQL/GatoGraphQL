# Release Notes: 10.1

## Added

- YouTube video for the Automation extension doc ([#93311e2](https://github.com/GatoGraphQL/GatoGraphQL/commit/93311e28ead43b18d4e18e1d19e3e0602de176af))
- Completed documentation for extensions:
  - [Schema Functions] New fields `_arrayEncodeAsJSONString` and `_objectEncodeAsJSONString` ([#3013](https://github.com/GatoGraphQL/GatoGraphQL/pull/3013))
  - [Helper Function Collection] New field `_arrayOfJSONObjectsExtractPropertiesAndConvertToObject` ([#3018](https://github.com/GatoGraphQL/GatoGraphQL/pull/3018))
- Order tabs in Settings by priority ([#3014](https://github.com/GatoGraphQL/GatoGraphQL/pull/3014))
- Documentation for new extension "Translation" (replacing "Google Translate" and "DeepL") ([#3016](https://github.com/GatoGraphQL/GatoGraphQL/pull/3016))

## Improvements

- Allow to create custom posts as `private` ([#3019](https://github.com/GatoGraphQL/GatoGraphQL/pull/3019))
- Allow printing textarea options in Settings ([#3020](https://github.com/GatoGraphQL/GatoGraphQL/pull/3020))
- Plugin updates: Use the same icon as the Gato GraphQL plugin for the extensions (`v10.1.1`) ([#3022](https://github.com/GatoGraphQL/GatoGraphQL/pull/3022))

## Fixed

- Exception when serializing an array value ([#3017](https://github.com/GatoGraphQL/GatoGraphQL/pull/3017))

## [Extensions] Added

- [Schema Functions] Fields `_arrayEncodeAsJSONString` and `_objectEncodeAsJSONString`
- [Helper Function Collection] Field `_arrayOfJSONObjectsExtractPropertiesAndConvertToObject`

## [Extensions] Fixed

- [Google Translate] Make the `@strTranslate` directive send not more than 128 strings to translate
