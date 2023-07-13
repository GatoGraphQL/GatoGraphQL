# Combining user data from different sources

In the previous recipe, we learnt that we can fetch user data from Mailchimp's REST API and combine it with our website's user data.

We can apply this same idea for any 2 data sources, and combine their datasets.

## Combining two datasets into one

Function field `_arrayInnerJoinJSONObjectProperties` (provided by the [**PHP Functions Via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension) allows to combine two datasets into one, given that the elements in both datasets share some common property.

In this GraphQL query, inputs `source` and `target` are lists of JSON objects which share a common `email` property:

```graphql
query {
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

## Combining datasets from different sources

We can use the previous idea to retrieve data we have stored in multiple external services (accessible via those services REST APIs), and combine these disparate datasets together.

Let's say that we use two separate services, which provide:

1. Newsletter subscribers' data (including email and language spoken by the person)
2. User data from a CRM (including email and name)

In this GraphQL query, we access the datasets from these services' REST APIs, and combine it all in a single dataset:

```graphql
query ProvideNewsletterUserData {
  userList: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: "https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions"
    }
  )
    @export(as: "userList")

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
}

query CombineUserDataFromDisparateSources
  @depends(on: "ProvideNewsletterUserData")
{
  joinedUserEmails: _arrayJoin(
    array: $userEmails,
    separator: "&emails[]="
  )

  userEndpoint: _strAppend(
    after: "https://newapi.getpop.org/users/api/rest/?query={name%20email}&emails[]=",
    append: $__joinedUserEmails
  )

  userEndpointDataItems: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: $__userEndpoint
    }
  )

  userData: _arrayInnerJoinJSONObjectProperties(
    source: $__userEndpointDataItems,
    target: $userList,
    index: "email"
  )
    @export(as: "userData")
}
```

...producing:

```json
{
  "data": {
    "userList": [
      {
        "email": "abracadabra@ganga.com",
        "lang": "de"
      },
      {
        "email": "longon@caramanon.com",
        "lang": "es"
      },
      {
        "email": "rancotanto@parabara.com",
        "lang": "en"
      },
      {
        "email": "quezarapadon@quebrulacha.net",
        "lang": "fr"
      },
      {
        "email": "test@test.com",
        "lang": "de"
      },
      {
        "email": "emilanga@pedrola.com",
        "lang": "fr"
      }
    ],
    "userEmails": [
      "abracadabra@ganga.com",
      "longon@caramanon.com",
      "rancotanto@parabara.com",
      "quezarapadon@quebrulacha.net",
      "test@test.com",
      "emilanga@pedrola.com"
    ],
    "joinedUserEmails": "abracadabra@ganga.com&emails[]=longon@caramanon.com&emails[]=rancotanto@parabara.com&emails[]=quezarapadon@quebrulacha.net&emails[]=test@test.com&emails[]=emilanga@pedrola.com",
    "userEndpoint": "https://newapi.getpop.org/users/api/rest/?query={name%20email}&emails[]=abracadabra@ganga.com&emails[]=longon@caramanon.com&emails[]=rancotanto@parabara.com&emails[]=quezarapadon@quebrulacha.net&emails[]=test@test.com&emails[]=emilanga@pedrola.com",
    "userEndpointDataItems": [
      {
        "name": "Abrigail Ataluncha",
        "email": "quezarapadon@quebrulacha.net"
      },
      {
        "name": "Chip Bennett",
        "email": "abracadabra@ganga.com"
      },
      {
        "name": "Contributor",
        "email": "contributor@test.com"
      },
      {
        "name": "Emil Uzelac",
        "email": "longon@caramanon.com"
      },
      {
        "name": "Lance Ampsrong",
        "email": "rancotanto@parabara.com"
      },
      {
        "name": "leo",
        "email": "leo@getpop.org"
      },
      {
        "name": "Test",
        "email": "test@test.com"
      },
      {
        "name": "Theme Demos",
        "email": "emilanga@pedrola.com"
      }
    ],
    "userData": [
      {
        "email": "abracadabra@ganga.com",
        "lang": "de",
        "name": "Chip Bennett"
      },
      {
        "email": "longon@caramanon.com",
        "lang": "es",
        "name": "Emil Uzelac"
      },
      {
        "email": "rancotanto@parabara.com",
        "lang": "en",
        "name": "Lance Ampsrong"
      },
      {
        "email": "quezarapadon@quebrulacha.net",
        "lang": "fr",
        "name": "Abrigail Ataluncha"
      },
      {
        "email": "test@test.com",
        "lang": "de",
        "name": "Test"
      },
      {
        "email": "emilanga@pedrola.com",
        "lang": "fr",
        "name": "Theme Demos"
      }
    ]
  }
}
```
