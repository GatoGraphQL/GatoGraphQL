# Injecting multiple resources into WP-CLI

In the previous tutorial lesson, we only retrieved (and injected into WP-CLI) a single user ID. Now, let's retrieve multiple user IDs while executing a single GraphQL query.

In this GraphQL query:

- We remove the `pagination` argument from the query, as to retrieve the list of all the users with Spanish locale
- We use [**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/) to export a list of the user IDs, under dynamic variable `$userIDs`
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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

With [**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/) we can [execute complex functionality within a single request](https://gatographql.com/guides/schema/executing-multiple-queries-concurrently/), and better organize the logic by splitting the GraphQL document into a series a logical/atomic units:

- There is no limit in how many operations can be added to the pipeline
- Any operation can declare more than one dependency:

```graphql
query SomeQuery @depends(on: ["SomePreviousOp", "AnotherPreviousOp"]) {
  # ...
}
```

- Any operation can depend on another operation, which itself depends on another operation, and so on:

```graphql
query ExecuteFirst
  # ...
}
query ExecuteSecond @depends(on: ["ExecuteFirst"]) {
  # ...
}
query ExecuteThird @depends(on: ["ExecuteSecond"]) {
  # ...
}
```

- We can execute any of the operations in the document:
  - `?operationName=ExecuteThird` executes `ExecuteFirst` > `ExecuteSecond` > `ExecuteThird`
  - `?operationName=ExecuteSecond` executes `ExecuteFirst` > `ExecuteSecond`
  - `?operationName=ExecuteFirst` executes `ExecuteFirst`

- When `@depends` receives only one operation, it can receive a `String` (instead of `[String]`):

```graphql
query ExecuteFirst
  # ...
}
query ExecuteSecond @depends(on: "ExecuteFirst") {
  # ...
}
```

- Both `query` and `mutation` operations can depend on each other:

```graphql
query GetAndExportData
  # ...
}
mutation MutateData @depends(on: "GetAndExportData") {
  # ...
}
query CountMutatedResults @depends(on: "MutateData") {
  # ...
}
```

- [Dynamic variables](https://gatographql.com/guides/augment/dynamic-variables/) do not need to be declared in the operation
- Via input `@export(type:)` we can select the output of the data exported into the dynamic variable:
  - `SINGLE` (default): A single field value
  - `LIST`: An array containing the field value of multiple resources
  - `DICTIONARY`: A dictionary containing the field value of multiple resources, with key: `${resource ID}` and value: `${field value}`

</div>

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
GRAPHQL_BODY="{\"operationName\": \"FormatAndPrintData\", \"query\": \"$(echo $GRAPHQL_QUERY | sed '/^#/d' | tr '\n' ' ' | sed 's/"/\\"/g')\"}"
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
