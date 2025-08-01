# Release Notes: 13.1

## Added

- Enable extensions if required theme is active ([#3113](https://github.com/GatoGraphQL/GatoGraphQL/pull/3113))
- Allow extension dependencies on themes ([#3115](https://github.com/GatoGraphQL/GatoGraphQL/pull/3115))
- Documentation for new Bricks Premium Extension ([#3117](https://github.com/GatoGraphQL/GatoGraphQL/pull/3117))
- Documentation for the new `@exportFrom` directive from **Multiple Query Execution** ([#3121](https://github.com/GatoGraphQL/GatoGraphQL/pull/3121))
- Documentation for the updated `@passOnwards` directive from **Field to Input** ([#3122](https://github.com/GatoGraphQL/GatoGraphQL/pull/3122))
- Added `NonEmptyString` custom scalar ([#3124](https://github.com/GatoGraphQL/GatoGraphQL/pull/3124))
- Added `IdentifierString` custom scalar ([#3125](https://github.com/GatoGraphQL/GatoGraphQL/pull/3125))
- Added new input `IncludeExcludeFilterInput` ([#3127](https://github.com/GatoGraphQL/GatoGraphQL/pull/3127))
- Added documentation for new fields `_strArrayReplace` and `_strArrayReplaceMultiple` from the **PHP Functions via Schema** extension ([#3129](https://github.com/GatoGraphQL/GatoGraphQL/pull/3129))
- Added field `optionObjectValues` ([#3133](https://github.com/GatoGraphQL/GatoGraphQL/pull/3133))
- Support for "partial" errors (shown in the response, but not bubbled up on nested directives) ([#3135](https://github.com/GatoGraphQL/GatoGraphQL/pull/3135))
- Support markdown in logs ([#3136](https://github.com/GatoGraphQL/GatoGraphQL/pull/3136))

## Improvements

- Allow reinstalling plugin initial data when plugin/theme dependency is activated/deactivated ([#3119](https://github.com/GatoGraphQL/GatoGraphQL/pull/3119))
- Made `customPostType` param on the `updateCustomPost` mutation optional ([#3120](https://github.com/GatoGraphQL/GatoGraphQL/pull/3120))
- Use `NonEmptyString` for `slug` on custom post mutations([#3126](https://github.com/GatoGraphQL/GatoGraphQL/pull/3126))
- Assign default value to configuration items added after the Settings was saved to DB ([#3130](https://github.com/GatoGraphQL/GatoGraphQL/pull/3130))
- Print errors before warnings in the response ([#3134](https://github.com/GatoGraphQL/GatoGraphQL/pull/3134))

## Fixed

- Avoid overriding logic: Querying "attachment" doesn't work in an array ([#3123](https://github.com/GatoGraphQL/GatoGraphQL/pull/3123))
- Returning no results from `get_posts` when passing many CPTs ([#3128](https://github.com/GatoGraphQL/GatoGraphQL/pull/3128))
- Handle empty response when LemonSqueezy service is down (#3139)
- Exception thrown when multiple fields have errors (`v13.1.1`) ([#3139](https://github.com/GatoGraphQL/GatoGraphQL/pull/3139))
- Exception in Extensions page (`v13.1.1`) ([#3140](https://github.com/GatoGraphQL/GatoGraphQL/pull/3140))
