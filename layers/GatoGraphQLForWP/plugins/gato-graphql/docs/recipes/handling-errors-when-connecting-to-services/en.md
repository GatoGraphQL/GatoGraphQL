# Handling errors when connecting to services

We can encounter different types of errors when fetching data from an external API.

For instance, consider the following query:

```graphql
{
  externalData: _sendJSONObjectItemHTTPRequest(
    input: {
      url: "https://newapi.getpop.org/wp-json/wp/v2/posts/8888/"
    }
  )
    
  postTitle: _objectProperty(
    object: $__externalData,
    by: { path: "title.rendered"}
  )
}
```

If the Internet connection went down, then field `_sendJSONObjectItemHTTPRequest` will trigger an error:

```json
{
  "errors": [
    {
      "message": "cURL error 6: Could not resolve host: newapi.getpop.org (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://newapi.getpop.org/wp-json/wp/v2/posts/8888/",
      "locations": [
        {
          "line": 2,
          "column": 17
        }
      ],
      "extensions": {
        "path": [
          "externalData: _sendJSONObjectItemHTTPRequest(input: {url: \"https://newapi.getpop.org/wp-json/wp/v2/posts/8888/\"}) @export(as: \"externalData\")",
          "query { ... }"
        ],
        "type": "QueryRoot",
        "field": "externalData: _sendJSONObjectItemHTTPRequest(input: {url: \"https://newapi.getpop.org/wp-json/wp/v2/posts/8888/\"}) @export(as: \"externalData\")",
        "id": "root",
        "code": "PoP/ComponentModel@e1"
      }
    },
    {
      "message": "Argument 'object' in field '_objectProperty' of type 'QueryRoot' cannot be null",
      "locations": [
        {
          "line": 10,
          "column": 13
        }
      ],
      "extensions": {
        "path": [
          "$__externalData",
          "(object: $__externalData)",
          "postTitle: _objectProperty(object: $__externalData, by: {path: \"title.rendered\"})",
          "query { ... }"
        ],
        "type": "QueryRoot",
        "field": "postTitle: _objectProperty(object: $__externalData, by: {path: \"title.rendered\"})",
        "id": "root",
        "code": "gql@5.4.2.1[b]",
        "specifiedBy": "https://spec.graphql.org/draft/#sec-Required-Arguments"
      }
    }
  ],
  "data": {
    "externalData": null,
    "postTitle": null
  }
}
```

If we manage to connect, but the requested resource does not exist, we will get a `404`:

