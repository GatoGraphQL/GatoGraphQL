# Injecting multiple results at once into WP-CLI

In the previous recipe, we only retrieved (and injected into WP-CLI) a single user ID.

With the use of extensions, we can retrieve multiple user IDs by executing a single GraphQL query:

- We remove the `pagination` argument from the query, as to retrieve the list of all the users with Spanish locale
- We export a list of the user IDs, under dynamic variable `$userIDs`
- We print the elements of this array with `_arrayJoin`, joining the items with a space in between, under alias `spanishLocaleUserIDs`
- We execute the operation `FormatAndPrintData`

```graphql
# This query is stored in file "find-multiple-users-with-spanish-locale.gql"
query RetrieveData {
  users(
    filter: {
      metaQuery: {
        key: "locale",
        compareBy: {
          stringValue: {
            value: "es_[A-Z]+"
            operator: REGEXP
          }
        }
      }
    }
  ) {
    id @export(as: "userIDs", type: LIST)
    name
    locale: metaValue(key: "locale")
  }
}

query FormatAndPrintData @depends(on: "RetrieveData") {
  spanishLocaleUserIDs: _arrayJoin(
    array: $userIDs,
    separator: " "
  )
}
```

The response to this query will be:

```json
{
  "data": {
    "users": [
      {
        "id": 3,
        "name": "Subscriber Bennett",
        "locale": "es_AR"
      },
      {
        "id": 2,
        "name": "Blogger Davenport",
        "locale": "es_ES"
      }
    ],
    "spanishLocaleUserIDs": "3 2"
  }
}
```

When executing the query, the dictionary in the body of the request must indicate the name of the operation to execute (`"FormatAndPrintData"`):

```bash
GRAPHQL_QUERY=$(cat find-multiple-users-with-spanish-locale.gql)
GRAPHQL_BODY="{\"operationName\": \"FormatAndPrintData\", \"query\": \"$(echo $GRAPHQL_QUERY | tr '\n' ' ' | sed 's/"/\\"/g')\"}"
GRAPHQL_RESPONSE=$(curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d $GRAPHQL_BODY \
  https://mysite.com/graphql/)
```

We must also adapt the regex (due to the new alias, the space in between the IDs, and the quotes around this string):

```bash
SPANISH_LOCALE_USER_IDS=$(echo $GRAPHQL_RESPONSE \
  | grep -E -o '"spanishLocaleUserIDs\":"((\d|\s)+)"' \
  | cut -d':' -f2- | cut -d'"' -f2- | rev | cut -d'"' -f2- | rev)
```

Printing the contents of variable `SPANISH_LOCALE_USER_IDS`, we get all the IDs, separated with a space:

```bash
echo $SPANISH_LOCALE_USER_IDS
# Response:
# 3 2
```

We can now inject all IDs together to the WP-CLI command (if it supports it), or iterate them and execute the command for each of them:

```bash
for USER_ID in $(echo $SPANISH_LOCALE_USER_IDS); do wp user update "$(echo $USER_ID)" --locale=fr_FR; done
```
