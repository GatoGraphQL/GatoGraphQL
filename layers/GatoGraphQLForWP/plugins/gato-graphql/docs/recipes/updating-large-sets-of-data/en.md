# Updating large sets of data

The GraphQL query below executes itself recursively, with the goal of updating thousands of resources in stages, with each stage affecting a handful of resources only, as to not overload the system all at once.

When first invoked, the GraphQL query divides the total number of resources to update into segments (calculated using the provided `$limit` variable), and executes itself via a new HTTP request for each of the segments (passing over the corresponding `$offset` as a variable), thus updating only a subset of all resources at a given time. Once all resources have been updated, the execution of the GraphQL query reaches the end:

```graphql
query ExportExecute(
  $offset: Int
) {
  executeQuery: _notNull(value: $offset)
    @export(as: "executeQuery")
}

query CalculateVars($limit: Int! = 10)
  @depends(on: "ExportExecute")
  @skip(if: $executeQuery)
{
  # Calculate the number of HTTP requests to be sent
  commentCount
  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)
  numberExecutions: _floatCeil(number: $__fractionalNumberExecutions)
  
  # Generate a list of the offset
  arrayOffsets: _arrayPad(array: [], length: $__numberExecutions, value: null)
    @underEachArrayItem(
      passIndexOnwardsAs: "position"
    )
      @applyField(
        name: "_intMultiply"
        arguments: {
          multiply: $position
          with: $limit
        }
        setResultInResponse: true
      )
    @export(as: "offsets")

  # Vars needed to generate a list of the HTTP Request inputs
  url: _httpRequestFullURL
    @export(as: "url")
  method: _httpRequestMethod
    @export(as: "method")
  headers: _httpRequestHeaders
  headersInputList: _objectConvertToNameValueEntryList(
    object: $__headers
  )
    @export(as: "headersInputList")
  body: _httpRequestBody
  bodyJSONObject: _strDecodeJSONObject(string: $__body)
    @export(as: "bodyJSONObject")
  bodyHasVariables: _propertyExistsInJSONObject(
    object: $__bodyJSONObject,
    by: { key: "variables" }
  )
    @export(as: "bodyHasVariables")
}

query GenerateVars
  @depends(on: ["ExportExecute", "CalculateVars"])
  @skip(if: $executeQuery)
{
  bodyJSON: _echo(value: $bodyJSONObject)
    @unless(condition: $bodyHasVariables)
      @objectAddEntry(
        object: $bodyJSONObject,
        key: "variables"
        value: {}
      )
    @export(as: "bodyJSON")
}

query GenerateRequestInputs
  @depends(on: ["ExportExecute", "GenerateVars"])
  @skip(if: $executeQuery)
{
  # Generate a list of the HTTP Request inputs (without the offset)
  requestInputs: _echo(value: $offsets)
    @underEachArrayItem(
      passValueOnwardsAs: "requestOffset"
      affectDirectivesUnderPos: [1, 2]
    )
      @applyField(
        name: "_objectAddEntry",
        arguments: {
          object: $bodyJSON
          underPath: "variables"
          key: "offset"
          value: $requestOffset
        },
        passOnwardsAs: "itemJSON"
      )
      @applyField(
        name: "_echo",
        arguments: {
          value: {
            url: $url
            method: $method
            options: {
              headers: $headersInputList
              json: $itemJSON
            }
          }
        },
        setResultInResponse: true
      )
    @export(as: "requestInputs")
}

query ExecuteURLs
  @depends(on: ["ExportExecute", "GenerateRequestInputs"])
  @skip(if: $executeQuery)
{
  _sendHTTPRequests(inputs: $requestInputs) {
    statusCode
    contentType
    body
      @remove
    bodyJSON: _strDecodeJSONObject(string: $__body)
  }
}

query ExecuteQuery(
  $offset: Int
)
  @depends(on: "ExportExecute")
  @include(if: $executeQuery)
{
  message: _sprintf(string: "Executed the query with $offset '%s'", values: [$offset])
}

query ExecuteAll
  @depends(on: ["ExecuteURLs", "ExecuteQuery"])
{
  id
}
```

Below is the step-by-step analysis of how this GraphQL works.

## Updating thousands of resources in bulk

Sometimes we need to execute an update that involves thousands of resources in the DB. For instance, consider the following comment (from a WordPress community online group):

> I find that for a lot of clients I'm working with large sets of data (10,000+ product variations for 1 product, or 13,000+ media files) ... inevitably the clients want to be able to bulk edit lots of things at once - like tag 2000 media files with the same tag.

Thanks to [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/), Gato GraphQL supports retrieving and updating thousands of resources from the DB via a single GraphQL query:

```graphql
mutation ReplaceOldWithNewDomainInPosts {
  posts(pagination: { limit: 30000 }) {
    id
    contentSource
    adaptedContentSource: _strReplace(
      search: "https://my-old-domain.com"
      replaceWith: "https://my-new-domain.com"
      in: $__contentSource
    )
    update(input: {
      contentAs: { html: $__adaptedContentSource }
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
    }
  }
}
```

Depending on the resilience of the system, though, this single GraphQL execution might put too much load on the DB, even making it crash.

## Paginating the execution of the GraphQL query

If updating thousands of resources at once makes the system crash, the solution is simple: Instead of executing the GraphQL just once for thousands of resources, we can execute it hundreds of times for dozens of resources each time.

The following bash scripts first finds out the total number of comments via `commentCount`, then calculates the segments considering env var `$ENTRIES_TO_PROCESS`, and calculates the pagination parameters and calls the GraphQL query for each segment (simply retrieving the comments from that segment):