```json
{
  "errors": [
    {
      "message": "Client error: `GET https://newapi.getpop.org/wp-json/wp/v2/posts/8888/` resulted in a `404 Not Found` response:\n{\"code\":\"rest_post_invalid_id\",\"message\":\"Invalid post ID.\",\"data\":{\"status\":404}}\n",
      "locations": [
        {
          "line": 2,
          "column": 17
        }
      ],
      "extensions": {
        "path": [
          "externalData: _sendJSONObjectItemHTTPRequest(input: {url: \"https://newapi.getpop.org/wp-json/wp/v2/posts/8888/\"}) @export(as: \"externalData\")",
          "query { ... }"
        ],
        "type": "QueryRoot",
        "field": "externalData: _sendJSONObjectItemHTTPRequest(input: {url: \"https://newapi.getpop.org/wp-json/wp/v2/posts/8888/\"}) @export(as: \"externalData\")",
        "id": "root",
        "code": "PoP/ComponentModel@e1"
      }
    },
    {
      "message": "Argument 'object' in field '_objectProperty' of type 'QueryRoot' cannot be null",
      "locations": [
        {
          "line": 10,
          "column": 13
        }
      ],
      "extensions": {
        "path": [
          "$__externalData",
          "(object: $__externalData)",
          "postTitle: _objectProperty(object: $__externalData, by: {path: \"title.rendered\"})",
          "query { ... }"
        ],
        "type": "QueryRoot",
        "field": "postTitle: _objectProperty(object: $__externalData, by: {path: \"title.rendered\"})",
        "id": "root",
        "code": "gql@5.4.2.1[b]",
        "specifiedBy": "https://spec.graphql.org/draft/#sec-Required-Arguments"
      }
    }
  ],
  "data": {
    "externalData": null,
    "postTitle": null
  }
}
```

In both cases, there was an additional error in the response:

```json
{
  "message": "Argument 'object' in field '_objectProperty' of type 'QueryRoot' cannot be null" 
}
```

This error happens because, after the first error, the dynamic variable `$__externalData` will have value `null`, triggering the second error. This is not ideal; we'd rather be aware that some error happened and, then, skip executing the rest of the GraphQL query.

In this recipe we will explore how to achieve this.

## Handling errors when connecting to a REST API

This GraphQL query splits the logic into two operations, where:

- The first operation exports dynamic variable `$requestProducedErrors`, indicating if the value of field `_sendJSONObjectItemHTTPRequest` is `null` (in which case, some error occurred)
- The second operation is `@skip`ped when `$requestProducedErrors` is `true`

This way, the second operation, which contains the logic to execute, is skipped when there was an error fetching the data in the first operation:

```graphql
query ConnectToRESTEndpoint($postId: ID!) {
  endpoint: _sprintf(
    string: "https://newapi.getpop.org/wp-json/wp/v2/posts/%s/?_fields=id,type,title,date"
    values: [$postId]
  ) @remove
  
  externalData: _sendJSONObjectItemHTTPRequest(
    input: {
      url: $__endpoint
    }
  ) @export(as: "externalData")

  requestProducedErrors: _isNull(value: $__externalData)
    @export(as: "requestProducedErrors")
    @remove
}

query ExecuteOperation
  @depends(on: "ConnectToRESTEndpoint")
  @skip(if: $requestProducedErrors)
{
  # Do something...
  postTitle: _objectProperty(
    object: $externalData,
    by: { path: "title.rendered"}
  )
}
```

When passing `$postId: 1`, the query is successful, and the response is:

```json
{
  "data": {
    "externalData": {
      "id": 1,
      "date": "2019-08-02T07:53:57",
      "type": "post",
      "title": {
        "rendered": "Hello world!"
      }
    },
    "postTitle": "Hello world!"
  }
}
```

Passing `$postId: 8888` concerning a non-existent resource, we get this response (notice that there's no `postTitle` in the response, and no second error message):

```json
{
  "errors": [
    {
      "message": "Client error: `GET https://newapi.getpop.org/wp-json/wp/v2/posts/8888/?_fields=id,type,title,date` resulted in a `404 Not Found` response:\n{\"code\":\"rest_post_invalid_id\",\"message\":\"Invalid post ID.\",\"data\":{\"status\":404}}\n",
      "locations": [
        {
          "line": 6,
          "column": 17
        }
      ],
      "extensions": {
        "path": [
          "externalData: _sendJSONObjectItemHTTPRequest(input: {url: $__endpoint}) @export(as: \"externalData\")",
          "query ConnectToRESTEndpoint($postId: ID!) { ... }"
        ],
        "type": "QueryRoot",
        "field": "externalData: _sendJSONObjectItemHTTPRequest(input: {url: $__endpoint}) @export(as: \"externalData\")",
        "id": "root",
        "code": "PoP/ComponentModel@e1"
      }
    }
  ],
  "data": {
    "externalData": null
  }
}
```

If the Internet connection is down, we get this response:

```json
{
  "errors": [
    {
      "message": "cURL error 6: Could not resolve host: newapi.getpop.org (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://newapi.getpop.org/wp-json/wp/v2/posts/8888/?_fields=id,type,title,date",
      "locations": [
        {
          "line": 17,
          "column": 17
        }
      ],
      "extensions": {
        "path": [
          "externalData: _sendHTTPRequest(input: {url: $__endpoint, method: GET}) { ... }",
          "query ConnectToAPI($postId: ID!) @depends(on: \"ExportDefaultDynamicVariables\") { ... }"
        ],
        "type": "QueryRoot",
        "field": "externalData: _sendHTTPRequest(input: {url: $__endpoint, method: GET}) { ... }",
        "id": "root",
        "code": "PoP/ComponentModel@e1"
      }
    }
  ],
  "data": {
    "externalData": null
  }
}
```

### Displaying the errors messages from the REST API response

The previous query uses field `_sendJSONObjectItemHTTPRequest`, which expects the status code to be `200` (or any other successful code).

However, it is possible for the REST API to return a `404` for a missing resource, and provide a descriptive error message in the JSON response.

We can capture this feedback from the webserver by replacing `_sendJSONObjectItemHTTPRequest` with `_sendHTTPRequest`, and display it in the `errors` entry in the GraphQL response.

For instance, when fetching data from a non-existent resource from the WP REST API, it returns a `data.status` entry in the response and associated data.

This GraphQL query captures this data, and explicitly adds an error entry with the response's error code and message, by using field `_fail` (provided by the [**Response Error Trigger**](https://gatographql.com/extensions/response-error-trigger/) extension):

```graphql
query ExportDefaultDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  defaultEndpointHasErrors: _echo(value: true)
    @export(as: "endpointHasErrors")
    @remove
}

