# Release Notes: 6.0

## Breaking changes

- Removed custom endpoints and persisted queries ([#2852](https://github.com/GatoGraphQL/GatoGraphQL/pull/2852))
- The single endpoint is enabled by default ([#2859](https://github.com/GatoGraphQL/GatoGraphQL/pull/2859))
- The single endpoint GraphiQL/Voyager clients are disabled default ([#2860](https://github.com/GatoGraphQL/GatoGraphQL/pull/2860))
- The Schema Configuration module is disabled by default ([#2848](https://github.com/GatoGraphQL/GatoGraphQL/pull/2848))
- The schema tutorial page is hidden by default ([#2856](https://github.com/GatoGraphQL/GatoGraphQL/pull/2856))

## Improvements

- Do not display Endpoint Categories if there are no endpoint CPTs enabled ([#2849](https://github.com/GatoGraphQL/GatoGraphQL/pull/2849))
- Hide "API Hierarchy" module if there are no endpoint CPTs enabled ([#2850](https://github.com/GatoGraphQL/GatoGraphQL/pull/2850))
- Hide "Excerpt as description" module if there are no CPTs enabled ([#2851](https://github.com/GatoGraphQL/GatoGraphQL/pull/2851))
- Display the "Enable Logs?" settings only when some extension is using it ([#2853](https://github.com/GatoGraphQL/GatoGraphQL/pull/2853))
- Hide the Schema tutorial page by default ([#2854](https://github.com/GatoGraphQL/GatoGraphQL/pull/2854))

## Fixed

## [PRO] Improvements

- If `from` email not provided in `_sendEmail` mutation, use the blog's admin email
