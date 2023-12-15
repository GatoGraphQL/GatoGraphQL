# Transforming data from an external API

This tutorial lesson demonstrates examples on adapting the response from an external API to anything we need it to be.

## Adding default values and extra properties to each entry

The REST API endpoint `newapi.getpop.org/wp-json/wp/v2/users/?_fields=id,name,url` produces user data, with some users having property `url` empty:

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

The GraphQL query below transforms this response:

- Adding a default URL to those users whose `url` property is empty
- Adding a `link` property to each user entry (composed using the user's name and URL values)

```graphql
query {
  # Retrieve data from the external API
  usersWithLinkAndDefaultURL: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: "https://newapi.getpop.org/wp-json/wp/v2/users/?_fields=id,name,url"
    }
  )
    # Set a default URL for users without any
    @underEachArrayItem
      @underJSONObjectProperty(
        by: {
          key: "url"
        }
      )
        @default(
          value: "https://mysite.com"
          condition: IS_EMPTY
        )

    # Add a new "link" entry on the JSON object
    @underEachArrayItem(
      affectDirectivesUnderPos: [1, 2, 3, 4],
      passValueOnwardsAs: "userListItem"
    )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $userListItem,
          by: {
            key: "name"
          }
        },
        passOnwardsAs: "userName"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $userListItem,
          by: {
            key: "url"
          }
        },
        passOnwardsAs: "userURL"
      )
      @applyField(
        name: "_sprintf",
        arguments: {
          string: "<a href=\"%s\">%s</a>",
          values: [$userURL, $userName]
        },
        passOnwardsAs: "userLink"
      )
      @applyField(
        name: "_objectAddEntry",
        arguments: {
          object: $userListItem,
          key: "link",
          value: $userLink
        },
        setResultInResponse: true
      )
}
```

The response is:

```json
{
  "data": {
    "usersWithLinkAndDefaultURL": [
      {
        "id": 1,
        "name": "leo",
        "url": "https://leoloso.com",
        "link": "<a href=\"https://leoloso.com\">leo</a>"
      },
      {
        "id": 7,
        "name": "Test",
        "url": "https://mysite.com",
        "link": "<a href=\"https://mysite.com\">Test</a>"
      },
      {
        "id": 2,
        "name": "Theme Demos",
        "url": "https://mysite.com",
        "link": "<a href=\"https://mysite.com\">Theme Demos</a>"
      }
    ]
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

[Composable directives](https://gatographql.com/guides/schema/using-composable-directives/) can nest one or more directives within them. When nesting more than one, we indicate this via argument `affectDirectivesUnderPos`, which contains the relative positions from that directive to its nested directives.

In the GraphQL query above, directive `@underEachArrayItem` (provided by the [**Field Value Iteration and Manipulation**](https://gatographql.com/extensions/field-value-iteration-and-manipulation/) extension) is a composable directive. On its first occurrence, it is nesting only one directive, and argument `affectDirectivesUnderPos` can be omitted:

```graphql
    @underEachArrayItem
      @underJSONObjectProperty(
        # ...
      )
```

(Btw, notice that `@underJSONObjectProperty` is also a composable directive, nesting the `@default` directive).

On its second occurrence, it is nesting the 4 directives to its right, as indicated by argument `affectDirectivesUnderPos` with value `[1, 2, 3, 4]`:

```graphql
    @underEachArrayItem(
      affectDirectivesUnderPos: [1, 2, 3, 4],
      # ...
    )
      @applyField(
        name: "_objectProperty",
        # ...
      )
      @applyField(
        name: "_objectProperty",
        # ...
      )
      @applyField(
        name: "_sprintf",
       # ...
      )
      @applyField(
        name: "_objectAddEntry",
        # ...
      )
```

<br/>

---

<br/>

🔥 **Tips:**

Directive `@applyField` (provided by the [**Field on Field**](https://gatographql.com/extensions/field-on-field/) extension) has two potential destinations for its output:

- Providing argument `passOnwardsAs: "someVariableName"` will assign the new value to dynamic variable `$someVariableName`, from which it can be read by the upcoming nested directives:

```graphql
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $userListItem,
          by: {
            key: "name"
          }
        },
        passOnwardsAs: "userName"
      )