```bash
# Get the number of comments in the site
GRAPHQL_RESPONSE=$(curl
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "{\n  commentCount\n}"}' \
  https://mysite.com/graphql/)

# Extract the number of comments into a variable
COMMENT_COUNT=$(echo $GRAPHQL_RESPONSE \
  | grep -E -o '"commentCount\":([0-9]+)' \
  | cut -d':' -f2-)

echo "Number of comments: $COMMENT_COUNT"

# How many entries will be processed on each query
ENTRIES_TO_PROCESS=10

# Calculate how many requests must be triggered
PAGINATION_COUNT=$(($(($COMMENT_COUNT / $ENTRIES_TO_PROCESS)) + $(($(($COMMENT_COUNT % $ENTRIES_TO_PROCESS)) ? 1 : 0))))

echo "Number of requests to process (at $ENTRIES_TO_PROCESS entries per request): $PAGINATION_COUNT"

# Execute the requests, at one per second
for PAGINATION_NUMBER in $(seq 0 $(($PAGINATION_COUNT - 1))); do sleep 1 && echo "\n\nPagination number: $PAGINATION_NUMBER\n" && curl -X POST -H "Content-Type: application/json" -d "{\"query\": \"{ comments(pagination: { limit: $ENTRIES_TO_PROCESS, offset: $(($PAGINATION_NUMBER * $ENTRIES_TO_PROCESS)) }) { id date content } }\"}" https://mysite.com/graphql/ ; done
```

## Executing the GraphQL query recursively

The solution above involves bash scripting. Would it be possible to have that same logic already performed within the GraphQL query itself?

If executing a single GraphQL query affecting thousands of resources makes the system crash, we can adapt the query to execute itself recursively, affecting a handful of resources each time, until all resources have been updated.

We can then split the 

Use query:

```graphql
query ExportExecute(
  $offset: Int
) {
  executeQuery: _notNull(value: $offset)
    @export(as: "executeQuery")
}

query CalculateVars($limit: Int! = 10)
  @depends(on: "ExportExecute")
  @skip(if: $executeQuery)
{
  # Calculate the number of HTTP requests to be sent
  commentCount
  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)
  numberExecutions: _floatCeil(number: $__fractionalNumberExecutions)
  
  # Generate a list of the offset
  arrayOffsets: _arrayPad(array: [], length: $__numberExecutions, value: null)
    @underEachArrayItem(
      passIndexOnwardsAs: "position"
    )
      @applyField(
        name: "_intMultiply"
        arguments: {
          multiply: $position
          with: $limit
        }
        setResultInResponse: true
      )
    @export(as: "offsets")

  # Vars needed to generate a list of the HTTP Request inputs
  url: _httpRequestFullURL
    @export(as: "url")
  method: _httpRequestMethod
    @export(as: "method")
  headers: _httpRequestHeaders
  headersInputList: _objectConvertToNameValueEntryList(
    object: $__headers
  )
    @export(as: "headersInputList")
  body: _httpRequestBody
  bodyJSONObject: _strDecodeJSONObject(string: $__body)
    @export(as: "bodyJSONObject")
  bodyHasVariables: _propertyExistsInJSONObject(
    object: $__bodyJSONObject,
    by: { key: "variables" }
  )
    @export(as: "bodyHasVariables")
}

query GenerateVars
  @depends(on: ["ExportExecute", "CalculateVars"])
  @skip(if: $executeQuery)
{
  bodyJSON: _echo(value: $bodyJSONObject)
    @unless(condition: $bodyHasVariables)
      @objectAddEntry(
        object: $bodyJSONObject,
        key: "variables"
        value: {}
      )
    @export(as: "bodyJSON")
}

query GenerateRequestInputs
  @depends(on: ["ExportExecute", "GenerateVars"])
  @skip(if: $executeQuery)
{
  # Generate a list of the HTTP Request inputs (without the offset)
  requestInputs: _echo(value: $offsets)
    @underEachArrayItem(
      passValueOnwardsAs: "requestOffset"
      affectDirectivesUnderPos: [1, 2]
    )
      @applyField(
        name: "_objectAddEntry",
        arguments: {
          object: $bodyJSON
          underPath: "variables"
          key: "offset"
          value: $requestOffset
        },
        passOnwardsAs: "itemJSON"
      )
      @applyField(
        name: "_echo",
        arguments: {
          value: {
            url: $url
            method: $method
            options: {
              headers: $headersInputList
              json: $itemJSON
            }
          }
        },
        setResultInResponse: true
      )
    @export(as: "requestInputs")
}

query ExecuteURLs
  @depends(on: ["ExportExecute", "GenerateRequestInputs"])
  @skip(if: $executeQuery)
{
  _sendHTTPRequests(inputs: $requestInputs) {
    statusCode
    contentType
    body
      @remove
    bodyJSON: _strDecodeJSONObject(string: $__body)
  }
}

query ExecuteQuery(
  $offset: Int
)
  @depends(on: "ExportExecute")
  @include(if: $executeQuery)
{
  message: _sprintf(string: "Executed the query with $offset '%s'", values: [$offset])
}

query ExecuteAll
  @depends(on: ["ExecuteURLs", "ExecuteQuery"])
{
}
```

It produces:

