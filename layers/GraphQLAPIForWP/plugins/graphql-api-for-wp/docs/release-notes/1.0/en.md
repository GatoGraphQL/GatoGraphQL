# Release Notes: 1.0

## Browse "Additional Documentation" when editing a Schema Configuration

Documentation for additional features in the GraphQL API can now be browsed when editing a Schema Configuration CPT, on the editor's sidebar:

![Additional Documentation in Schema Configuration CPT](../../images/releases/v1.0/schema-configuration-additional-documentation.png)

When clicking on any of the links, a modal window is displayed with the corresponding documentation:

![Modal window with documentation](../../images/releases/v1.0/schema-configuration-additional-documentation-modal.png)

## Inspect properties when editing Custom Endpoints and Persisted Query Endpoints

A sidebar component has been added to the editor for Custom Endpoints and Persisted Query Endpoints, displaying links to:

- The endpoint URL
- Its source configuration
- Its GraphiQL client (for the Custom Endpoint)
- Its interactive schema client (for the Custom Endpoint)

![Custom Endpoint Properties](../../images/custom-endpoint-properties.png)

## Added documentation for PRO modules and their corresponding Schema Configuration functionalities

PRO modules are installed by the GraphQL API PRO plugin, which has been released alongside the GraphQL API for WordPress v1.0.

Documentation for the corresponding PRO features can be visualized in the plugin, by:

- Clicking on the corresponding module's "View details" link, on the Modules page
- Clicking on the corresponding block's "View details" link, when editing a Schema Configuration

![PRO modules in the Modules page](../../images/releases/v1.0/pro-documentation-modules.png)

![PRO blocks when editing a Schema Configuration](../../images/releases/v1.0/pro-documentation-schema-configuration.png)

![Clicking on "View details" displays the documentation](../../images/releases/v1.0/pro-documentation-schema-configuration-modal.png)

## The Settings page has been re-designed

Due to the great number of modules in the plugin, the Settings page required several rows to display all tabs, which was not very polished.

Now, the Settings page organizes all modules into 2 levels, and displays tabs vertically, making it easier to browse them:

![Settings page](../../images/releases/v1.0/settings-page.png)

## Reset settings, and choose to use "safe" or "unsafe" default settings

A GraphQL API may be exposed publicly or only privately, and depending on which is the case there are options and features that need to be restricted or not.

For instance, querying values from the `wp_options` table can be unrestrained whenever building a static site (in which case the WordPress site may be on the developer's laptop, not exposed to the Internet), but must be limited to a handful of options (or even none at all) for a public API, for security reasons.

The plugin has provided "safe" and "unsafe" default behaviors for the Settings since v0.8, but it required to define a PHP constant in `wp-config.php` to switch from one to the other, and then manually delete all Settings values that had to be regenerated.

Now it is possible to do the switch directly via the UI: The new "Plugin Management" tab in the Settings Page has an item "Reset Settings" which restores the default settings values, and allows to decide if to use the "safe" or "unsafe" default behaviors.

![Reset Settings page](../../images/releases/v1.0/reset-settings-page.png)

## Added documentation page "Use Cases, Best Practices, and Recipes"

The new documentation page "Use Cases, Best Practices, and Recipes" has been added to the menu.

It describes when it makes sense to use the GraphQL API and what's the best way to use it (and also GraphQL in general), and provides a handful of recipes (involving GraphQL queries) to help users achieve their goals.

![Use Cases, Best Practices, and Recipes page](../../images/releases/v1.0/recipes-page.png)

## Configuration blocks in the the Schema Configuration CPT editor can be removed (and added again)

When creating a Schema Configuration, the new entry contains the whole list of options to configure:

![New Schema Configuration](../../images/new-schema-configuration.png)

If we need to configure only a handful of items, displaying all blocks in the editor makes it difficult to visualize.

Now, the Gutenberg template (containing the blocks) is not locked anymore, then we can delete the blocks that we don't need. And if we need to add a block again, it can be done from the inserter (notice that every block can only be added once):

![Removing and adding blocks in the Schema Configuration](../../images/removing-and-adding-blocks-in-schema-configuration.gif)

## Fixed

- Made field `Comment.type` of type `CommentTypeEnum` (previously was `String`)
- Avoid error from loading non-existing translation files
- Updated all documentation images

## Breaking changes

- Environment variable `ENABLE_UNSAFE_DEFAULTS` has been renamed to `SETTINGS_OPTION_ENABLE_UNSAFE_DEFAULT_BEHAVIOR`, and "Reset Settings" must be clicked on the "Plugin Management" tab of the Settings page for the new value to be applied.
