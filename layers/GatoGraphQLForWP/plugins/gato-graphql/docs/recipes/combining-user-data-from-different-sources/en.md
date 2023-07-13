# Combining user data from different sources

In the previous recipe, we learnt that we can fetch user data from Mailchimp's REST API and combine it with user data stored in our website.

We can generalize this idea, applying it to any two data sources, combining their datasets into one, and then executing some operation with the combined data.

## Combining datasets from different sources

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Function field `_arrayInnerJoinJSONObjectProperties` (provided by the [**PHP Functions Via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension) allows us to combine JSON objects that reference the same entity, into a single JSON object containing all properties.

The JSON objects in both sources can be identified as referencing the same entity because their "index" property has the same value.

In this GraphQL query, inputs `source` and `target` receive lists of JSON objects which share a common `email` property (and so is used as the "index"):

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

When the `email` property value is the same in the source and target JSON objects, those objects are then combined in the resulting list:

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

</div>

We can retrieve data we have stored in multiple cloud-based services (accessible via those services' APIs), and combine these disparate datasets together.

For instance, pick any two of these services that might store user data:

- Mailchimp
- Dropbox
- GitHub
- Microsoft Teams
- Slack
- Trello
- Google Drive
- Your WordPress website
- Your company's internal applications
- etc

The GraphQL query below combines the datasets from two hypothetical services:

1. A newsletter system (storing subscribers' data, including their email and spoken language)
2. A CRM (storing customers' data, including their name and email)

It first retrieves all records from the newsletter service and extracts the emails. Then it uses these emails to generate the endpoint URL for the CRM's REST API, as to fetch data for those users only. Finally, it combines both datasets into one, around the shared `email` property:

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