```json
{
  "data": {
    "executeQuery": false,
    "commentCount": 21,
    "fractionalNumberExecutions": 2.1,
    "numberExecutions": 3,
    "arrayOffsets": [
      0,
      10,
      20
    ],
    "url": "https:\/\/gato-graphql-pro.lndo.site\/wp-admin\/edit.php?page=gato_graphql&action=execute_query",
    "method": "POST",
    "headers": {
      "authorization": "",
      "host": "gato-graphql-pro.lndo.site",
      "user-agent": "GuzzleHttp\/7",
      "content-length": "3364",
      "accept": "application\/json",
      "content-type": "application\/json",
      "cookie": "wordpress_test_cookie=WP%20Cookie%20check; wordpress_sec_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wordpress_logged_in_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wp-settings-time-1={REPLACED_FOR_TESTING}",
      "x-forwarded-for": "172.19.0.1",
      "x-forwarded-host": "gato-graphql-pro.lndo.site",
      "x-forwarded-port": "443",
      "x-forwarded-proto": "https",
      "x-lando": "on",
      "x-real-ip": "172.19.0.1",
      "accept-encoding": "gzip"
    },
    "headersInputList": [
      {
        "name": "authorization",
        "value": ""
      },
      {
        "name": "host",
        "value": "gato-graphql-pro.lndo.site"
      },
      {
        "name": "user-agent",
        "value": "GuzzleHttp\/7"
      },
      {
        "name": "content-length",
        "value": "3364"
      },
      {
        "name": "accept",
        "value": "application\/json"
      },
      {
        "name": "content-type",
        "value": "application\/json"
      },
      {
        "name": "cookie",
        "value": "wordpress_test_cookie=WP%20Cookie%20check; wordpress_sec_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wordpress_logged_in_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wp-settings-time-1={REPLACED_FOR_TESTING}"
      },
      {
        "name": "x-forwarded-for",
        "value": "172.19.0.1"
      },
      {
        "name": "x-forwarded-host",
        "value": "gato-graphql-pro.lndo.site"
      },
      {
        "name": "x-forwarded-port",
        "value": "443"
      },
      {
        "name": "x-forwarded-proto",
        "value": "https"
      },
      {
        "name": "x-lando",
        "value": "on"
      },
      {
        "name": "x-real-ip",
        "value": "172.19.0.1"
      },
      {
        "name": "accept-encoding",
        "value": "gzip"
      }
    ],
    "body": "{\"operationName\":\"ExecuteAll\",\"query\":\"query ExportExecute(\\n  $offset: Int\\n) {\\n  executeQuery: _notNull(value: $offset)\\n    @export(as: \\\"executeQuery\\\")\\n}\\n\\nquery CalculateVars($limit: Int! = 10)\\n  @depends(on: \\\"ExportExecute\\\")\\n  @skip(if: $executeQuery)\\n{\\n  # Calculate the number of HTTP requests to be sent\\n  commentCount\\n  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)\\n  numberExecutions: _floatCeil(number: $__fractionalNumberExecutions)\\n  \\n  # Generate a list of the offset\\n  arrayOffsets: _arrayPad(array: [], length: $__numberExecutions, value: null)\\n    @underEachArrayItem(\\n      passIndexOnwardsAs: \\\"position\\\"\\n    )\\n      @applyField(\\n        name: \\\"_intMultiply\\\"\\n        arguments: {\\n          multiply: $position\\n          with: $limit\\n        }\\n        setResultInResponse: true\\n      )\\n    @export(as: \\\"offsets\\\")\\n\\n  # Vars needed to generate a list of the HTTP Request inputs\\n  url: _httpRequestFullURL\\n    @export(as: \\\"url\\\")\\n  method: _httpRequestMethod\\n    @export(as: \\\"method\\\")\\n  headers: _httpRequestHeaders\\n  headersInputList: _objectConvertToNameValueEntryList(\\n    object: $__headers\\n  )\\n    @export(as: \\\"headersInputList\\\")\\n  body: _httpRequestBody\\n  bodyJSONObject: _strDecodeJSONObject(string: $__body)\\n    @export(as: \\\"bodyJSONObject\\\")\\n  bodyHasVariables: _propertyExistsInJSONObject(\\n    object: $__bodyJSONObject,\\n    by: { key: \\\"variables\\\" }\\n  )\\n    @export(as: \\\"bodyHasVariables\\\")\\n}\\n\\nquery GenerateVars\\n  @depends(on: [\\\"ExportExecute\\\", \\\"CalculateVars\\\"])\\n  @skip(if: $executeQuery)\\n{\\n  bodyJSON: _echo(value: $bodyJSONObject)\\n    @unless(condition: $bodyHasVariables)\\n      @objectAddEntry(\\n        object: $bodyJSONObject,\\n        key: \\\"variables\\\"\\n        value: {}\\n      )\\n    @export(as: \\\"bodyJSON\\\")\\n}\\n\\nquery GenerateRequestInputs\\n  @depends(on: [\\\"ExportExecute\\\", \\\"GenerateVars\\\"])\\n  @skip(if: $executeQuery)\\n{\\n  # Generate a list of the HTTP Request inputs (without the offset)\\n  requestInputs: _echo(value: $offsets)\\n    @underEachArrayItem(\\n      passValueOnwardsAs: \\\"requestOffset\\\"\\n      affectDirectivesUnderPos: [1, 2]\\n    )\\n      @applyField(\\n        name: \\\"_objectAddEntry\\\",\\n        arguments: {\\n          object: $bodyJSON\\n          underPath: \\\"variables\\\"\\n          key: \\\"offset\\\"\\n          value: $requestOffset\\n        },\\n        passOnwardsAs: \\\"itemJSON\\\"\\n      )\\n      @applyField(\\n        name: \\\"_echo\\\",\\n        arguments: {\\n          value: {\\n            url: $url\\n            method: $method\\n            options: {\\n              headers: $headersInputList\\n              json: $itemJSON\\n            }\\n          }\\n        },\\n        setResultInResponse: true\\n      )\\n    @export(as: \\\"requestInputs\\\")\\n}\\n\\nquery ExecuteURLs\\n  @depends(on: [\\\"ExportExecute\\\", \\\"GenerateRequestInputs\\\"])\\n  @skip(if: $executeQuery)\\n{\\n  _sendHTTPRequests(inputs: $requestInputs) {\\n    statusCode\\n    contentType\\n    body\\n      @remove\\n    bodyJSON: _strDecodeJSONObject(string: $__body)\\n  }\\n}\\n\\nquery ExecuteQuery(\\n  $offset: Int\\n)\\n  @depends(on: \\\"ExportExecute\\\")\\n  @include(if: $executeQuery)\\n{\\n  message: _sprintf(string: \\\"Executed the query with $offset '%s'\\\", values: [$offset])\\n}\\n\\nquery ExecuteAll\\n  @depends(on: [\\\"ExecuteURLs\\\", \\\"ExecuteQuery\\\"])\\n{\\n}\"}",
    "bodyJSONObject": {
      "operationName": "ExecuteAll",
      "query": "query ExportExecute(\n  $offset: Int\n) {\n  executeQuery: _notNull(value: $offset)\n    @export(as: \"executeQuery\")\n}\n\nquery CalculateVars($limit: Int! = 10)\n  @depends(on: \"ExportExecute\")\n  @skip(if: $executeQuery)\n{\n  # Calculate the number of HTTP requests to be sent\n  commentCount\n  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)\n  numberExecutions: _floatCeil(number: $__fractionalNumberExecutions)\n  \n  # Generate a list of the offset\n  arrayOffsets: _arrayPad(array: [], length: $__numberExecutions, value: null)\n    @underEachArrayItem(\n      passIndexOnwardsAs: \"position\"\n    )\n      @applyField(\n        name: \"_intMultiply\"\n        arguments: {\n          multiply: $position\n          with: $limit\n        }\n        setResultInResponse: true\n      )\n    @export(as: \"offsets\")\n\n  # Vars needed to generate a list of the HTTP Request inputs\n  url: _httpRequestFullURL\n    @export(as: \"url\")\n  method: _httpRequestMethod\n    @export(as: \"method\")\n  headers: _httpRequestHeaders\n  headersInputList: _objectConvertToNameValueEntryList(\n    object: $__headers\n  )\n    @export(as: \"headersInputList\")\n  body: _httpRequestBody\n  bodyJSONObject: _strDecodeJSONObject(string: $__body)\n    @export(as: \"bodyJSONObject\")\n  bodyHasVariables: _propertyExistsInJSONObject(\n    object: $__bodyJSONObject,\n    by: { key: \"variables\" }\n  )\n    @export(as: \"bodyHasVariables\")\n}\n\nquery GenerateVars\n  @depends(on: [\"ExportExecute\", \"CalculateVars\"])\n  @skip(if: $executeQuery)\n{\n  bodyJSON: _echo(value: $bodyJSONObject)\n    @unless(condition: $bodyHasVariables)\n      @objectAddEntry(\n        object: $bodyJSONObject,\n        key: \"variables\"\n        value: {}\n      )\n    @export(as: \"bodyJSON\")\n}\n\nquery GenerateRequestInputs\n  @depends(on: [\"ExportExecute\", \"GenerateVars\"])\n  @skip(if: $executeQuery)\n{\n  # Generate a list of the HTTP Request inputs (without the offset)\n  requestInputs: _echo(value: $offsets)\n    @underEachArrayItem(\n      passValueOnwardsAs: \"requestOffset\"\n      affectDirectivesUnderPos: [1, 2]\n    )\n      @applyField(\n        name: \"_objectAddEntry\",\n        arguments: {\n          object: $bodyJSON\n          underPath: \"variables\"\n          key: \"offset\"\n          value: $requestOffset\n        },\n        passOnwardsAs: \"itemJSON\"\n      )\n      @applyField(\n        name: \"_echo\",\n        arguments: {\n          value: {\n            url: $url\n            method: $method\n            options: {\n              headers: $headersInputList\n              json: $itemJSON\n            }\n          }\n        },\n        setResultInResponse: true\n      )\n    @export(as: \"requestInputs\")\n}\n\nquery ExecuteURLs\n  @depends(on: [\"ExportExecute\", \"GenerateRequestInputs\"])\n  @skip(if: $executeQuery)\n{\n  _sendHTTPRequests(inputs: $requestInputs) {\n    statusCode\n    contentType\n    body\n      @remove\n    bodyJSON: _strDecodeJSONObject(string: $__body)\n  }\n}\n\nquery ExecuteQuery(\n  $offset: Int\n)\n  @depends(on: \"ExportExecute\")\n  @include(if: $executeQuery)\n{\n  message: _sprintf(string: \"Executed the query with $offset '%s'\", values: [$offset])\n}\n\nquery ExecuteAll\n  @depends(on: [\"ExecuteURLs\", \"ExecuteQuery\"])\n{\n}"
    },
    "bodyHasVariables": false,
    "bodyJSON": {
      "operationName": "ExecuteAll",
      "query": "query ExportExecute(\n  $offset: Int\n) {\n  executeQuery: _notNull(value: $offset)\n    @export(as: \"executeQuery\")\n}\n\nquery CalculateVars($limit: Int! = 10)\n  @depends(on: \"ExportExecute\")\n  @skip(if: $executeQuery)\n{\n  # Calculate the number of HTTP requests to be sent\n  commentCount\n  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)\n  numberExecutions: _floatCeil(number: $__fractionalNumberExecutions)\n  \n  # Generate a list of the offset\n  arrayOffsets: _arrayPad(array: [], length: $__numberExecutions, value: null)\n    @underEachArrayItem(\n      passIndexOnwardsAs: \"position\"\n    )\n      @applyField(\n        name: \"_intMultiply\"\n        arguments: {\n          multiply: $position\n          with: $limit\n        }\n        setResultInResponse: true\n      )\n    @export(as: \"offsets\")\n\n  # Vars needed to generate a list of the HTTP Request inputs\n  url: _httpRequestFullURL\n    @export(as: \"url\")\n  method: _httpRequestMethod\n    @export(as: \"method\")\n  headers: _httpRequestHeaders\n  headersInputList: _objectConvertToNameValueEntryList(\n    object: $__headers\n  )\n    @export(as: \"headersInputList\")\n  body: _httpRequestBody\n  bodyJSONObject: _strDecodeJSONObject(string: $__body)\n    @export(as: \"bodyJSONObject\")\n  bodyHasVariables: _propertyExistsInJSONObject(\n    object: $__bodyJSONObject,\n    by: { key: \"variables\" }\n  )\n    @export(as: \"bodyHasVariables\")\n}\n\nquery GenerateVars\n  @depends(on: [\"ExportExecute\", \"CalculateVars\"])\n  @skip(if: $executeQuery)\n{\n  bodyJSON: _echo(value: $bodyJSONObject)\n    @unless(condition: $bodyHasVariables)\n      @objectAddEntry(\n        object: $bodyJSONObject,\n        key: \"variables\"\n        value: {}\n      )\n    @export(as: \"bodyJSON\")\n}\n\nquery GenerateRequestInputs\n  @depends(on: [\"ExportExecute\", \"GenerateVars\"])\n  @skip(if: $executeQuery)\n{\n  # Generate a list of the HTTP Request inputs (without the offset)\n  requestInputs: _echo(value: $offsets)\n    @underEachArrayItem(\n      passValueOnwardsAs: \"requestOffset\"\n      affectDirectivesUnderPos: [1, 2]\n    )\n      @applyField(\n        name: \"_objectAddEntry\",\n        arguments: {\n          object: $bodyJSON\n          underPath: \"variables\"\n          key: \"offset\"\n          value: $requestOffset\n        },\n        passOnwardsAs: \"itemJSON\"\n      )\n      @applyField(\n        name: \"_echo\",\n        arguments: {\n          value: {\n            url: $url\n            method: $method\n            options: {\n              headers: $headersInputList\n              json: $itemJSON\n            }\n          }\n        },\n        setResultInResponse: true\n      )\n    @export(as: \"requestInputs\")\n}\n\nquery ExecuteURLs\n  @depends(on: [\"ExportExecute\", \"GenerateRequestInputs\"])\n  @skip(if: $executeQuery)\n{\n  _sendHTTPRequests(inputs: $requestInputs) {\n    statusCode\n    contentType\n    body\n      @remove\n    bodyJSON: _strDecodeJSONObject(string: $__body)\n  }\n}\n\nquery ExecuteQuery(\n  $offset: Int\n)\n  @depends(on: \"ExportExecute\")\n  @include(if: $executeQuery)\n{\n  message: _sprintf(string: \"Executed the query with $offset '%s'\", values: [$offset])\n}\n\nquery ExecuteAll\n  @depends(on: [\"ExecuteURLs\", \"ExecuteQuery\"])\n{\n}",
      "variables": {}
    },
    "requestInputs": [
      {
        "url": "https:\/\/gato-graphql-pro.lndo.site\/wp-admin\/edit.php?page=gato_graphql&action=execute_query",
        "method": "POST",
        "options": {
          "headers": [
            {
              "name": "authorization",
              "value": ""
            },
            {
              "name": "host",
              "value": "gato-graphql-pro.lndo.site"
            },
            {
              "name": "user-agent",
              "value": "GuzzleHttp\/7"
            },
            {
              "name": "content-length",
              "value": "3364"
            },
            {
              "name": "accept",
              "value": "application\/json"
            },
            {
              "name": "content-type",
              "value": "application\/json"
            },
            {
              "name": "cookie",
              "value": "wordpress_test_cookie=WP%20Cookie%20check; wordpress_sec_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wordpress_logged_in_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wp-settings-time-1={REPLACED_FOR_TESTING}"
            },
            {
              "name": "x-forwarded-for",
              "value": "172.19.0.1"
            },
            {
              "name": "x-forwarded-host",
              "value": "gato-graphql-pro.lndo.site"
            },
            {
              "name": "x-forwarded-port",
              "value": "443"
            },
            {
              "name": "x-forwarded-proto",
              "value": "https"
            },
            {
              "name": "x-lando",
              "value": "on"
            },
            {
              "name": "x-real-ip",
              "value": "172.19.0.1"
            },
            {
              "name": "accept-encoding",
              "value": "gzip"
            }
          ],
          "json": {
            "operationName": "ExecuteAll",
            "query": "query ExportExecute(\n  $offset: Int\n) {\n  executeQuery: _notNull(value: $offset)\n    @export(as: \"executeQuery\")\n}\n\nquery CalculateVars($limit: Int! = 10)\n  @depends(on: \"ExportExecute\")\n  @skip(if: $executeQuery)\n{\n  # Calculate the number of HTTP requests to be sent\n  commentCount\n  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)\n  numberExecutions: _floatCeil(number: $__fractionalNumberExecutions)\n  \n  # Generate a list of the offset\n  arrayOffsets: _arrayPad(array: [], length: $__numberExecutions, value: null)\n    @underEachArrayItem(\n      passIndexOnwardsAs: \"position\"\n    )\n      @applyField(\n        name: \"_intMultiply\"\n        arguments: {\n          multiply: $position\n          with: $limit\n        }\n        setResultInResponse: true\n      )\n    @export(as: \"offsets\")\n\n  # Vars needed to generate a list of the HTTP Request inputs\n  url: _httpRequestFullURL\n    @export(as: \"url\")\n  method: _httpRequestMethod\n    @export(as: \"method\")\n  headers: _httpRequestHeaders\n  headersInputList: _objectConvertToNameValueEntryList(\n    object: $__headers\n  )\n    @export(as: \"headersInputList\")\n  body: _httpRequestBody\n  bodyJSONObject: _strDecodeJSONObject(string: $__body)\n    @export(as: \"bodyJSONObject\")\n  bodyHasVariables: _propertyExistsInJSONObject(\n    object: $__bodyJSONObject,\n    by: { key: \"variables\" }\n  )\n    @export(as: \"bodyHasVariables\")\n}\n\nquery GenerateVars\n  @depends(on: [\"ExportExecute\", \"CalculateVars\"])\n  @skip(if: $executeQuery)\n{\n  bodyJSON: _echo(value: $bodyJSONObject)\n    @unless(condition: $bodyHasVariables)\n      @objectAddEntry(\n        object: $bodyJSONObject,\n        key: \"variables\"\n        value: {}\n      )\n    @export(as: \"bodyJSON\")\n}\n\nquery GenerateRequestInputs\n  @depends(on: [\"ExportExecute\", \"GenerateVars\"])\n  @skip(if: $executeQuery)\n{\n  # Generate a list of the HTTP Request inputs (without the offset)\n  requestInputs: _echo(value: $offsets)\n    @underEachArrayItem(\n      passValueOnwardsAs: \"requestOffset\"\n      affectDirectivesUnderPos: [1, 2]\n    )\n      @applyField(\n        name: \"_objectAddEntry\",\n        arguments: {\n          object: $bodyJSON\n          underPath: \"variables\"\n          key: \"offset\"\n          value: $requestOffset\n        },\n        passOnwardsAs: \"itemJSON\"\n      )\n      @applyField(\n        name: \"_echo\",\n        arguments: {\n          value: {\n            url: $url\n            method: $method\n            options: {\n              headers: $headersInputList\n              json: $itemJSON\n            }\n          }\n        },\n        setResultInResponse: true\n      )\n    @export(as: \"requestInputs\")\n}\n\nquery ExecuteURLs\n  @depends(on: [\"ExportExecute\", \"GenerateRequestInputs\"])\n  @skip(if: $executeQuery)\n{\n  _sendHTTPRequests(inputs: $requestInputs) {\n    statusCode\n    contentType\n    body\n      @remove\n    bodyJSON: _strDecodeJSONObject(string: $__body)\n  }\n}\n\nquery ExecuteQuery(\n  $offset: Int\n)\n  @depends(on: \"ExportExecute\")\n  @include(if: $executeQuery)\n{\n  message: _sprintf(string: \"Executed the query with $offset '%s'\", values: [$offset])\n}\n\nquery ExecuteAll\n  @depends(on: [\"ExecuteURLs\", \"ExecuteQuery\"])\n{\n}",
            "variables": {
              "offset": 0
            }
          }
        }
      },
      {
        "url": "https:\/\/gato-graphql-pro.lndo.site\/wp-admin\/edit.php?page=gato_graphql&action=execute_query",
        "method": "POST",
        "options": {
          "headers": [
            {
              "name": "authorization",
              "value": ""
            },
            {
              "name": "host",
              "value": "gato-graphql-pro.lndo.site"
            },
            {
              "name": "user-agent",
              "value": "GuzzleHttp\/7"
            },
            {
              "name": "content-length",
              "value": "3364"
            },
            {
              "name": "accept",
              "value": "application\/json"
            },
            {
              "name": "content-type",
              "value": "application\/json"
            },
            {
              "name": "cookie",
              "value": "wordpress_test_cookie=WP%20Cookie%20check; wordpress_sec_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wordpress_logged_in_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wp-settings-time-1={REPLACED_FOR_TESTING}"
            },
            {
              "name": "x-forwarded-for",
              "value": "172.19.0.1"
            },
            {
              "name": "x-forwarded-host",
              "value": "gato-graphql-pro.lndo.site"
            },
            {
              "name": "x-forwarded-port",
              "value": "443"
            },
            {
              "name": "x-forwarded-proto",
              "value": "https"
            },
            {
              "name": "x-lando",
              "value": "on"
            },
            {
              "name": "x-real-ip",
              "value": "172.19.0.1"
            },
            {
              "name": "accept-encoding",
              "value": "gzip"
            }
          ],
          "json": {
            "operationName": "ExecuteAll",
            "query": "query ExportExecute(\n  $offset: Int\n) {\n  executeQuery: _notNull(value: $offset)\n    @export(as: \"executeQuery\")\n}\n\nquery CalculateVars($limit: Int! = 10)\n  @depends(on: \"ExportExecute\")\n  @skip(if: $executeQuery)\n{\n  # Calculate the number of HTTP requests to be sent\n  commentCount\n  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)\n  numberExecutions: _floatCeil(number: $__fractionalNumberExecutions)\n  \n  # Generate a list of the offset\n  arrayOffsets: _arrayPad(array: [], length: $__numberExecutions, value: null)\n    @underEachArrayItem(\n      passIndexOnwardsAs: \"position\"\n    )\n      @applyField(\n        name: \"_intMultiply\"\n        arguments: {\n          multiply: $position\n          with: $limit\n        }\n        setResultInResponse: true\n      )\n    @export(as: \"offsets\")\n\n  # Vars needed to generate a list of the HTTP Request inputs\n  url: _httpRequestFullURL\n    @export(as: \"url\")\n  method: _httpRequestMethod\n    @export(as: \"method\")\n  headers: _httpRequestHeaders\n  headersInputList: _objectConvertToNameValueEntryList(\n    object: $__headers\n  )\n    @export(as: \"headersInputList\")\n  body: _httpRequestBody\n  bodyJSONObject: _strDecodeJSONObject(string: $__body)\n    @export(as: \"bodyJSONObject\")\n  bodyHasVariables: _propertyExistsInJSONObject(\n    object: $__bodyJSONObject,\n    by: { key: \"variables\" }\n  )\n    @export(as: \"bodyHasVariables\")\n}\n\nquery GenerateVars\n  @depends(on: [\"ExportExecute\", \"CalculateVars\"])\n  @skip(if: $executeQuery)\n{\n  bodyJSON: _echo(value: $bodyJSONObject)\n    @unless(condition: $bodyHasVariables)\n      @objectAddEntry(\n        object: $bodyJSONObject,\n        key: \"variables\"\n        value: {}\n      )\n    @export(as: \"bodyJSON\")\n}\n\nquery GenerateRequestInputs\n  @depends(on: [\"ExportExecute\", \"GenerateVars\"])\n  @skip(if: $executeQuery)\n{\n  # Generate a list of the HTTP Request inputs (without the offset)\n  requestInputs: _echo(value: $offsets)\n    @underEachArrayItem(\n      passValueOnwardsAs: \"requestOffset\"\n      affectDirectivesUnderPos: [1, 2]\n    )\n      @applyField(\n        name: \"_objectAddEntry\",\n        arguments: {\n          object: $bodyJSON\n          underPath: \"variables\"\n          key: \"offset\"\n          value: $requestOffset\n        },\n        passOnwardsAs: \"itemJSON\"\n      )\n      @applyField(\n        name: \"_echo\",\n        arguments: {\n          value: {\n            url: $url\n            method: $method\n            options: {\n              headers: $headersInputList\n              json: $itemJSON\n            }\n          }\n        },\n        setResultInResponse: true\n      )\n    @export(as: \"requestInputs\")\n}\n\nquery ExecuteURLs\n  @depends(on: [\"ExportExecute\", \"GenerateRequestInputs\"])\n  @skip(if: $executeQuery)\n{\n  _sendHTTPRequests(inputs: $requestInputs) {\n    statusCode\n    contentType\n    body\n      @remove\n    bodyJSON: _strDecodeJSONObject(string: $__body)\n  }\n}\n\nquery ExecuteQuery(\n  $offset: Int\n)\n  @depends(on: \"ExportExecute\")\n  @include(if: $executeQuery)\n{\n  message: _sprintf(string: \"Executed the query with $offset '%s'\", values: [$offset])\n}\n\nquery ExecuteAll\n  @depends(on: [\"ExecuteURLs\", \"ExecuteQuery\"])\n{\n}",
            "variables": {
              "offset": 10
            }
          }
        }
      },
      {
        "url": "https:\/\/gato-graphql-pro.lndo.site\/wp-admin\/edit.php?page=gato_graphql&action=execute_query",
        "method": "POST",
        "options": {
          "headers": [
            {
              "name": "authorization",
              "value": ""
            },
            {
              "name": "host",
              "value": "gato-graphql-pro.lndo.site"
            },
            {
              "name": "user-agent",
              "value": "GuzzleHttp\/7"
            },
            {
              "name": "content-length",
              "value": "3364"
            },
            {
              "name": "accept",
              "value": "application\/json"
            },
            {
              "name": "content-type",
              "value": "application\/json"
            },
            {
              "name": "cookie",
              "value": "wordpress_test_cookie=WP%20Cookie%20check; wordpress_sec_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wordpress_logged_in_{REPLACED_FOR_TESTING}={REPLACED_FOR_TESTING}; wp-settings-time-1={REPLACED_FOR_TESTING}"
            },
            {
              "name": "x-forwarded-for",
              "value": "172.19.0.1"
            },
            {
              "name": "x-forwarded-host",
              "value": "gato-graphql-pro.lndo.site"
            },
            {
              "name": "x-forwarded-port",
              "value": "443"
            },
            {
              "name": "x-forwarded-proto",
              "value": "https"
            },
            {
              "name": "x-lando",
              "value": "on"
            },
            {
              "name": "x-real-ip",
              "value": "172.19.0.1"
            },
            {
              "name": "accept-encoding",
              "value": "gzip"
            }
          ],
          "json": {
            "operationName": "ExecuteAll",
            "query": "query ExportExecute(\n  $offset: Int\n) {\n  executeQuery: _notNull(value: $offset)\n    @export(as: \"executeQuery\")\n}\n\nquery CalculateVars($limit: Int! = 10)\n  @depends(on: \"ExportExecute\")\n  @skip(if: $executeQuery)\n{\n  # Calculate the number of HTTP requests to be sent\n  commentCount\n  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)\n  numberExecutions: _floatCeil(number: $__fractionalNumberExecutions)\n  \n  # Generate a list of the offset\n  arrayOffsets: _arrayPad(array: [], length: $__numberExecutions, value: null)\n    @underEachArrayItem(\n      passIndexOnwardsAs: \"position\"\n    )\n      @applyField(\n        name: \"_intMultiply\"\n        arguments: {\n          multiply: $position\n          with: $limit\n        }\n        setResultInResponse: true\n      )\n    @export(as: \"offsets\")\n\n  # Vars needed to generate a list of the HTTP Request inputs\n  url: _httpRequestFullURL\n    @export(as: \"url\")\n  method: _httpRequestMethod\n    @export(as: \"method\")\n  headers: _httpRequestHeaders\n  headersInputList: _objectConvertToNameValueEntryList(\n    object: $__headers\n  )\n    @export(as: \"headersInputList\")\n  body: _httpRequestBody\n  bodyJSONObject: _strDecodeJSONObject(string: $__body)\n    @export(as: \"bodyJSONObject\")\n  bodyHasVariables: _propertyExistsInJSONObject(\n    object: $__bodyJSONObject,\n    by: { key: \"variables\" }\n  )\n    @export(as: \"bodyHasVariables\")\n}\n\nquery GenerateVars\n  @depends(on: [\"ExportExecute\", \"CalculateVars\"])\n  @skip(if: $executeQuery)\n{\n  bodyJSON: _echo(value: $bodyJSONObject)\n    @unless(condition: $bodyHasVariables)\n      @objectAddEntry(\n        object: $bodyJSONObject,\n        key: \"variables\"\n        value: {}\n      )\n    @export(as: \"bodyJSON\")\n}\n\nquery GenerateRequestInputs\n  @depends(on: [\"ExportExecute\", \"GenerateVars\"])\n  @skip(if: $executeQuery)\n{\n  # Generate a list of the HTTP Request inputs (without the offset)\n  requestInputs: _echo(value: $offsets)\n    @underEachArrayItem(\n      passValueOnwardsAs: \"requestOffset\"\n      affectDirectivesUnderPos: [1, 2]\n    )\n      @applyField(\n        name: \"_objectAddEntry\",\n        arguments: {\n          object: $bodyJSON\n          underPath: \"variables\"\n          key: \"offset\"\n          value: $requestOffset\n        },\n        passOnwardsAs: \"itemJSON\"\n      )\n      @applyField(\n        name: \"_echo\",\n        arguments: {\n          value: {\n            url: $url\n            method: $method\n            options: {\n              headers: $headersInputList\n              json: $itemJSON\n            }\n          }\n        },\n        setResultInResponse: true\n      )\n    @export(as: \"requestInputs\")\n}\n\nquery ExecuteURLs\n  @depends(on: [\"ExportExecute\", \"GenerateRequestInputs\"])\n  @skip(if: $executeQuery)\n{\n  _sendHTTPRequests(inputs: $requestInputs) {\n    statusCode\n    contentType\n    body\n      @remove\n    bodyJSON: _strDecodeJSONObject(string: $__body)\n  }\n}\n\nquery ExecuteQuery(\n  $offset: Int\n)\n  @depends(on: \"ExportExecute\")\n  @include(if: $executeQuery)\n{\n  message: _sprintf(string: \"Executed the query with $offset '%s'\", values: [$offset])\n}\n\nquery ExecuteAll\n  @depends(on: [\"ExecuteURLs\", \"ExecuteQuery\"])\n{\n}",
            "variables": {
              "offset": 20
            }
          }
        }
      }
    ],
    "_sendHTTPRequests": [
      {
        "statusCode": 200,
        "contentType": "application\/json",
        "bodyJSON": {
          "data": {
            "executeQuery": true,
            "message": "Executed the query with $offset '0'"
          }
        }
      },
      {
        "statusCode": 200,
        "contentType": "application\/json",
        "bodyJSON": {
          "data": {
            "executeQuery": true,
            "message": "Executed the query with $offset '10'"
          }
        }
      },
      {
        "statusCode": 200,
        "contentType": "application\/json",
        "bodyJSON": {
          "data": {
            "executeQuery": true,
            "message": "Executed the query with $offset '20'"
          }
        }
      }
    ]
  }
}
```

