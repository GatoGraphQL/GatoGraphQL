# Combining user data from different systems

API gateway

combine-user-data.gql

```graphql
query ProvideNewsletterUserData {
  userList: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: "https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions"
    }
  )
    @export(as: "userList")
    # @remove

  userEmails: _echo(value: $__userList)
    @forEach(passOnwardsAs: "userListItemForEmail")
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $userListItemForEmail,
          by: {
            key: "email"
          }
        },
        setResultInResponse: true
      )
    @export(as: "userEmails")
    # @remove
}

query CombineUserDataFromDisparateSources
  @depends(on: "ProvideNewsletterUserData")
{
  joinedUserEmails: _arrayJoin(
    array: $userEmails,
    separator: "&emails[]="
  ) # @remove

  userEndpoint: _strAppend(
    after: "https://newapi.getpop.org/users/api/rest/?query={name%20email}&emails[]=",
    append: $__joinedUserEmails
  ) # @remove

  userEndpointDataItems: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: $__userEndpoint
    }
  ) # @remove

  userData: _arrayInnerJoinJSONObjectProperties(
    source: $__userEndpointDataItems,
    target: $userList,
    index: "email"
  )
    @export(as: "userData")
    # @remove
}
```
