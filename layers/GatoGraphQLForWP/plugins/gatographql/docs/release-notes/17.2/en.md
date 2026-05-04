# Release Notes: 17.2

### Added

- Field `Root.blockTypes` to query data from the Block Type Registry ([#3291](https://github.com/GatoGraphQL/GatoGraphQL/pull/3291))
- Cache compiled queries to disk ([#3303](https://github.com/GatoGraphQL/GatoGraphQL/pull/3303))

### Improvements

- Tested up to WordPress 7.0 ([#3289](https://github.com/GatoGraphQL/GatoGraphQL/pull/3289))
- Support AI Connectors from WordPress 7.0 ([#3290](https://github.com/GatoGraphQL/GatoGraphQL/pull/3290))
- Updated documentation for Translation extension, now supporting Gemini as a translation service provider ([#3292](https://github.com/GatoGraphQL/GatoGraphQL/pull/3292))
- Allow the custom post `author` to be null (e.g. when the post has no assigned user / author ID 0) ([#3293](https://github.com/GatoGraphQL/GatoGraphQL/pull/3293))
- Execute GraphQL queries much faster ([#3294](https://github.com/GatoGraphQL/GatoGraphQL/pull/3294), [#3295](https://github.com/GatoGraphQL/GatoGraphQL/pull/3295), [#3298](https://github.com/GatoGraphQL/GatoGraphQL/pull/3298))
- Consume less memory ([#3296](https://github.com/GatoGraphQL/GatoGraphQL/pull/3296), [#3297](https://github.com/GatoGraphQL/GatoGraphQL/pull/3297))

### Fixed

- Handle null response from license API (eg: when access is forbidden via network) ([#3288](https://github.com/GatoGraphQL/GatoGraphQL/pull/3288))
