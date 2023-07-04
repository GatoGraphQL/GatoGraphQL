# Complementing WP-CLI

<a href="https://wp-cli.org" target="_blank">WP-CLI</a> is a command-line tool to interact with WordPress, that helps us automate tasks. It allows us to install a new site, create or update posts, activate plugins, modify the options, and much more.

WP-CLI commands can be nested:

1. Execute a WP-CLI command that returns the ID of some resource
2. Inject that ID into another WP-CLI command, to execute an operation on that resource

For instance, this script finds the ID for the post with some slug, and updates its meta:

```bash
wp post meta set $(wp post list --name="hello-world" --format=ids) _wp_page_template about.php
```

This script repeatedly creates a menu item and sets its ID as parent to another new menu item, thus defining their hierarchy (`"Most ancestor menu item"` > `"Parent menu item"` > `"Child menu item"`):

```bash
wp menu item add-custom bottom-menu "Child menu item" https://bbc.com --parent-id=$(wp menu item add-post bottom-menu 1 --title="Parent menu item" --parent-id=$(wp menu item add-post bottom-menu 1 --title="Most ancestor menu item" --porcelain) --porcelain)
```

As we learnt in the previous recipe, Gato GraphQL can augment WordPress capabilities for searching for data. As such, we can also use Gato GraphQL to find the data we need, and inject it into WP-CLI.

The following queries will demonstrate how to do that.

## Executing a GraphQL query from the terminal

Let's use the GraphQL query from the previous recipe, to find users with the Spanish locale, but limiting it to only 1 result (via `pagination: { limit: 1 }`):

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
    id
    name
    locale: metaValue(key: "locale")
  }
}
```

In the terminal, we can use `curl` to execute a query against the GraphQL server, passing the following data:

- Execute the `POST` method
- Accepted content type is `application/json`
- The query is passed via the body, as a dictionary under entry `"query"`
- The query must be formatted: all `"` must be escaped, and newlines are represented as `\n`

```bash
curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "query {\n  users(\n    filter: {\n      metaQuery: {\n        key: \"locale\",\n        compareBy: {\n          stringValue: {\n            value: \"es_[A-Z]+\"\n            operator: REGEXP\n          }\n        }\n      }\n    },\n    pagination: {\n      limit: 1\n    }\n  ) {\n    id\n    name\n    locale: metaValue(key: \"locale\")\n  }\n}"}' \
  https://gato-graphql-pro.lndo.site/graphql/
```

This prints the response right in the terminal:

```json
{"data":{"users":[{"id":3,"name":"Subscriber Bennett","locale":"es_AR"}]}}
```


```bash
GRAPHQL_RESPONSE=$(curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "query {\n  users(\n    filter: {\n      metaQuery: {\n        key: \"locale\",\n        compareBy: {\n          stringValue: {\n            value: \"es_[A-Z]+\"\n            operator: REGEXP\n          }\n        }\n      }\n    },\n    pagination: {\n      limit: 1\n    }\n  ) {\n    spanishLocaleUserID: id\n    name\n    locale: metaValue(key: \"locale\")\n  }\n}"}' \
  https://gato-graphql-pro.lndo.site/graphql/)

echo $GRAPHQL_RESPONSE
```

## Extracting an ID from the GraphQL response

Transforming query's '"' to '\"' and newlines to '\n':

```bash
GRAPHQL_RESPONSE=$(curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "query {\n  users(\n    filter: {\n      metaQuery: {\n        key: \"locale\",\n        compareBy: {\n          stringValue: {\n            value: \"es_[A-Z]+\"\n            operator: REGEXP\n          }\n        }\n      }\n    },\n    pagination: {\n      limit: 1\n    }\n  ) {\n    spanishLocaleUserID: id\n    name\n    locale: metaValue(key: \"locale\")\n  }\n}"}' \
  https://gato-graphql-pro.lndo.site/graphql/)

echo $GRAPHQL_RESPONSE
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
GRAPHQL_RESPONSE=$(curl \
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
GRAPHQL_RESPONSE=$(curl \
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
GRAPHQL_RESPONSE=$(curl \
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
