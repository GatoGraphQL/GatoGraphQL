# Lesson 28: Updating large sets of data

Sometimes we need to update thousands of resources in a single action, as expressed in the following comment (posted on a community group about WordPress):

> I find that for a lot of clients I'm working with large sets of data (10,000+ product variations for 1 product, or 13,000+ media files) ... inevitably the clients want to be able to bulk edit lots of things at once - like tag 2000 media files with the same tag.

In this tutorial lesson we will explore ways to tackle this task.

## Nested Mutations

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

Thanks to [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/), we can retrieve and update thousands of resources from the DB via a single GraphQL query:

```graphql
mutation ReplaceOldWithNewDomainInPosts {
  posts(pagination: { limit: 3000 }) {
    id
    rawContent
    adaptedRawContent: _strReplace(
      search: "https://my-old-domain.com"
      replaceWith: "https://my-new-domain.com"
      in: $__rawContent
    )
    update(input: {
      contentAs: { html: $__adaptedRawContent }
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

Because the solution above involves bash scripting, it must be executed via the CLI (or some admin panel or tool), limiting its use.

We can replicate the same logic into the GraphQL query itself, thus allowing us to execute it already within WordPress (even already storing it as a GraphQL Persisted Query).

The GraphQL query below executes itself recursively. When first invoked, it:

- Divides the total number of resources to update into segments (calculated using the provided `$limit` variable)
- Executes itself via a new HTTP request for each of the segments (passing over the corresponding `$offset` as a variable), thus updating only a subset of all resources at a given time

The GraphQL query is recursive by having the HTTP requests point to the same URL as the current one (plus adding the `$offset` variable for that segment), for which we retrieve the URL (and also the body, method and headers) from the current HTTP request (via the [**HTTP Request via Schema**](https://gatographql.com/extensions/schema-functions/) extension).

The `$async` argument passed to `_sendHTTPRequests` has been set to `false`, so that the HTTP requests will be executed one after the other. In addition, the optional variable `$delay` allows to indicate how many milliseconds to delay before sending each request.

Once all resources have been updated, the execution of the GraphQL query reaches the end and terminates:

```graphql
# When first invoked, we do not pass variable `$offset`
# Then `$offset` is `null`, and dynamic variable `$executeQuery` will be `true`
query ExportExecute(
  $offset: Int
) {
  executeQuery: _notNull(value: $offset)
    @export(as: "executeQuery")
    @remove # Comment this directive to visualize output during development
}

# Only calculate the segments on the first invocation of the GraphQL query
query CalculateVars($limit: Int! = 10)
  @depends(on: "ExportExecute")
  @skip(if: $executeQuery)
{
  # Calculate the number of HTTP requests to be sent
  commentCount
  fractionalNumberExecutions: _floatDivide(number: $__commentCount, by: $limit)
    @remove # Comment this directive to visualize output during development
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

  # Vars needed to generate a list of the HTTP Request inputs,
  # with many of them retrieved from the current HTTP request data
  url: _httpRequestFullURL
    @export(as: "url")
    @remove # Comment this directive to visualize output during development
  method: _httpRequestMethod
    @export(as: "method")
    @remove # Comment this directive to visualize output during development
  headers: _httpRequestHeaders
    @remove # Comment this directive to visualize output during development
  headersInputList: _objectConvertToNameValueEntryList(
    object: $__headers
  )
    @export(as: "headersInputList")
    @remove # Comment this directive to visualize output during development
  body: _httpRequestBody
    @remove # Comment this directive to visualize output during development
  bodyJSONObject: _strDecodeJSONObject(string: $__body)
    @export(as: "bodyJSONObject")
    @remove # Comment this directive to visualize output during development
  bodyHasVariables: _propertyIsSetInJSONObject(
    object: $__bodyJSONObject,
    by: { key: "variables" }
  )
    @export(as: "bodyHasVariables")
    @remove # Comment this directive to visualize output during development
}

query GenerateVars
  @depends(on: ["ExportExecute", "CalculateVars"])
  @skip(if: $executeQuery)
{
  bodyJSON: _echo(value: $bodyJSONObject)
    @unless(condition: $bodyHasVariables)
      @objectAddEntry(
        key: "variables"
        value: {}
      )
    @export(as: "bodyJSON")
    @remove # Comment this directive to visualize output during development
}

# Generate all the HTTPRequestInput objects to send each of the HTTP requests
query GenerateRequestInputs(
  $timeout: Float,
  $delay: Int
)
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
              timeout: $timeout
              delay: $delay
            }
          }
        },
        setResultInResponse: true
      )
    @export(as: "requestInputs")
    @remove # Comment this directive to visualize output during development
}

# Execute all the generated URLs, either asynchronously or not
query ExecuteURLs
  @depends(on: ["ExportExecute", "GenerateRequestInputs"])
  @skip(if: $executeQuery)
{
  _sendHTTPRequests(
    async: false
    inputs: $requestInputs
  ) {
    statusCode
    contentType
    body
      @remove
    bodyJSON: _strDecodeJSONObject(string: $__body)
  }
}

# This is the actual execution of the query.
# In this case, it simply prints the time when it was executed,
# the provided query variables, and the comment IDs for that segment
query ExecuteQuery(
  $offset: Int
  $limit: Int! = 10
)
  @depends(on: "ExportExecute")
  @include(if: $executeQuery)
{
  executionTime: _httpRequestRequestTime
  queryVariables: _sprintf(string: "[$limit: %s, $offset: %s]", values: [$limit, $offset])
  comments(
    pagination: { limit: $limit, offset: $offset }
    sort: { order: ASC, by: ID }
  ) {
    id
  }
}

query ExecuteAll
  @depends(on: ["ExecuteURLs", "ExecuteQuery"])
{
  id
    @remove
}
```

The response is:

```json
{
  "data": {
    "commentCount": 23,
    "numberExecutions": 3,
    "arrayOffsets": [
      0,
      10,
      20
    ],
    "_sendHTTPRequests": [
      {
        "statusCode": 200,
        "contentType": "application/json",
        "bodyJSON": {
          "data": {
            "executionTime": 1689814467,
            "queryVariables": "[$limit: 10, $offset: 0]",
            "comments": [
              {
                "id": 2
              },
              {
                "id": 3
              },
              {
                "id": 4
              },
              {
                "id": 5
              },
              {
                "id": 6
              },
              {
                "id": 7
              },
              {
                "id": 8
              },
              {
                "id": 9
              },
              {
                "id": 10
              },
              {
                "id": 11
              }
            ]
          }
        }
      },
      {
        "statusCode": 200,
        "contentType": "application/json",
        "bodyJSON": {
          "data": {
            "executionTime": 1689814468,
            "queryVariables": "[$limit: 10, $offset: 10]",
            "comments": [
              {
                "id": 12
              },
              {
                "id": 13
              },
              {
                "id": 16
              },
              {
                "id": 17
              },
              {
                "id": 18
              },
              {
                "id": 19
              },
              {
                "id": 20
              },
              {
                "id": 21
              },
              {
                "id": 22
              },
              {
                "id": 23
              }
            ]
          }
        }
      },
      {
        "statusCode": 200,
        "contentType": "application/json",
        "bodyJSON": {
          "data": {
            "executionTime": 1689814470,
            "queryVariables": "[$limit: 10, $offset: 20]",
            "comments": [
              {
                "id": 24
              },
              {
                "id": 25
              },
              {
                "id": 26
              }
            ]
          }
        }
      }
    ]
  }
}
```
