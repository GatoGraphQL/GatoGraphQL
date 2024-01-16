# Complementing WP-CLI

[WP-CLI](https://wp-cli.org) is a command-line tool to interact with WordPress, that helps us automate tasks. It allows us to install a new site, create or update posts, activate plugins, modify the options, and much more.

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

As we learnt in the previous tutorial lesson, Gato GraphQL can augment WordPress capabilities for searching for data. As such, we can also use Gato GraphQL to find the data we need, and inject it into WP-CLI.

The following queries will demonstrate how to do that.

## Executing a GraphQL query from the terminal

Let's use the GraphQL query from the previous lesson, to find users with the Spanish locale, and execute a WP-CLI command on that user.

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
- The body is a dictionary with entry `"query"` and the GraphQL query (and, if needed, also entries `"variables"` and `"operationName"`)
- The query string must be formatted: All `"` must be escaped as `\"`, and newlines must be replaced with `\n`
- Point against the endpoint URL from Gato GraphQL (either the single endpoint, or some custom endpoint)

```bash
curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "query {\n  users(\n    filter: {\n      metaQuery: {\n        key: \"locale\",\n        compareBy: {\n          stringValue: {\n            value: \"es_[A-Z]+\"\n            operator: REGEXP\n          }\n        }\n      }\n    },\n    pagination: {\n      limit: 1\n    }\n  ) {\n    id\n    name\n    locale: metaValue(key: \"locale\")\n  }\n}"}' \
  https://mysite.com/graphql/
```

This prints the response right in the terminal:

```json
{"data":{"users":[{"id":3,"name":"Subscriber Bennett","locale":"es_AR"}]}}
```

## Extracting the ID from the GraphQL response

Similar to doing `--field=ID`, `--format=ids` or `--porcelain` in WP-CLI, we need to find a way to extract the specific piece of data that we need from the GraphQL response. In this example, that is the user ID.

We assign the GraphQL response to an environment variable (such as `GRAPHQL_RESPONSE`), and identify the user ID with a particular alias (such as `spanishLocaleUserID`):

```bash
GRAPHQL_RESPONSE=$(curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "query {\n  users(\n    filter: {\n      metaQuery: {\n        key: \"locale\",\n        compareBy: {\n          stringValue: {\n            value: \"es_[A-Z]+\"\n            operator: REGEXP\n          }\n        }\n      }\n    },\n    pagination: {\n      limit: 1\n    }\n  ) {\n    spanishLocaleUserID: id\n    name\n    locale: metaValue(key: \"locale\")\n  }\n}"}' \
  https://mysite.com/graphql/)
```

Executing `echo $GRAPHQL_RESPONSE` we can visualize the response:

```json
{"data":{"users":[{"spanishLocaleUserID":3,"name":"Subscriber Bennett","locale":"es_AR"}]}}
```

Next, we execute `grep` with a regex matching the `"spanishLocaleUserID":{ID}` pattern, and extract the ID into environment variable `SPANISH_LOCALE_USER_ID`:

```bash
SPANISH_LOCALE_USER_ID=$(echo $GRAPHQL_RESPONSE \
  | grep -E -o '"spanishLocaleUserID\":(\d+)' \
  | cut -d':' -f2- | cut -d'"' -f2- | rev | cut -d'"' -f2- | rev)
```

Now, we can inject the value of this variable into WP-CLI:

```bash
wp user update "$(echo $SPANISH_LOCALE_USER_ID)" --locale=fr_FR
```

## Making the GraphQL query more readable

When formatting the GraphQL query to input it into `curl`, it became difficult to read.

A solution is to apply the transformations using bash commands, such as `tr` and `sed`:

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
GRAPHQL_BODY="{\"query\": \"$(echo $GRAPHQL_QUERY | sed '/^#/d' | tr '\n' ' ' | sed 's/"/\\"/g')\"}"
GRAPHQL_RESPONSE=$(curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d $GRAPHQL_BODY \
  https://mysite.com/graphql/)
```

## Adding syntax highlighting to the GraphQL query

A further iteration from the previous step is to place the GraphQL query in a separate `.gql` file, which can then be edited with an editor (such as VSCode) and use its syntax highlighting:

```graphql
# This query is stored in file "find-user-with-spanish-locale.gql"
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

Then, we read the contents of this file using `cat`:

```bash
GRAPHQL_QUERY=$(cat find-user-with-spanish-locale.gql)
GRAPHQL_BODY="{\"query\": \"$(echo $GRAPHQL_QUERY | sed '/^#/d' | tr '\n' ' ' | sed 's/"/\\"/g')\"}"
GRAPHQL_RESPONSE=$(curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d $GRAPHQL_BODY \
  https://mysite.com/graphql/)
```