```

- Providing argument `setResultInResponse: true` will assign the new value again to the field (hence it will modify the response):

```graphql
      @applyField(
        name: "_objectAddEntry",
        arguments: {
          object: $userListItem,
          key: "link",
          value: $userLink
        },
        setResultInResponse: true
      )
```

</div>

## Extracting a specific property from the JSON objects

The REST API endpoint `newapi.getpop.org/wp-json/newsletter/v1/subscriptions` produces a collection of email subscription data, including the subscriber's email and language:

```json
[
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
]
```

This GraphQL query prints only the emails from the API response, by extracting the `email` property from each entry and replacing the field value with it:

```graphql
query {
  emails: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: "https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions"
    }
  )
    @underEachArrayItem(
      passValueOnwardsAs: "userEntry"
    )
      @applyField(
        name: "_objectProperty"
        arguments: {
          object: $userEntry,
          by: {
            key: "email"
          }
        }
        setResultInResponse: true
      )
}
```

The response is:

```json
{
  "data": {
    "emails": [
      "abracadabra@ganga.com",
      "longon@caramanon.com",
      "rancotanto@parabara.com",
      "quezarapadon@quebrulacha.net",
      "test@test.com",
      "emilanga@pedrola.com"
    ]
  }
}
```

## Conditionally modifying field values

This example continues on the previous one, plus also converting the format of the emails in the response.

The GraphQL query below extracts the emails from the response of the API, and converts to upper case those from users whose language is English or German via the composable directive `@if` (provided by the [**Conditional Field Manipulation**](https://gatographql.com/extensions/conditional-field-manipulation/) extension):

```graphql
query {
  # Retrieve data from a REST API endpoint
  userEntries: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: "https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions"
    }
  )
    @remove

  emails: _echo(value: $__userEntries)

    # Iterate all the entries, passing every entry
    # (under the dynamic variable $userEntry)
    # to each of the next 4 directives
    @underEachArrayItem(
      passValueOnwardsAs: "userEntry"
      affectDirectivesUnderPos: [1, 2, 3, 4]
    )

      # Extract property "lang" from the entry
      # via the functionality field `_objectProperty`,
      # and pass it onwards as dynamic variable $userLang
      @applyField(
        name: "_objectProperty"
        arguments: {
          object: $userEntry,
          by: {
            key: "lang"
          }
        }
        passOnwardsAs: "userLang"
      )

      # Execute functionality field `_inArray` to find out
      # if $userLang is either "en" or "de", and place the
      # result under dynamic variable $isSpecialLang
      @applyField(
        name: "_inArray"
        arguments: {
          value: $userLang,
          array: ["en", "de"]
        }
        passOnwardsAs: "isSpecialLang"
      )

      # Extract property "email" from the entry
      # and set it back as the value for that entry
      @applyField(
        name: "_objectProperty"
        arguments: {
          object: $userEntry,
          by: {
            key: "email"
          }
        }
        setResultInResponse: true
      )

      # If $isSpecialLang is `true` then execute
      # directive `@strUpperCase` 
      @if(condition: $isSpecialLang)
        @strUpperCase
}
```

The response is:

```json
{
  "data": {
    "emails": [
      "ABRACADABRA@GANGA.COM",
      "longon@caramanon.com",
      "RANCOTANTO@PARABARA.COM",
      "quezarapadon@quebrulacha.net",
      "TEST@TEST.COM",
      "emilanga@pedrola.com"
    ]
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Executing conditional logic in Gato GraphQL can be made dynamic: By passing a dynamic variable to `@if(condition:)` (and also to `@unless(condition:)`) that was evaluated on the queried object, logic will be executed or not depending on conditions from that entity.

This way, we can dynamically modify the response for some entities (and not others), on conditions such as:

- Does the post have comments?
- Does the comment have responses?
- Is the user an admin?
- Is the tag/category applied to some post?
- Etc

</div>
