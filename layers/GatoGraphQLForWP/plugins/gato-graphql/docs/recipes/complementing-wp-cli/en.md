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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- The single endpoint is (by default) accessible under `graphql/`, and a custom endpoint is (by default) accessible under `graphql/{custom-endpoint-slug}/`
- The single endpoint is <a href="https://gatographql.com/guides/config/enabling-and-configuring-the-single-endpoint/" target="_blank">disabled by default</a>, so it must be enabled
- The single endpoint is public; to avoid unintentionally exposing private data, it is advised to enable it only when your website is not accessible to the Internet (eg: the site is on a development laptop, used to build a headless site)
- Otherwise, it is advised to <a href="https://gatographql.com/guides/use/creating-a-custom-endpoint/" target="_blank">create a custom endpoint</a>, <a href="https://gatographql.com/guides/special-features/public-private-and-password-protected-endpoints/#heading-private-endpoints" target="_blank">publish it as `private`</a>, and pass the cookies added by WordPress (once the user has been authenticated) to `curl` (you can use DevTools to inspect the request headers when in the WordPress dashboard)
- Alternatively (via extensions), we can restrict access to the single or custom endpoint via <a href="https://gatographql.com/guides/use/defining-access-control/" target="_blank">Access Control</a> (eg: checking that the <a href="https://gatographql.com/guides/config/restricting-access-by-visitor-ip/" target="_blank">visitor comes from IP `127.0.0.1`</a>).

</div>

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
GRAPHQL_BODY="{\"query\": \"$(echo $GRAPHQL_QUERY | tr '\n' ' ' | sed 's/"/\\"/g')\"}"
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
GRAPHQL_BODY="{\"query\": \"$(echo $GRAPHQL_QUERY | tr '\n' ' ' | sed 's/"/\\"/g')\"}"
GRAPHQL_RESPONSE=$(curl \
  -X POST \
  -H "Content-Type: application/json" \
  -d $GRAPHQL_BODY \
  https://mysite.com/graphql/)
```

## Retrieving multiple results at once

In the previous examples, we only produced a single user ID.

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