query ConnectToAPI($postId: ID!)
  @depends(on: "ExportDefaultDynamicVariables")
{
  endpoint: _sprintf(
    string: "https://newapi.getpop.org/wp-json/wp/v2/posts/%s/?_fields=id,type,title,date"
    values: [$postId]
  ) @remove
  
  externalData: _sendHTTPRequest(
    input: {
      url: $__endpoint,
      method: GET
    }
  ) {    
    contentType
    statusCode
    body @remove
    bodyJSONObject: _strDecodeJSONObject(string: $__body)
      @export(as: "externalData")
  }

  isNullExternalData: _isNull(value: $__externalData)
    @export(as: "isNullExternalData")
    @remove
}

query ValidateAPIResponse
  @depends(on: "ConnectToAPI")
  @skip(if: $isNullExternalData)
{
  endpointHasErrors: _propertyIsSetInJSONObject(
    object: $externalData
    by: {
      path: "data.status"
    }
  )
    @export(as: "endpointHasErrors")
    @remove
}

query FailIfExternalAPIHasErrors($postId: ID!)
  @depends(on: "ValidateAPIResponse")
  @include(if: $endpointHasErrors)
  @skip(if: $isNullExternalData)
{
  code: _objectProperty(
    object: $externalData,
    by: {
      key: "code"
    }
  ) @remove
  message: _objectProperty(
    object: $externalData,
    by: {
      key: "message"
    }
  ) @remove
  errorMessage: _sprintf(
    string: "[%s] %s",
    values: [$__code, $__message]
  ) @remove
  data: _objectProperty(
    object: $externalData,
    by: {
      key: "data"
    }
  ) @remove
  _fail(
    message: $__errorMessage
    data: {
      postId: $postId,
      endpointData: $__data
    }
  ) @remove
}

