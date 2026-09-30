# Release Notes: 20.0

## Breaking changes

- Bumped the minimum required WordPress version to 6.5 (#3405)
- Removed `AbstractPlugin::getPluginNamespaceForDB()` and `PluginMetadata::PLUGIN_NAMESPACE_FOR_DB`: an extension overriding the method must override `getPluginNamespaceForEntityTypeNames()` instead, and one reading the constant must read `PLUGIN_NAMESPACE_FOR_ENTITY_TYPE_NAMES`
- The "Settings" block in the Schema Configuration now defaults to "Allow access", as the "Settings" module does, so a configuration that never set the behavior allows only its listed options to non-administrators (#3409)

## Fixed

- The content of a Custom HTML block (`core/html`) is read again on WordPress 7.1, which registers it without saying it is kept in the block's HTML, so the block came back with no content (#3415)
