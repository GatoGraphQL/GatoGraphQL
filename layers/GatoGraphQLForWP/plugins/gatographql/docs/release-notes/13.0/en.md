# Release Notes: 13.0

## Breaking changes

- Replaced methods in `Logger`: `logInfo` and `logError` => `log(severity: ...)` ([#3093](https://github.com/GatoGraphQL/GatoGraphQL/pull/3093))
- Split `Logger` service into `Logger` and `SystemLogger` ([#3098](https://github.com/GatoGraphQL/GatoGraphQL/pull/3098))
- Moved "Logs" from "General" tab to new "Logs" tab in the Settings ([#3103](https://github.com/GatoGraphQL/GatoGraphQL/pull/3103))

## Added

- Enable logs by severity in the Settings ([#3095](https://github.com/GatoGraphQL/GatoGraphQL/pull/3095))
- Add Logs page to the menu, to browse the logs ([#3100](https://github.com/GatoGraphQL/GatoGraphQL/pull/3100))
- Split Logs by sources and date ([#3100](https://github.com/GatoGraphQL/GatoGraphQL/pull/3100))
- Logs count badge in the Logs menu link ([#3105](https://github.com/GatoGraphQL/GatoGraphQL/pull/3105))

### Improvements

- Print the severity (`SUCCESS`/`INFO`/`WARNING`/`ERROR`) in the logs ([#3099](https://github.com/GatoGraphQL/GatoGraphQL/pull/3099))
- Use the Logger also when using plugin as standalone (without WordPress) ([#3104](https://github.com/GatoGraphQL/GatoGraphQL/pull/3104))

### Fixed

- Error type for "Custom post does not exist" was missing from union types ([#3109](https://github.com/GatoGraphQL/GatoGraphQL/pull/3109))
- Do not show the notification count badge in the plugin menu if the Logs page is not enabled (`v13.0.2`) ([#3112](https://github.com/GatoGraphQL/GatoGraphQL/pull/3112))