Check timeout/async combinations in recursive-and-iterative-query-with-http-request.gql



Develop also the case of using field `_sendMultipleHTTPRequests` with both async true and false, and only then explain the recursive solution from below.

Recursive solution:

```graphql
mutation UpdatePosts(
  $limit: Int! = 10,
  $offset: Int! = 0,
  $authorID: ID!
) {
  posts(pagination: {
    limit: $limit,
    offset: $offset,
  })
    @export(as: "postIDs")
  {
    update(input: {
      authorID: $authorID,
    }) {
      status
    }
  }
}

query CalculateReachedEnd
  @depends(on: "UpdatePosts")
{
  reachedEnd: _empty(value: $postIDs) @export(as: "reachedEnd")
}

mutation TriggerRecursion
  @depends(on: "CalculateReachedEnd")
  @skip(if: $reachedEnd)
{
  nextOffset: _intAdd(add: $limit, to: $offset)
  # Only available in Persisted Queries.
  # Mode will always be asynchronous.
  # Executed in endpoint will throw an error.
  _recursivelyExecutePersistedQuery(
    # This value is implicit
    # persistedQueryID: null,
    overridingVariables: {
      offset: $__nextOffset
    }
  )
}
```

Advanced:

(As there is no `_sendEmail` mutation yet, comment out this code, and re-add it once the mutation is supported)

