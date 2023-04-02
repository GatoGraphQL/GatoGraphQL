# Helper Fields

Collection of fields providing useful functionality concerning URLs, Date formatting, etc

## Description

This module adds fields to the GraphQL schema which provide commonly-used helper functionality.

Helper fields are **Global Fields**, hence they are added to every single type in the GraphQL schema: in `QueryRoot`, but also in `Post`, `User`, etc.

## Available Helper Fields

This is the list of currently-available helper fields.

### `_urlAddParams`

Adds params to a URL.

### `_urlRemoveParams`

Removes params from a URL.

## Examples

Field `_urlAddParams` receives a `JSONObject` of `param name => value` as input, allowing us to add not only `String` params, but also `[String]`, `[[String]]` and other combinations.

This query:

```graphql
{
  _urlAddParams(
    url: "https://graphql-api.com/",
    params: {
      stringParam: "someValue",
      intParam: 5,
      stringListParam: ["value1", "value2"],
      intListParam: [8, 9, 4],
    }
  )
}
```

...produces:

```json
{
  "data": {
    "_urlAddParams": "https://graphql-api.com/?stringParam=someValue&intParam=5&stringListParam%5B0%5D=value1&stringListParam%5B1%5D=value2&intListParam%5B0%5D=8&intListParam%5B1%5D=9&intListParam%5B2%5D=4",
  }
}
```

In combination with the **Inspect HTTP Request Fields** and **Field to Input** modules, we can retrieve the currently-requested URL when executing a GraphQL custom endpoint or persisted query, add extra parameters, and send another HTTP request to the new URL.

For instance, in this query, we retrieve the IDs of the users in the website and execute a new GraphQL query passing their ID as parameter:

```graphql
{
  users {
    userID: id
    url: _urlAddParams(
      url: "https://somewebsite/endpoint/user-data",
      params: {
        userID: $__userID
      }
    )
    _sendHTTPRequest(input: {
      url: $__url
    }) {
      statusCode
      contentType
      body
    }
  }
}
```
