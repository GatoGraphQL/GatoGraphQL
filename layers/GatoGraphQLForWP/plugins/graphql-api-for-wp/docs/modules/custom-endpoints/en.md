# Custom Endpoints

Create custom schemas, with custom access rules for different users, each available under its own endpoint.

## Description

A GraphQL server normally exposes a single endpoint for retrieving and posting data.

In addition to supporting the single endpoint, the GraphQL API also makes it possible to create custom endpoints, providing different schema configurations to deal with the needs from different targets, such as:

- Some specific client or user
- A group of users with more access to features (such as PRO users)
- One of the several applications, like mobile app or website
- 3rd-party APIs
- Any other

The custom endpoint is a Custom Post Type, and its permalink is the endpoint. An endpoint with title `"My endpoint"` and slug `my-endpoint` will be accessible under `/graphql/my-endpoint/`.

![Custom endpoint editor](../../images/custom-endpoint.png "Custom endpoint editor")

## Clients

Each custom endpoint has its own set of clients to interact with:

✅ A **GraphiQL client**, available under the endpoint + `?view=graphiql` (eg: `/graphql/my-endpoint/?view=graphiql`).

Module `GraphiQL for Custom Endpoints` must be enabled.

![Custom endpoint's GraphiQL client](../../images/custom-endpoint-graphiql.png "Custom endpoint's GraphiQL client")

✅ An **Interactive schema client**, available under the endpoint + `?view=schema` (eg: `/graphql/my-endpoint/?view=schema`).

Module `Interactive Schema for Custom Endpoints` must be enabled.

![Custom endpoint's Interactive schema](../../images/custom-endpoint-interactive-schema.png "Custom endpoint's Interactive schema")

## Creating a Custom Endpoint

Clicking on the Custom Endpoints link in the menu, it displays the list of all the created custom endpoints:

![Custom Endpoints in the admin](../../images/custom-endpoints-page.png)

A custom endpoint is a custom post type (CPT). To create a new custom endpoint, click on button "Add New GraphQL endpoint", which will open the WordPress editor:

![Creating a new Custom Endpoint](../../images/new-custom-endpoint.png)

When the custom endpoint is ready, publish it, and its permalink becomes its endpoint URL. Links to the endpoint (and source and clients) are shown on the "Custom Endpoint Overview" sidebar panel:

![Custom Endpoint Overview](../../images/custom-endpoint-overview.png)

Appending `?view=source` to the permalink, it will show the endpoint's configuration (as long as the user is logged-in and the user role has access to it):

![Custom endpoint source](../../images/custom-endpoint-source.png)

By default, the custom endpoint has path `/graphql/`, and this value is configurable through the Settings:

![Custom endpoint Settings](../../images/settings-custom-endpoints.png)

### Schema configuration

Defining what elements the schema contains, and what access will users have to it, is defined in the schema configuration.

So we must create a create a schema configuration, and then select it from the dropdown:

![Selecting the schema configuration](../../images/select-schema-configuration.png)

### Organizing Custom Endpoints by Category

On the sidebar panel "Endpoint categories" we can add categories to help manage the Custom Endpoint:

![Endpoint categories when editing a Custom Endpoint](../../images/graphql-custom-endpoint-editor-with-categories.png)

For instance, we can create categories to manage endpoints by client, application, or any other required piece of information:

![List of endpoint categories](../../images/graphql-endpoint-categories.png)

On the list of Custom Endpoints, we can visualize their categories and, clicking on any category link, or using the filter at the top, will only display all entries for that category:

![List of Custom Endpoints with their categories](../../images/graphql-custom-endpoints-with-categories.png)

![Filtering Custom Endpoints by category](../../images/graphql-custom-endpoints-filtering-by-category.png)

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
  <td><strong>Title</strong></td>
  <td>Custom endpoint's title</td>
</tr>
<tr>
  <td><strong>Schema configuration</strong></td>
  <td>From the dropdown, select the schema configuration that applies to the custom endpoint, or one of these options: <ul><li><code>"Default"</code>: the schema configuration is the one selected on the plugin's Settings</li><li><code>"None"</code>: the custom endpoint will be unconstrained</li><li><code>"Inherit from parent"</code>: Use the same schema configuration as the parent custom endpoint.<br/>This option is available when module <code>"API Hierarchy"</code> is enabled, and the custom endpoint has a parent query (selected on the Document settings)</li></ul></td>
</tr>
<tr>
  <td><strong>Options</strong></td>
  <td>Select if the custom endpoint is enabled.<br/>It's useful to disable a custom endpoint it's a parent query in an API hierarchy</td>
</tr>
<tr>
  <td><strong>GraphiQL</strong></td>
  <td>Enable/disable attaching a GraphiQL client to the endpoint, accessible under <code>?view=graphiql</code></td>
</tr>
<tr>
  <td><strong>Interactive Schema</strong></td>
  <td>Enable/disable attaching an Interactive schema client to the endpoint, accessible under <code>?view=schema</code></td>
</tr>
<tr>
  <td><strong>API Hierarchy</strong></td>
  <td>Use the same query as the parent custom endpoint.<br/>This section is enabled when the custom endpoint has a parent query (selected on the Document settings)</td>
</tr>
</tbody>
</table>

These are the inputs in the Document settings:

| Input | Description |
| --- | --- |
| **Permalink** | The endpoint under which the custom endpoint will be available |
| **Categories** | Can categorize the custom endpoint.<br/>Eg: `mobile`, `app`, etc |
| **Excerpt** | Provide a description for the custom endpoint.<br/>This input is available when module `"Excerpt as Description"` is enabled |
| **Page attributes** | Select a parent custom endpoint.<br/>This input is available when module `"API Hierarchy"` is enabled |

<!-- ## Settings

| Option | Description | 
| --- | --- |
| **Endpoint base slug** | The base path for the custom endpoint URL. It defaults to `graphql` | -->

<!-- ## Resources

Video showing how to create a custom endpoint: <a href="https://vimeo.com/413503485" target="_blank">vimeo.com/413503485</a>. -->