query ExecuteSomeOperation
  @depends(on: "FailIfExternalAPIHasErrors")
  @skip(if: $endpointHasErrors)
{
  # Do something...
  postTitle: _objectProperty(
    object: $externalData,
    by: { path: "title.rendered"}
  )
}
```


<div class="doc-highlight" markdown=1>

🔥 **Tips:**

The [**Response Error Trigger**](https://gatographql.com/extensions/response-error-trigger/) extension provides two ways to add a custom entry under `errors`:

- Via field `_fail`
- Via directive `@fail`

While field `_fail` adds the error always, directive `@fail` only whenever the condition under argument `condition` is met. Its default value is `IS_NULL`, meaning that it will be triggered when the field it is applied to has value `null`:

```graphql
query GetPost($id: ID!) {
  post(by:{id: $id})
    @fail(
      message: "There is no post with the provided ID"
      data: {
        id: $id
      }
    )
  {
    id
    title
  }
}
```

</div>

When executing the query with variable `$postId: 1` the request is successful, and we obtain:

```json
{
  "data": {
    "externalData": {
      "contentType": "application/json; charset=UTF-8",
      "statusCode": 200,
      "bodyJSONObject": {
        "id": 1,
        "date": "2019-08-02T07:53:57",
        "type": "post",
        "title": {
          "rendered": "Hello world!"
        }
      }
    },
    "postTitle": "Hello world!"
  }
}
```

When executing the query with variable `$postId: 8888` the resource is missing, and we obtain:

```json
{
  "errors": [
    {
      "message": "[rest_post_invalid_id] Invalid post ID.",
      "locations": [
        {
          "line": 76,
          "column": 3
        }
      ],
      "extensions": {
        "path": [
          "_fail(message: $__errorMessage, data: {postId: $postId, endpointData: $__data}) @remove",
          "query FailIfExternalAPIHasErrors($postId: ID!) @depends(on: \"ValidateAPIResponse\") @include(if: $endpointHasErrors) @skip(if: $isNullExternalData) { ... }"
        ],
        "type": "QueryRoot",
        "field": "_fail(message: $__errorMessage, data: {postId: $postId, endpointData: $__data}) @remove",
        "id": "root",
        "failureData": {
          "postId": 8888,
          "endpointData": {
            "status": 404
          }
        },
        "code": "PoPSchema/FailFieldAndDirective@e1"
      }
    }
  ],
  "data": {
    "externalData": {
      "contentType": "application/json; charset=UTF-8",
      "statusCode": 404,
      "bodyJSONObject": {
        "code": "rest_post_invalid_id",
        "message": "Invalid post ID.",
        "data": {
          "status": 404
        }
      }
    }
  }
}
```

## Handling errors when connecting to a GraphQL API

When querying a missing resource in a GraphQL API, the response will have status code `200` and `null` value for that resource (making it different to REST, which instead returns a `404`).

The GraphQL below validates that no errors happened when executing `_sendGraphQLHTTPRequest` by checking that:

- The response is not `null` (eg: the Internet connection did not go down)
- The response does not contain the `errors` entry
- The response contains a non-`null` value under entry `data.post` (i.e. the queried resource exists)

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  defaultResponseHasErrors: _echo(value: false)
    @export(as: "responseHasErrors")
    @remove
  defaultPostIsMissing: _echo(value: false)
    @export(as: "postIsMissing")
    @remove
}

query ConnectToGraphQLAPI($postId: ID!)
  @depends(on: "InitializeDynamicVariables")
{
  externalData: _sendGraphQLHTTPRequest(
    input: {
      endpoint: "https://newapi.getpop.org/api/graphql/",
      query: """
        query GetPostData($postId: ID!) {
          post(by: { id : $postId }) {
            date
            title
          }
        }
      """,
      variables: [
        {
          name: "postId",
          value: $postId
        }
      ]
    }
  ) @export(as: "externalData")

  requestProducedErrors: _isNull(value: $__externalData)
    @export(as: "requestProducedErrors")
    @remove
}

query ValidateResponse
  @depends(on: "ConnectToGraphQLAPI")
  @skip(if: $requestProducedErrors)
{
  responseHasErrors: _propertyIsSetInJSONObject(
    object: $externalData
    by: {
      key: "errors"
    }
  )
    @export(as: "responseHasErrors")
    @remove

  postExists: _propertyIsSetInJSONObject(
    object: $externalData
    by: {
      path: "data.post"
    }
  )
    @remove
    
  postIsMissing: _not(value: $__postExists)
    @export(as: "postIsMissing")
    @remove
}

query FailIfResponseHasErrors
  @depends(on: "ValidateResponse")
  @skip(if: $requestProducedErrors)
  @skip(if: $postIsMissing)
  @include(if: $responseHasErrors)
{
  errors: _objectProperty(
    object: $externalData,
    by: {
      key: "errors"
    }
  ) @remove

  _fail(
    message: "Executing the GraphQL query produced error(s)"
    data: {
      errors: $__errors
    }
  ) @remove
}

query ExecuteOperation
  @depends(on: "FailIfResponseHasErrors")
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
{
  # Do something...
  postTitle: _objectProperty(
    object: $externalData,
    by: { path: "data.post.title" }
  )
}
```

