# Release Notes: 13.0

## Breaking changes

- Replaced methods in `Logger`: `logInfo` and `logError` => `log(severity: ...)` ([#3093](https://github.com/GatoGraphQL/GatoGraphQL/pull/3093))
- Split `Logger` service into `Logger` and `SystemLogger` ([#3098](https://github.com/GatoGraphQL/GatoGraphQL/pull/3098))

## Added

- Enable logs by severity in the Settings ([#3095](https://github.com/GatoGraphQL/GatoGraphQL/pull/3095))

### Improvements

- Print the severity (`SUCCESS`/`INFO`/`WARNING`/`ERROR`) in the logs ([#3099](https://github.com/GatoGraphQL/GatoGraphQL/pull/3099))