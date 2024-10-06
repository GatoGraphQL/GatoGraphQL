# Release Notes: 6.0

## Breaking changes

**Action required:** When updating the plugin (i.e. not installing anew), you need to deactivate and then re-activate the plugin. Until then, the "GraphiQL" and "Schema" items won't appear on the menu (due to the newly-added "Schema Editing Access" module, see below)

- Removed custom endpoints and persisted queries ([#2852](https://github.com/GatoGraphQL/GatoGraphQL/pull/2852))
- The single endpoint is enabled by default ([#2859](https://github.com/GatoGraphQL/GatoGraphQL/pull/2859))
- The single endpoint GraphiQL/Voyager clients are disabled default ([#2860](https://github.com/GatoGraphQL/GatoGraphQL/pull/2860))
- The Schema Configuration module is disabled by default ([#2848](https://github.com/GatoGraphQL/GatoGraphQL/pull/2848))
- The schema tutorial page is hidden by default ([#2856](https://github.com/GatoGraphQL/GatoGraphQL/pull/2856))
- The settings under "Schema Elements Configuration" (new item) need to be set again ([#2861](https://github.com/GatoGraphQL/GatoGraphQL/pull/2861))

## Improvements

- Tested up to WordPress 6.7 ([#2887](https://github.com/GatoGraphQL/GatoGraphQL/pull/2887))
- Do not display Endpoint Categories if there are no endpoint CPTs enabled ([#2849](https://github.com/GatoGraphQL/GatoGraphQL/pull/2849))
- Hide "API Hierarchy" module if there are no endpoint CPTs enabled ([#2850](https://github.com/GatoGraphQL/GatoGraphQL/pull/2850))
- Hide "Excerpt as description" module if there are no CPTs enabled ([#2851](https://github.com/GatoGraphQL/GatoGraphQL/pull/2851))
- Display the "Enable Logs?" settings only when some extension is using it ([#2853](https://github.com/GatoGraphQL/GatoGraphQL/pull/2853))
- Hide the Schema tutorial page by default ([#2854](https://github.com/GatoGraphQL/GatoGraphQL/pull/2854))
- Reorganized the Settings, splitting "Schema Configuration" into 2 elements: "Schema Configuration" and  "Schema Elements Configuration" ([#2861](https://github.com/GatoGraphQL/GatoGraphQL/pull/2861))
- Improved documentation for extensions ([#2866](https://github.com/GatoGraphQL/GatoGraphQL/pull/2866))
- Added links to online docs on the Settings page ([#2875](https://github.com/GatoGraphQL/GatoGraphQL/pull/2875))

### Added "Schema Editing Access" module ([#2877](https://github.com/GatoGraphQL/GatoGraphQL/pull/2877))

Grant non-admin users access to create and manage endpoints.

Decide which users can access the GraphiQL and Interactive schema clients in the admin, and are able to edit the GraphQL schema, by selecting the appropriate configuration from the dropdown in the "Plugin Configuration > Schema Editing Access" tab on the Settings page:

- `Users with capability: "gatogql_manage_graphql_schema"`
- `Users with role: "administrator"`
- `Users with any role: "administrator", "editor"`
- `Users with any role: "administrator", "editor", "author"`

The first option (`Users with capability: "gatogql_manage_graphql_schema"`) is selected by default, granting access to users with the `administrator` role only.

<div class="img-width-1024" markdown=1>

![Configuring the schema editing access in the Settings](../../images/settings-schema-editing-access.png "Configuring the schema editing access in the Settings")

</div>

## Fixed

## [PRO] Improvements

- If `from` email not provided in `_sendEmail` mutation, use the blog's admin email
