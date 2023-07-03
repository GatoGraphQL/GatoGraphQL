# Complementing WP-CLI

First a single result

```graphql
query {
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
    },
    pagination: {
      limit: 1
    }
  ) {
    spanishLocaleUserID: id
    name
    locale: metaValue(key: "locale")
  }
}
```

Transforming query's '"' to '\"' and newlines to '\n':

```bash
GRAPHQL_RESPONSE=$(curl --insecure \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "query {\n  users(\n    filter: {\n      metaQuery: {\n        key: \"locale\",\n        compareBy: {\n          stringValue: {\n            value: \"es_[A-Z]+\"\n            operator: REGEXP\n          }\n        }\n      }\n    },\n    pagination: {\n      limit: 1\n    }\n  ) {\n    spanishLocaleUserID: id\n    name\n    locale: metaValue(key: \"locale\")\n  }\n}"}' \
  https://gato-graphql-pro.lndo.site/graphql/nested-mutations/)
```

Input into WP-CLI:

```bash
SPANISH_LOCALE_USER_ID=$(echo $GRAPHQL_RESPONSE \
  | grep -E -o '"spanishLocaleUserID\":"(.*)"' \
  | cut -d':' -f2- | cut -d'"' -f2- | rev | cut -d'"' -f2- | rev)

wp user update "$(echo $SPANISH_LOCALE_USER_ID)" --locale=fr_FR
```

## Automating the bash script

```bash
GRAPHQL_QUERY='
  query {
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
      },
      pagination: {
        limit: 1
      }
    ) {
      spanishLocaleUserID: id
      name
      locale: metaValue(key: "locale")
    }
  }
'
GRAPHQL_BODY="{\"query\": \"$(echo $GRAPHQL_QUERY | tr '\n' ' ' | sed 's/"/\\"/g')\"}"
GRAPHQL_RESPONSE=$(curl --insecure \
  -X POST \
  -H "Content-Type: application/json" \
  -d $GRAPHQL_BODY \
  https://gato-graphql.lndo.site/graphql/website/)
```

## Query with syntax highlighting

Do this:

```graphql
query {
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
    },
    pagination: {
      limit: 1
    }
  ) {
    spanishLocaleUserID: id
    name
    locale: metaValue(key: "locale")
  }
}
```

And:

```bash
GRAPHQL_QUERY=$(cat query.gql)
GRAPHQL_BODY="{\"query\": \"$(echo $GRAPHQL_QUERY | tr '\n' ' ' | sed 's/"/\\"/g')\"}"
GRAPHQL_RESPONSE=$(curl --insecure \
  -X POST \
  -H "Content-Type: application/json" \
  -d $GRAPHQL_BODY \
  https://gato-graphql.lndo.site/graphql/website/)
```

## Multiple results via `@export`

```graphql
query One {
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

Must also pass the "operationName":

```bash
GRAPHQL_QUERY=$(cat query.gql)
GRAPHQL_BODY="{\"operationName\": \"Two\", \"query\": \"$(echo $GRAPHQL_QUERY | tr '\n' ' ' | sed 's/"/\\"/g')\"}"
GRAPHQL_RESPONSE=$(curl --insecure \
  -X POST \
  -H "Content-Type: application/json" \
  -d $GRAPHQL_BODY \
  https://gato-graphql.lndo.site/graphql/website/)
```

Then:

```bash
SPANISH_LOCALE_USER_IDS=$(echo $GRAPHQL_RESPONSE \
  | grep -E -o '"spanishLocaleUserIDs\":"(.*)"' \
  | cut -d':' -f2- | cut -d'"' -f2- | rev | cut -d'"' -f2- | rev)

# I can't pass multiple IDs to `wp user meta`:
# $ wp user meta update "$(echo $SPANISH_LOCALE_USER_IDS)" locale "fr_FR"
# Then iterate the list, and execute command for each:
for USER_ID in $(echo $SPANISH_LOCALE_USER_IDS); do wp user update "$(echo $USER_ID)" --locale=fr_FR; done
```
