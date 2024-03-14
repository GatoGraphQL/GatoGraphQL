# Lesson 26: Filtering data from an external API

If the external API does not allow filtering by a certain property that we need, we can use Gato GraphQL to iterate over the entries in the API response, and remove those ones that do not satifsy our condition.

Let's refer again to REST API endpoint `newapi.getpop.org/wp-json/wp/v2/users/?_fields=id,name,url`, where some users have property `url` empty:

```json
[
  {
    "id": 1,
    "name": "leo",
    "url": "https://leoloso.com"
  },
  {
    "id": 7,
    "name": "Test",
    "url": ""
  },
  {
    "id": 2,
    "name": "Theme Demos",
    "url": ""
  }
]
```

The GraphQL query below filters out those users where the `url` property is empty, by:

- Retrieving data from the external API
- Iterating over the entries via `@underEachArrayItem`, and placing each entry under dynamic variable `$userDataEntry`
- Extracting property `url` from each entry, and placing this value under dynamic variable `$websiteURL`
- Checking if this value is empty, and assigning the result under dynamic variable `$isWebsiteURLEmpty`
- Applying conditional directive `@if` which, if `$isWebsiteURLEmpty` is `true`, sets the value of that entry as `null`
- Executing directive `@arrayFilter` to filter out all `null` entries

```graphql
query {
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

The response is:

```json
{
  "data": {
    "usersWithWebsiteURL": [
      {
        "id": 1,
        "name": "leo",
        "url": "https://leoloso.com"
      }
    ]
  }
}
```
