# Release Notes: 17.2

### Added

- Field `Root.blockTypes` to query data from the Block Type Registry ([#3291](https://github.com/GatoGraphQL/GatoGraphQL/pull/3291))
### Improvements

- Tested up to WordPress 7.0 ([#3289](https://github.com/GatoGraphQL/GatoGraphQL/pull/3289))
- Support AI Connectors from WordPress 7.0 ([#3290](https://github.com/GatoGraphQL/GatoGraphQL/pull/3290))
- Updated documentation for Translation extension, now supporting Gemini as a translation service provider ([#3292](https://github.com/GatoGraphQL/GatoGraphQL/pull/3292))
- Allow the custom post `author` to be null (e.g. when the post has no assigned user / author ID 0) ([#3293](https://github.com/GatoGraphQL/GatoGraphQL/pull/3293))
- Improve performance of parsing, compiling and executing GraphQL query ([#3294](https://github.com/GatoGraphQL/GatoGraphQL/pull/3294), [#3295](https://github.com/GatoGraphQL/GatoGraphQL/pull/3295))

### Fixed

- Handle null response from license API (eg: when access is forbidden via network) ([#3288](https://github.com/GatoGraphQL/GatoGraphQL/pull/3288))