```graphql
mutation UpdatePosts(
  $limit: Int! = 10,
  $offset: Int! = 0,
  $authorID: ID!
) {
  posts(pagination: {
    limit: $limit,
    offset: $offset,
  })
    @export(as: "postIDs")
  {
    update(input: {
      authorID: $authorID,
    }) {
      status
    }
  }
}

query CalculateReachedEnd
  @depends(on: "UpdatePosts")
{
  reachedEnd: _empty(value: $postIDs) @export(as: "reachedEnd")
}

mutation TriggerRecursion
  @depends(on: "CalculateReachedEnd")
  @skip(if: $reachedEnd)
{
  nextOffset: _intAdd(add: $limit, to: $offset)
  # Only available in Persisted Queries.
  # Mode will always be asynchronous.
  # Executed in endpoint will throw an error.
  _recursivelyExecutePersistedQuery(
    # This value is implicit
    # persistedQueryID: null,
    overridingVariables: {
      offset: $__nextOffset
    }
  )
}

mutation SendAdminEmail
  @depends(on: "CalculateReachedEnd")
  @include(if: $reachedEnd)
{
  _sendEmail(body: "done")
}

query Execute
  @depends(on: ["TriggerRecursion", "SendAdminEmail"])
{
  _echo(value: "Success!")
}
```

