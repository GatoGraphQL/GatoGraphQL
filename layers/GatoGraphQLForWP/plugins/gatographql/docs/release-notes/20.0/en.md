# Release Notes: 20.0

## Breaking changes

- Bumped the minimum required WordPress version to 6.5 (#3405)
- Removed `AbstractPlugin::getPluginNamespaceForDB()` and `PluginMetadata::PLUGIN_NAMESPACE_FOR_DB`: an extension overriding the method must override `getPluginNamespaceForEntityTypeNames()` instead, and one reading the constant must read `PLUGIN_NAMESPACE_FOR_ENTITY_TYPE_NAMES`
- The "Settings" block in the Schema Configuration now defaults to "Allow access", as the "Settings" module does, so a configuration that never set the behavior allows only its listed options to non-administrators (#3409)
