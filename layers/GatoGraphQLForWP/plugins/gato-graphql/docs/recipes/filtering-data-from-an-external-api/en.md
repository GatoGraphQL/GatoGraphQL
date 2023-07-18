# Filtering data from an external API

If the external API does not allow filtering by a certain property that we need, we can use Gato GraphQL to iterate the entries in the API response, and remove those ones that do not satifsy our condition.

In this GraphQL query, we discard all user data entries where the user's website URL is `null`:

```graphql
query FilterExternalAPIData {
  usersWithWebsiteURL: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: "https://newapi.getpop.org/wp-json/wp/v2/users/?_fields=id,name,url"
    }
  )
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
