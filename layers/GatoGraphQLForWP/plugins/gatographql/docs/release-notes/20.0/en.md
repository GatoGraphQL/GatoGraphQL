# Release Notes: 20.0

## Breaking changes

- Bumped the minimum required WordPress version to 6.5 (#3405)
- Removed `AbstractPlugin::getPluginNamespaceForDB()` and `PluginMetadata::PLUGIN_NAMESPACE_FOR_DB`: an extension overriding the method must override `getPluginNamespaceForEntityTypeNames()` instead, and one reading the constant must read `PLUGIN_NAMESPACE_FOR_ENTITY_TYPE_NAMES`
- The "Settings" block in the Schema Configuration now defaults to "Allow access", as the "Settings" module does, so a configuration that never set the behavior allows only its listed options to non-administrators (#3409)
- Constant classes that hold a set of values are now native PHP enums, under namespace `…\Enums` instead of `…\Constants` (such as `LoggerSeverity`, `Behaviors`, `CommentStatus` and `LicenseStatus`). Code using one of their values as a string must read `->value`, and methods that receive or return those values, such as `LoggerInterface::log()`, now take and return the enum ([#3414](https://github.com/GatoGraphQL/GatoGraphQL/pull/3414))

## Fixed

- The content of a Custom HTML block (`core/html`) is read again on WordPress 7.1, which registers it without saying it is kept in the block's HTML, so the block came back with no content (#3415)
- A request that runs many GraphQL queries, such as a translation run over many entities, no longer runs out of memory (#3419)
- A post with HTML outside of its blocks, such as one converted to blocks only in part, can now be read as blocks, that HTML being a Classic block (`core/freeform`). The whole post used to fail for not being block content (#3420)