Iterative solution:

```graphql
query CalculateVars($limit:Int! = 10) {
  commentCount
  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)
    @remove
  numberExecutions: _floatCeil(number: $__fractionalNumberExecutions)
    @export(as:"numberExecutions")
  placeholderArray: _arrayPad(array: [], length: $__numberExecutions, value: "")
    @remove
  arrayPositions: _arrayKeys(array: $__placeholderArray)
    @remove
  arrayOffsets: _echo(value: $__arrayPositions)
    @underEachArrayItem(passValueOnwardsAs: "position")
      @intMultiply(with:$limit)
    @export(as:"offsets")
    @remove
}

query CalculateURLs($limit:Int! = 10)
  @depends(on:"CalculateVars")
{
  urls: _echo(value: $offsets)
    @underEachArrayItem(passValueOnwardsAs: "offset")
      @applyField(
        name: "_sprintf",
        arguments: {
          string: "https://gato-graphql-pro.lndo.site/wp-admin/admin.php?page=gato_graphql&query={posts(pagination:{limit:%s,offset:%s}){id}}"
          values: [$limit, $offset]
        },
        setResultInResponse:true
      )
    @export(as: "urls")
}

query CalculateURLInputs
  @depends(on:"CalculateURLs")
{
  urlInputs: _echo(value: $urls)
    @underEachArrayItem(passValueOnwardsAs: "url")
      @applyField(
        name: "_objectAddEntry",
        arguments: {
          object: {}
          key: "url"
          value: $url
        },
        setResultInResponse:true
      )
    @export(as: "urlInputs")
}

query ExecuteURLs
  @depends(on:"CalculateURLInputs")
{
  _sendHTTPRequests(inputs: $urlInputs) {
    statusCode
    body
  }
}
```

