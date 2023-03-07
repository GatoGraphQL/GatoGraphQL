# Release Notes: 1.0

## Browse "Additional Documentation" when editing a Schema Configuration

Documentation for additional features in the GraphQL API can now be browsed when editing a Schema Configuration CPT, on the editor's sidebar:

![Additional Documentation in Schema Configuration CPT](../../images/releases/v1.0/schema-configuration-additional-documentation.png)

When clicking on any of the links, a modal window is displayed with the corresponding documentation:

![Modal window with documentation](../../images/releases/v1.0/schema-configuration-additional-documentation-modal.png)

## Added documentation for PRO modules and their corresponding Schema Configuration functionalities

PRO modules are installed by the GraphQL API PRO plugin, which has been released alongside the GraphQL API for WordPress v1.0.

Documentation for the corresponding PRO features can be visualized in the plugin, by:

- Clicking on the corresponding module's "View details" link, on the Modules page
- Clicking on the corresponding block's "View details" link, when editing a Schema Configuration

![PRO modules in the Modules page](../../images/releases/v1.0/pro-documentation-modules.png)

![PRO blocks when editing a Schema Configuration](../../images/releases/v1.0/pro-documentation-schema-configuration.png)

![Clicking on "View details" displays the documentation](../../images/releases/v1.0/pro-documentation-schema-configuration-modal.png)

## The Settings page has tabs displayed vertically

Due to the great number of modules in the plugin, the Settings page required several rows to display all tabs, which was not very polished.

Now, the Settings page displays the tabs vertically, making it easier to browse them:

![Settings page](../../images/releases/v1.0/settings-page.png)

## Added documentation page "Use Cases, Best Practices, and Recipes"

The new documentation page "Use Cases, Best Practices, and Recipes" has been added to the menu.

It describes when it makes sense to use the GraphQL API and what's the best way to use it (and also GraphQL in general), and provides a handful of recipes (involving GraphQL queries) to help users achieve their goals.

![Use Cases, Best Practices, and Recipes page](../../images/releases/v1.0/recipes-page.png)

## Fixed

- Made field `Comment.type` of type `CommentTypeEnum` (previously was `String`)
- Avoid error from loading non-existing translation files
