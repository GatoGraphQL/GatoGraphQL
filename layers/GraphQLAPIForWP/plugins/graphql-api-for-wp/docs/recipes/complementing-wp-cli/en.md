# Complementing WP-CLI

```graphql
query One {
  spanishLocaleUsers: users(filter: { metaQuery: {
    key: "locale",
    compareBy: {
      stringValue: {
        value: "es_[A-Z]+"
        operator: REGEXP
      }
    }
  }}) {
    id @export(as: "userIDs")
    name
    locale: metaValue(key: "locale")
  }
}

query Two @depends(on: "One") {
  spanishLocaleUserIDs: _arrayJoin(
    array: $userIDs,
    separator: " "
  )
}
```

Then (transforming query " to \" and newlines to \n):

```bash
GRAPHQL_RESPONSE=$(curl --insecure \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"operationName": "Two", "query": "query One {\n  spanishLocaleUsers: users(filter: { metaQuery: {\n    key: \"locale\",\n    compareBy: {\n      stringValue: {\n        value: \"es_[A-Z]+\"\n        operator: REGEXP\n      }\n    }\n  }}) {\n    id @export(as: \"userIDs\")\n    name\n    locale: metaValue(key: \"locale\")\n  }\n}\n\nquery Two @depends(on: \"One\") {\n  spanishLocaleUserIDs: _arrayJoin(\n    array: $userIDs,\n    separator: \" \"\n  )\n}"}' \
  https://graphql-api-pro.lndo.site/graphql/nested-mutations/)

SPANISH_LOCALE_USER_IDS=$(echo $GRAPHQL_RESPONSE \
  | grep -E -o '"spanishLocaleUserIDs\":"(.*)"' \
  | cut -d':' -f2- | cut -d'"' -f2- | rev | cut -d'"' -f2- | rev)

# I can't pass multiple IDs to `wp user meta`:
# $ wp user meta update "$(echo $SPANISH_LOCALE_USER_IDS)" locale "fr_FR"
# Then iterate the list, and execute command for each:
for USER_ID in $(echo $SPANISH_LOCALE_USER_IDS); do wp user update "$(echo $USER_ID)" --locale=fr_FR; done
```