Use "timeout" to execute query, but don't wait for the response
  Indicate it will produce an error though

Use "delay" for sleep!!!

```graphql
{
  _sendHTTPRequests(
    async:false,
    inputs:[
      {
        url: "https://newapi.getpop.org/wp-json/wp/v2/users/1/?_fields=id,name,url"
        options:{
          # 2 seconds (2000 milliseconds)
          delay: 2000
        }
      },
      {
        url: "https://newapi.getpop.org/wp-json/wp/v2/users/2/?_fields=id,name,url"
        options:{
          # 2 seconds (2000 milliseconds)
          delay: 2000
        }
      }
    ]
  ) {
    statusCode
    body
  }
}
```

Also handle errors in any of the iterations. Eg: using @fail
  Or keep for another guide!?






Maybe use part of this code:

In combination with extensions **HTTP Request via Schema** and [**Field to Input**](https://gatographql.com/extensions/field-to-input/), we can retrieve the currently-requested URL when executing a GraphQL custom endpoint or persisted query, add extra parameters, and send another HTTP request to the new URL.

For instance, in this query, we retrieve the IDs of the users in the website and execute a new GraphQL query passing their ID as parameter:

```graphql
{
  users {
    userID: id
    url: _httpRequestFullURL
    method: _httpRequestMethod
    headers: _httpRequestHeaders
    body: _httpRequestBody
    newURL: _urlAddParams(
      url: $__url,
      params: {
        userID: $__userID
      }
    )
    _sendHTTPRequest(
      input: {
        url: $__newURL,
        method: $__method,
        options: {
          headers: $__headers
          body: $__body
        }
      }
    ) {
      statusCode
      contentType
      body
    }
  }
}
```