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

<a href="../../images/custom-endpoint.png" target="_blank">![Creating a custom endpoint](../../images/custom-endpoint.png "Creating a custom endpoint")</a>

## Clients

Each custom endpoint has its own set of clients to interact with:

✅ A **GraphiQL client**, available under the endpoint + `?view=graphiql` (eg: `/graphql/my-endpoint/?view=graphiql`).

Module `GraphiQL for Custom Endpoints` must be enabled.

<a href="../../images/custom-endpoint-graphiql.png" target="_blank">![Custom endpoint's GraphiQL client](../../images/custom-endpoint-graphiql.png "Custom endpoint's GraphiQL client")</a>

✅ An **Interactive schema client**, available under the endpoint + `?view=schema` (eg: `/graphql/my-endpoint/?view=schema`).

Module `Interactive Schema for Custom Endpoints` must be enabled.

<a href="../../images/custom-endpoint-interactive-schema.png" target="_blank">![Custom endpoint's Interactive schema](../../images/custom-endpoint-interactive-schema.png "Custom endpoint's Interactive schema")</a>

## How to use

Clicking on the Custom Endpoints link in the menu, it displays the list of all the created custom endpoints:

<a href="../../images/custom-endpoints-page.png" target="_blank">![Custom Endpoints in the admin](../../images/custom-endpoints-page.png)</a>

A custom endpoint is a custom post type (CPT). To create a new custom endpoint, click on button "Add New GraphQL endpoint", which will open the WordPress editor:

<a href="../../images/new-custom-endpoint.png" target="_blank">![Creating a new Custom Endpoint](../../images/new-custom-endpoint.png)</a>

When the endpoint is ready, publish it, and its permalink becomes its endpoint:

<a href="../../images/publishing-custom-endpoint.gif" target="_blank">![Publishing the custom endpoint](../../images/publishing-custom-endpoint.gif)</a>

Appending `?view=source` to the permalink, it will show the endpoint's configuration (as long as the user has access to it):

<a href="../../images/custom-endpoint-source.png" target="_blank">![Custom endpoint source](../../images/custom-endpoint-source.png)</a>

By default, the custom endpoint has path `/graphql/`, and this value is configurable through the Settings:

<a href="../../images/settings-custom-endpoints.png" target="_blank">![Custom endpoint Settings](../../images/settings-custom-endpoints.png)</a>

## Editor Inputs

These inputs in the body of the editor are shipped with the plugin (more inputs can be added by extensions):

<table markdown="1">
<thead>
<tr>
    <th>Input</th>
    <th>Description</th>
</tr>
</thead>
<tbody>
<tr>
  <td>**Title**</td>
  <td>Custom endpoint's title</td>
</tr>
<tr>
  <td>**Schema configuration**</td>
  <td>From the dropdown, select the schema configuration that applies to the custom endpoint, or one of these options: <ul markdown="1"><li>`"Default"`: the schema configuration is the one selected on the plugin's Settings</li><li>`"None"`: the custom endpoint will be unconstrained</li><li>`"Inherit from parent"`: Use the same schema configuration as the parent custom endpoint.<br/>This option is available when module `"API Hierarchy"` is enabled, and the custom endpoint has a parent query (selected on the Document settings)</li></ul></td>
</tr>
<tr>
  <td>**Options**</td>
  <td>Select if the custom endpoint is enabled.<br/>It's useful to disable a custom endpoint it's a parent query in an API hierarchy</td>
</tr>
<tr>
  <td>**GraphiQL**</td>
  <td>Enable/disable attaching a GraphiQL client to the endpoint, accessible under `?view=graphiql`</td>
</tr>
<tr>
  <td>**Interactive Schema**</td>
  <td>Enable/disable attaching an Interactive schema client to the endpoint, accessible under `?view=schema`</td>
</tr>
<tr>
  <td>**API Hierarchy**</td>
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
| **Base path** | The base path for the custom endpoint URL. It defaults to `graphql` | -->

## Resources

Video showing how to create a custom endpoint: <https://vimeo.com/413503485>.
