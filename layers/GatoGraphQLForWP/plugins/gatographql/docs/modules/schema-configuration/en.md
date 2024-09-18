# Schema Configuration

A schema configuration is used by the Single Endpoint, Custom Endpoints and Persisted Queries to customize their behavior.

## Description

The GraphQL schema can be configured with those elements provided by the different installed and enabled modules. (Go to the "Modules" page to see the list of all of them.)

Each module can define its configuration through its own block in the Schema Configuration editor. This includes blocks for:

- Setting the schema as public or private
- Enabling “sensitive” data elements
- Namespacing the schema
- Using nested mutations
- Defining response headers
- Define who can access the schema via Access Control Lists
- Set-up HTTP caching via Cache Control Lists
- Many others

![Schema Configuration](../../images/schema-configuration.png)

## Creating a Schema Configuration

Clicking on the Schema Configurations link in the menu, it displays the list of all of them:

<div class="img-width-1024" markdown=1>

![Schema Configurations in the admin](../../images/schema-configurations-page.png)

</div>

A schema configuration is a custom post type (CPT). To create a new schema configuration, click on button "Add New Schema Configuration", which will open the WordPress editor:

<div class="img-width-1024" markdown=1>

![Creating a new Schema Configuration](../../images/new-schema-configuration.png)

</div>

We can optionally remove those configuration blocks which use the corresponding "Default" value from the Settings. The removed block can be added once again via the inserter menu, by clicking on the `+` button at the bottom (please notice that only one instance of each block can be inserted):

<div class="img-width-1024" markdown=1>

![Removing and adding blocks in the Schema Configuration](../../images/schema-configuration-removing-and-adding-blocks.gif)

</div>

To configure the different elements in the schema configuration, click on corresponding input, and it becomes editable. Then select the entries that apply to the configuration. When you are done, click on "Publish":

<div class="img-width-1024" markdown=1>

![Creating a new Schema Configuration](../../images/editing-schema-configuration.gif)

</div>

Once published, the schema configuration becomes available when editing a Custom Endpoint or Persisted Query:

<div class="img-width-1024" markdown=1>

![Selecting the Schema Configuration in the Custom Endpoint](../../images/schema-configuration-in-custom-endpoint.png)

</div>

If the Custom Endpoint or Persisted Query has value `"Default"`, then the schema configuration selected in the Settings for the corresponding endpoint, under tabs "Custom Endpoints" or "Persisted Queries", will be used:

<div class="img-width-1024" markdown=1>

![Default Schema Configuration in the Settings](../../images/settings-default-schema-configuration-for-custom-endpoints.png)

</div>

Opening the schema configuration's permalink in the browser will show its contents (as long as the user is logged-in and the user role has access to it):

<div class="img-width-1024" markdown=1>

![Schema configuration's source](../../images/schema-configuration-source.png)

</div>

## Editor Inputs

These inputs in the body of the editor are shipped with the plugin (more inputs can be added by extensions):

<table>
<thead>
<tr>
    <th>Input</th>
    <th>Description</th>
</tr>
</thead>
<tbody>
<tr>
    <td><strong>Expose Sensitive Data in the Schema</strong></td>
    <td>Expose “sensitive” data elements in the GraphQL schema (such as field <code>Root.roles</code>, field arg <code>Root.posts(status:)</code>, and others), which provide access to potentially private user data. If <code>"Default"</code> is selected, the value selected in the Settings is used.</td>
</tr>
<tr>
    <td><strong>Mutation Scheme</strong></td><td>Define if to enable mutations, and if the redundant fields from the root must be removed. If <code>"Default"</code> is selected, the value selected in the Settings is used.</td>
</tr>
<tr>
    <td><strong>Namespace Types?</strong></td><td>Define if to have all types in the schema automatically namespaced. If <code>"Default"</code> is selected, the value selected in the Settings is used.</td>
</tr>
<tr>
    <td><strong>Access Control Lists</strong></td>
    <td>(If module <code>Access Control</code> is enabled) Manage who can access the schema, by selecting the Access Control Lists that must be applied to the custom endpoint or persisted query</td>
</tr>
<tr>
    <td><strong>Public/Private Schema</strong></td>
    <td>(If module <code>Public/Private Schema</code> is enabled) When access to some a field or directive is denied, there are 2 ways for the API to behave:<ul><li><code>"Public"</code>: Provide an error message to the user, indicating why access is denied. This behavior makes the metadata from the schema always available.</li><li><code>"Private"</code>: The error message indicates that the field or directive does not exist. This behavior exposes the metadata from the schema only to those users who can access it.</li></ul>If <code>"Default"</code> is selected, the value selected in the Settings is used.</td>
</tr>
<tr>
    <td><strong>Cache Control Lists</strong></td>
    <td>(If module <code>Cache Control</code> is enabled) Manage the behavior of HTTP caching, by selecting the Cache Control Lists that must be applied to the custom endpoint or persisted query</td>
</tr>
<tr>
    <td><em>Many others</em></td>
    <td>(Check the corresponding enabled modules...)</td>
</tr>
</tbody>
</table>

These are the inputs in the Document settings:

| Input | Description |
| --- | --- |
| **Excerpt** | Provide a description for the schema configuration.<br/>This input is available when module `Excerpt as Description` is enabled |
