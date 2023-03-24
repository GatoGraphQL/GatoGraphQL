# Transforming data from an external API

api-transformation.gql:

```graphql
query AdaptExternalAPIData {
  userList: _requestJSONObjectCollection(
    input: {
      url: "https://newapi.getpop.org/wp-json/wp/v2/users/?_fields=id,name,url"
    }
  )

  adaptedUserList: _echo(value: $__userList)
    # Set a default URL for users without any
    @forEach
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
    @forEach(
      affectDirectivesUnderPos: [1, 2, 3, 4],
      passOnwardsAs: "userListItem"
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

conditional-data-manipulation-in-array.gql

```graphql
###################################################################
# Fetch data from a REST endpoint, extract the emails, and make
# uppercase those ones from users with a special language.
###################################################################
query ExtractEmailsFromAPIAndUpperCaseSpecialOnes
{
  # Retrieve data from a REST API endpoint
  userEntries: _requestJSONObjectCollection(
    input: {
      url: "https://newapi.getpop.org/wp-json/newsletter/v1/subscriptions"
    }
  ) # @remove   # <= Uncomment this directive to not print the API data

  emails: _echo(value: $__userEntries)

    # Iterate all the entries, passing every entry
    # (under the dynamic variable $userEntry)
    # to each of the next 4 directives
    @forEach(
      passOnwardsAs: "userEntry"
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
