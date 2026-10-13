# Release Notes: 20.0

## Breaking changes

- Bumped the minimum required WordPress version to 6.5 (#3405)
- Removed `AbstractPlugin::getPluginNamespaceForDB()` and `PluginMetadata::PLUGIN_NAMESPACE_FOR_DB`: an extension overriding the method must override `getPluginNamespaceForEntityTypeNames()` instead, and one reading the constant must read `PLUGIN_NAMESPACE_FOR_ENTITY_TYPE_NAMES`
- The "Settings" block in the Schema Configuration now defaults to "Allow access", as the "Settings" module does, so a configuration that never set the behavior allows only its listed options to non-administrators (#3409)
- Constant classes that hold a set of values are now native PHP enums, under namespace `…\Enums` instead of `…\Constants` (such as `LoggerSeverity`, `Behaviors`, `CommentStatus` and `LicenseStatus`). Code using one of their values as a string must read `->value`, and methods that receive or return those values, such as `LoggerInterface::log()`, now take and return the enum ([#3414](https://github.com/GatoGraphQL/GatoGraphQL/pull/3414))

## Added

- Added module "GraphQL Spec Compliance", with options to follow the GraphQL spec where Gato GraphQL behaves differently: pass a `null` in a non-null field up to its nearest nullable parent, coerce scalar and enum values (printing `ID` as a string), and add the response `path` to errors. All of them are disabled by default ([#3432](https://github.com/GatoGraphQL/GatoGraphQL/pull/3432))

## Fixed

- The content of a Custom HTML block (`core/html`) is read again on WordPress 7.1, which registers it without saying it is kept in the block's HTML, so the block came back with no content (#3415)
- A request that runs many GraphQL queries, such as a translation run over many entities, no longer runs out of memory (#3419)
- A post with HTML outside of its blocks, such as one converted to blocks only in part, can now be read as blocks, that HTML being a Classic block (`core/freeform`). The whole post used to fail for not being block content (#3420)
- In a bundle, such as Gato AI Translations for Polylang, a doc that is only in English now shows the notice linking to the website in your language ([#3425](https://github.com/GatoGraphQL/GatoGraphQL/pull/3425))
- The plugin's docs, and their links to the website, are in the language each user picked for the admin, not in the site's language ([#3426](https://github.com/GatoGraphQL/GatoGraphQL/pull/3426))
- A post no longer fails to be read as blocks when one of its blocks leaves out an attribute that defaults to true/false or a number, such as a Video block without `controls` ([#3427](https://github.com/GatoGraphQL/GatoGraphQL/pull/3427))
- A block's true/false attribute read from its HTML, such as `autoplay` or `controls` in a Video block, is now `true` when the HTML attribute is there and `false` when it is not, instead of an empty string or the block's default ([#3427](https://github.com/GatoGraphQL/GatoGraphQL/pull/3427))
- A block attribute whose selector is `*` is now read from the block's own HTML element ([#3427](https://github.com/GatoGraphQL/GatoGraphQL/pull/3427))
- A post whose content is only whitespace now has no blocks, instead of failing for not being block content ([#3427](https://github.com/GatoGraphQL/GatoGraphQL/pull/3427))
- A fragment spread within several fragments now resolves its fields in each of them, also when one of those fragments does not apply, which made the fields go missing from the others ([#3428](https://github.com/GatoGraphQL/GatoGraphQL/pull/3428))
- An inline fragment within a fragment, such as `... on Post { title }`, is now only applied to objects of its type ([#3428](https://github.com/GatoGraphQL/GatoGraphQL/pull/3428))
