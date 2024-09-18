# Release Notes: 0.10

## Configure several modules via the Schema Configuration

The Schema Configuration now allows to configure several additional modules:

- Custom Posts
- Tags
- Categories
- Settings
- Custom Post Meta
- Comment Meta
- Taxonomy Meta
- User Meta

As a result, endpoints can have a unique configuration that makes sense only for them, including:

- What CPTs and taxonomies (for tags and categories) can be accessed
- What meta entries can be queried

For instance, we can now define the Custom Post Types that can be queried for some specific endpoint, by editing block "Custom Posts" in the corresponding Schema Configuration:

<div class="img-width-1024" markdown=1>

![Selecting the allowed Custom Post Types in the Schema Configuration](../../images/customposts-schema-configuration-queryable-cpts.png)

</div>

_Please notice: Because of the new blocks added, we will need to click on the "Reset the template" button when editing a Schema Configuration entry created on a previous version of the plugin._

## Added docs for modules "Tags" and "Categories"

Added documentation for modules "Tags" and "Categories", displayed when clicking on "View details" on the corresponding entries on the Modules page.

<div class="img-width-1024" markdown=1>

![Documentation for "Tags" module](../../images/releases/v010/tags-doc.png)

</div>

## Breaking changes

### Modified value for "allow" behavior option in settings

The value for the `allow` behavior option in the settings has been modified (from `"allowlist"` to `"allow"`), so this option must be selected and stored again for the following modules:

- All the meta modules (Custom Post Meta, User Meta, Comment Meta and Taxonomy Meta)
- Settings module
