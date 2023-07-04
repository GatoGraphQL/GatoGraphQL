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

Let's use the GraphQL query from the previous recipe, to find users with the Spanish locale, and execute a WP-CLI command on that user.

We first limit the result to only 1 user (via `pagination: { limit: 1 }`):

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

In the terminal, we can use `curl` (or a similar tool) to execute a query against the GraphQL server, passing the following data:

- Execute the `POST` method
- Accepted content type is `application/json`
- The query is passed via the body, as a dictionary under entry `"query"`
- The query must be formatted: all `"` must be escaped as `\"`, and newlines are replaced with `\n`
- The endpoint URL

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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- The endpoint can either be the single endpoint (under `graphql/`), or a custom endpoint (under `graphql/{custom-endpoint-name}`)
- The single endpoint is <a href="https://gatographql.com/guides/config/enabling-and-configuring-the-single-endpoint/" target="_blank">disabled by default</a>, so it must be enabled
- The single endpoint is public; to avoid unintentionally exposing private data, it is advised to enable it only when your website is not accessible to the Internet (eg: the site is on a development laptop, used to build a headless site)
- Otherwise, it is advised to <a href="https://gatographql.com/guides/use/creating-a-custom-endpoint/" target="_blank">create a custom endpoint</a>, <a href="https://gatographql.com/guides/special-features/public-private-and-password-protected-endpoints/#heading-private-endpoints" target="_blank">publish it as `private`</a>, and pass the cookies added by WordPress (once the user has been authenticated) to `curl` (you can use DevTools to inspect the request headers when in the WordPress dashboard)
- Alternatively, we can restrict access to the endpoint via <a href="https://gatographql.com/guides/use/defining-access-control/" target="_blank">Access Control</a>, for instance checking that the <a href="https://gatographql.com/guides/config/restricting-access-by-visitor-ip/" target="_blank">visitor comes from IP `127.0.0.1`</a>.

</div>

## Extracting the ID from the GraphQL response

Let's use command-line tools for manipulating the GraphQL response, and extract from it the required data.

First, we assign the GraphQL response to an environment variable:

```bash
GRAPHQL_RESPONSE=$(curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "query {\n  users(\n    filter: {\n      metaQuery: {\n        key: \"locale\",\n        compareBy: {\n          stringValue: {\n            value: \"es_[A-Z]+\"\n            operator: REGEXP\n          }\n        }\n      }\n    },\n    pagination: {\n      limit: 1\n    }\n  ) {\n    id\n    name\n    locale: metaValue(key: \"locale\")\n  }\n}"}' \
  https://gato-graphql-pro.lndo.site/graphql/)
```

We can do `echo $GRAPHQL_RESPONSE` to visualize the response.

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
