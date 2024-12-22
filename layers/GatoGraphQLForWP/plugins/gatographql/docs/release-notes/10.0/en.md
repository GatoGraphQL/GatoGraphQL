# Release Notes: 10.0

## Breaking changes

- Changed signature of method `validateDirectiveArgValue`, passing the `$directiveArgs` param when validating a single directive arg ([#2989](https://github.com/GatoGraphQL/GatoGraphQL/pull/2989))

## Improvements

- Settings: For entries of the "Key => Label" type (eg: Extension license keys), allow to use a select input to print preselected values ([#2988](https://github.com/GatoGraphQL/GatoGraphQL/pull/2988))

## [Extensions] Added

- [DeepL] DeepL plugin
- [Schema Functions] Fields `_arrayFilter` and `_objectFilter`, and directive `@objectFilter`

## [Extensions] Improvements

- [Google Translate] Translate up to 128 strings in each request to the Google Translate API
- [Google Translate] Make the `@strTranslate` directive receive `TranslationProvidersEnum` as the provider arg

## [Extensions] Fixed

- [Schema Functions] `_isEmpty` on empty object must be `false`
