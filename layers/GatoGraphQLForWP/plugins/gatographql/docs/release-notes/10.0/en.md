# Release Notes: 10.0

## Breaking changes

- Changed signature of method `validateDirectiveArgValue`, passing the `$directiveArgs` param when validating a single directive arg ([#2989](https://github.com/GatoGraphQL/GatoGraphQL/pull/2989))
- Made params to method `assertIsValid` non-nullable ([#2998](https://github.com/GatoGraphQL/GatoGraphQL/pull/2998))

## Added

- GraphQL type `StringListValueJSONObject` ([#2991](https://github.com/GatoGraphQL/GatoGraphQL/pull/2991))
- YouTube videos to the extension docs ([#9b95be5](https://github.com/GatoGraphQL/GatoGraphQL/commit/9b95be598df6ac15aa63927922ed32fd83548489))
- Documentation for Premium Extensions ([#2994](https://github.com/GatoGraphQL/GatoGraphQL/pull/2994))
- YouTube video for the Automation extension doc (`v10.0.1`) ([#93311e2](https://github.com/GatoGraphQL/GatoGraphQL/commit/93311e28ead43b18d4e18e1d19e3e0602de176af))

## Improvements

- Settings: For entries of the "Key => Label" type (eg: Extension license keys), allow to use a select input to print preselected values ([#2988](https://github.com/GatoGraphQL/GatoGraphQL/pull/2988))

## Fixed

- Container not generated properly in certain hosts (`@required` attribute disregarded) ([#3009](https://github.com/GatoGraphQL/GatoGraphQL/pull/3009))
- Use the Gutenberg Editor for the plugin CPTs (when Classic Editor active) ([#3011](https://github.com/GatoGraphQL/GatoGraphQL/pull/3011))

## [Extensions] Added

- [DeepL] DeepL plugin
- [Schema Functions] Fields `_arrayFilter`, `_objectFilter` and `_objectFlip`, and directive `@objectFilter`
- [Helper Function Collection] Field `_objectSpreadIDListValueAndFlip`

## [Extensions] Improvements

- [Google Translate] Translate up to 128 strings in each request to the Google Translate API
- [Google Translate] Make the `@strTranslate` directive receive `TranslationProvidersEnum` as the provider arg

## [Extensions] Fixed

- [Schema Functions] `_isEmpty` on empty object must be `false`