When passing `$postId: 1`, the query is successful, and the response is:

```json
{
  "data": {
    "externalData": {
      "data": {
        "post": {
          "date": "2019-08-02T07:53:57+00:00",
          "title": "Hello world!"
        }
      }
    },
    "postTitle": "Hello world!"
  }
}
```

Passing `$postId: 8888` concerning a non-existent resource, we get this response (notice that there's no `postTitle` in the response, and also no error message):

```json
{
  "data": {
    "externalData": {
      "data": {
        "post": null
      }
    }
  }
}
```

If the Internet connection is down, we get this response:

```json
{
  "errors": [
    {
      "message": "cURL error 6: Could not resolve host: newapi.getpop.org (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://newapi.getpop.org/api/graphql/",
      "locations": [
        {
          "line": 15,
          "column": 17
        }
      ],
      "extensions": {
        "path": [
          "externalData: _sendGraphQLHTTPRequest(input: {endpoint: \"https://newapi.getpop.org/api/graphql/\", query: \"\n        query GetPostData($postId: ID!) {\n          post(by: { id : $postId }) {\n            date\n            title\n          }\n        }\n      \", variables: [{name: \"postId\", value: $postId}]}) @export(as: \"externalData\")",
          "query ConnectToGraphQLAPI($postId: ID!) @depends(on: \"InitializeDynamicVariables\") { ... }"
        ],
        "type": "QueryRoot",
        "field": "externalData: _sendGraphQLHTTPRequest(input: {endpoint: \"https://newapi.getpop.org/api/graphql/\", query: \"\n        query GetPostData($postId: ID!) {\n          post(by: { id : $postId }) {\n            date\n            title\n          }\n        }\n      \", variables: [{name: \"postId\", value: $postId}]}) @export(as: \"externalData\")",
        "id": "root",
        "code": "PoP/ComponentModel@e1"
      }
    }
  ],
  "data": {
    "externalData": null
  }
}
```

### Producing an error if the requested resource does not exist

If the GraphQL query above, if the queried post does not exist, it just returns `null` and there's no error entry under `errors`.

If we want to force adding an error in that situation, we can append the following operation, which uses field `_fail` to trigger an error:

```graphql
query FailIfPostNotExists($postId: ID!)
  @skip(if: $requestProducedErrors)
  @include(if: $postIsMissing)
  @depends(on: "ValidateResponse")
{
  errorMessage: _sprintf(
    string: "There is no post with ID '%s'",
    values: [$postId]
  ) @remove
  _fail(
    message: $__errorMessage
    data: {
      id: $postId
    }
  ) @remove
}

query ExecuteOperation
  @depends(on: [
    "FailIfResponseHasErrors",
    "FailIfPostNotExists"
  ])
  # ...
{
  # ...
}
```

Now, when passing `$postId: 8888` concerning a non-existent resource, we get this response:

```json
{
  "errors": [
    {
      "message": "There is no post with ID '8888'",
      "locations": [
        {
          "line": 96,
          "column": 3
        }
      ],
      "extensions": {
        "path": [
          "_fail(message: $__errorMessage, data: {id: $postId}) @remove",
          "query FailIfPostNotExists($postId: ID!) @skip(if: $requestProducedErrors) @include(if: $postIsMissing) @depends(on: \"ValidateResponse\") { ... }"
        ],
        "type": "QueryRoot",
        "field": "_fail(message: $__errorMessage, data: {id: $postId}) @remove",
        "id": "root",
        "failureData": {
          "id": 8888
        },
        "code": "PoPSchema/FailFieldAndDirective@e1"
      }
    }
  ],
  "data": {
    "externalData": {
      "data": {
        "post": null
      }
    }
  }
}
```
