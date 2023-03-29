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
    @forEach(
      passOnwardsAs: "userDataEntry"
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

```graphql
query ConnectToAPI($endpoint: String!) {
  externalData: _sendJSONObjectItemHTTPRequest(
    input: {
      url: $endpoint
    }
  ) @export(as: "externalData")
  _propertyIsSetInJSONObject(
    object: $__externalData
    by: {
      path: "data.status"
    }
  ) @export(as: "endpointHasErrors")
}

query FailIfExternalAPIHasErrors($endpoint: String!)
  @include(if: $endpointHasErrors)
  @depends(on: "ConnectToAPI")
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
      endpoint: $endpoint
      endpointData: $__data
    }
  ) @remove
}
```

var

```json
{
  "endpoint": "https://newapi.getpop.org/wp-json/wp/v2/posts/8888888888/?_fields=id,type,title,date"
}
```