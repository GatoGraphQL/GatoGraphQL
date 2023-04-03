# Helper Fields

Collection of fields providing useful functionality concerning URLs, Date formatting, etc

## Description

This module adds fields to the GraphQL schema which provide commonly-used helper functionality.

Helper fields are **Global Fields**, hence they are added to every single type in the GraphQL schema: in `QueryRoot`, but also in `Post`, `User`, etc.

## Available Helper Fields

This is the list of currently-available helper fields.

### `_urlAddParams`

Adds params to a URL.

The parameters input is a `JSONObject` of `param name => value`, allowing us to pass values of multiple types, including `String`, `Int`, List (eg: `[String]`) and also `JSONObject`.

This query:

```graphql
{
  _urlAddParams(
    url: "https://graphql-api.com",
    params: {
      stringParam: "someValue",
      intParam: 5,
      stringListParam: ["value1", "value2"],
      intListParam: [8, 9, 4],
      objectParam: {
        "1st": "1stValue",
        "2nd": 2,
        "3rd": ["uno", 2.5]
        "4th": {
          nestedIn: "nestedOut"
        }
      }
    }
  )
}
```

...produces:

```json
{
  "data": {
    "_urlAddParams": "https:\/\/graphql-api.com?stringParam=someValue&intParam=5&stringListParam%5B0%5D=value1&stringListParam%5B1%5D=value2&intListParam%5B0%5D=8&intListParam%5B1%5D=9&intListParam%5B2%5D=4&objectParam%5B1st%5D=1stValue&objectParam%5B2nd%5D=2&objectParam%5B3rd%5D%5B0%5D=uno&objectParam%5B3rd%5D%5B1%5D=2.5&objectParam%5B4th%5D%5BnestedIn%5D=nestedOut"
  }
}
```

(The decoded URL is `"https://graphql-api.com?stringParam=someValue&intParam=5&stringListParam[0]=value1&stringListParam[1]=value2&intListParam[0]=8&intListParam[1]=9&intListParam[2]=4&objectParam[1st]=1stValue&objectParam[2nd]=2&objectParam[3rd][0]=uno&objectParam[3rd][1]=2.5&objectParam[4th][nestedIn]=nestedOut"`.)

Please notice that `null` values are not added to the URL.

This query:

```graphql
{
  _urlAddParams(
    url: "https://graphql-api.com",
    params: {
      stringParam: null,
      listParam: [1, null, 3],
      objectParam: {
        uno: null,
        dos: 2
      }
    }
  )
}
```

...produces:

```json
{
  "data": {
    "_urlAddParams": "https:\/\/graphql-api.com?listParam%5B0%5D=1&listParam%5B2%5D=3&objectParam%5Bdos%5D=2"
  }
}
```

(The decoded URL is `"https://graphql-api.com?listParam[0]=1&listParam[2]=3&objectParam[dos]=2"`.)

### `_urlRemoveParams`

Removes params from a URL.

## Examples

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
