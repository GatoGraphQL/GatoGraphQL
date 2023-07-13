# Combining user data from different systems

In the previous recipe, we learnt how to fetch user data from Mailchimp's REST API, and combined it with our website's user data.

We can apply this same idea for any 2 data sources, and combine their datasets.

## Combining two datasets into one

We can use function field `_arrayInnerJoinJSONObjectProperties` (from the [**PHP Functions Via Schema**](https://gatographql.com/extensions/php-functions-via-schema/)) to combine two datasets into one, given that the elements in both datasets share some common property.

In this GraphQL query, inputs `source` and `target` are lists of JSON objects which share a common `email` property:

```graphql
{
  _arrayInnerJoinJSONObjectProperties(
    source: [
      {
        email: "abracadabra@ganga.com",
        lang: "de"
      },
      {
        email: "longon@caramanon.com",
        lang: "es"
      },
      {
        email: "rancotanto@parabara.com",
        lang: "en"
      },
      {
        email: "quezarapadon@quebrulacha.net",
        lang: "fr"
      },
      {
        email: "test@test.com",
        lang: "de"
      },
      {
        email: "emilanga@pedrola.com",
        lang: "fr"
      }
    ],
    target: [
      {
        email: "quezarapadon@quebrulacha.net",
        name: "Abrigail Ataluncha"
      },
      {
        email: "abracadabra@ganga.com",
        name: "Chip Bennett"
      },
      {
        email: "contributor@test.com",
        name: "Contributor"
      },
      {
        email: "longon@caramanon.com",
        name: "Emil Uzelac"
      },
      {
        email: "rancotanto@parabara.com",
        name: "Lance Ampsrong"
      },
      {
        email: "leo@getpop.org",
        name: "leo"
      },
      {
        email: "test@test.com",
        name: "Test"
      },
      {
        email: "emilanga@pedrola.com",
        name: "Theme Demos"
      }
    ],
    index: "email"
  )
}
```

The end result of applying field `_arrayInnerJoinJSONObjectProperties` will be the combination of the two datasets around the shared `"email"` property:

```json
{
  "data": {
    "_arrayInnerJoinJSONObjectProperties": [
      {
        "email": "quezarapadon@quebrulacha.net",
        "name": "Abrigail Ataluncha",
        "lang": "fr"
      },
      {
        "email": "abracadabra@ganga.com",
        "name": "Chip Bennett",
        "lang": "de"
      },
      {
        "email": "contributor@test.com",
        "name": "Contributor"
      },
      {
        "email": "longon@caramanon.com",
        "name": "Emil Uzelac",
        "lang": "es"
      },
      {
        "email": "rancotanto@parabara.com",
        "name": "Lance Ampsrong",
        "lang": "en"
      },
      {
        "email": "leo@getpop.org",
        "name": "leo"
      },
      {
        "email": "test@test.com",
        "name": "Test",
        "lang": "de"
      },
      {
        "email": "emilanga@pedrola.com",
        "name": "Theme Demos",
        "lang": "fr"
      }
    ]
  }
}
```




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
    @underEachArrayItem(passValueOnwardsAs: "userListItemForEmail")
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
