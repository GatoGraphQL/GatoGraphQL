# Filtering data from an external API

api-filtering.gql:

```graphql
query FilterExternalAPIData {
  userList: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: "https://newapi.getpop.org/wp-json/wp/v2/users/?_fields=id,name,url"
    }
  )

  usersWithWebsiteURL: _echo(value: $__userList)
    # Remove users without a website URL
    @underEachArrayItem(
      passValueOnwardsAs: "userDataEntry"
      affectDirectivesUnderPos: [1, 2, 3]
    )
      @applyField(
        name: "_objectProperty"
        arguments: {
          object: $userDataEntry
          by: {
            key: "url"
          }
        }
        passOnwardsAs: "websiteURL"
      )
      @applyField(
        name: "_isEmpty"
        arguments: {
          value: $websiteURL
        }
        passOnwardsAs: "isWebsiteURLEmpty"
      )
      @if(
        condition: $isWebsiteURLEmpty
      )
        @setNull
    @arrayFilter
}
```

fail-if-external-api-has-errors.gql <= Check if to place it on this Recipe, or elsewhere

REST:

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

GraphQL:

```graphql
query ConnectToGraphQLAPI($postId: ID!) {
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

  responseHasErrors: _propertyIsSetInJSONObject(
    object: $__externalData
    by: {
      key: "errors"
    }
  )
    @export(as: "responseHasErrors")
    @remove
}

query FailIfResponseHasErrors
  @depends(on: "ConnectToGraphQLAPI")
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
  @skip(if: $responseHasErrors)
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

or:

```json

```