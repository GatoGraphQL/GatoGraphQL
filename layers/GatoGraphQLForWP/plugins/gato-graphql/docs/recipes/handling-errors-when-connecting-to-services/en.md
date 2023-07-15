# Handling errors when connecting to services

Doing this:

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
}

query ExecuteOperation
  @depends(on: "ConnectToRESTEndpoint")
{
  # Do something...
  postTitle: _objectProperty(
    object: $externalData,
    by: { path: "title.rendered"}
  )
}
```

...will bubble errors:

```json
{
  "errors": [
    {
      "message": "Client error: `GET https://newapi.getpop.org/wp-json/wp/v2/posts/88888/?_fields=id,type,title,date` resulted in a `404 Not Found` response:\n{\"code\":\"rest_post_invalid_id\",\"message\":\"Invalid post ID.\",\"data\":{\"status\":404}}\n",
      "locations": [
        {
          "line": 7,
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
    },
    {
      "message": "Argument 'object' in field '_objectProperty' of type 'QueryRoot' cannot be null",
      "locations": [
        {
          "line": 19,
          "column": 13
        }
      ],
      "extensions": {
        "path": [
          "$externalData",
          "(object: $externalData)",
          "postTitle: _objectProperty(object: $externalData, by: {path: \"title.rendered\"})",
          "query ExecuteOperation @depends(on: \"ConnectToRESTEndpoint\") { ... }"
        ],
        "type": "QueryRoot",
        "field": "postTitle: _objectProperty(object: $externalData, by: {path: \"title.rendered\"})",
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

## Handling errors when connecting to a REST API

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

with:

      # This will fail because the resource does not exist, producing a 404
"https://newapi.getpop.org/wp-json/wp/v2/posts/88888/?_fields=id,type,title,date"
"https://newapi.getpop.org/wp-json/wp/v2/posts/1/?_fields=id,type,title,date"

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

or:

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

### Otherwise

Previous one expects status code to be 200. Without that expectation, we can retrieve the error message contained in the body:

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

returns:

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

or with wrong post ID:

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
          "postId": 88888,
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

or if webserver is down:

```json
{
  "errors": [
    {
      "message": "cURL error 6: Could not resolve host: newapi.getpop.org (see https://curl.haxx.se/libcurl/c/libcurl-errors.html) for https://newapi.getpop.org/wp-json/wp/v2/posts/88888/?_fields=id,type,title,date",
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

## Handling errors when connecting to a GraphQL API

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
      # The resource does not exist, but the status is still 200, and the error is in the response
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

or if post with ID not exists:

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

or if webserver is down:

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
