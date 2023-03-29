# Bulk editing content

Nested mutations!

Consider use case for plugin:
    https://poststatus.slack.com/archives/CHNM7Q7T8/p1672258772145609
    > Not sure the best channel to ask this, but I'm just going to throw it out here... I find that for a lot of clients I'm working with large sets of data (10,000+ product variations for 1 product, or 13,000+ media files) ... inevitably the clients want to be able to bulk edit lots of things at once - like tag 2000 media files with the same tag. Historically I've been writing cli commands that will export/import csv files to address the bulk editing. But that still very much leaves them dependent on my team to make that level of a change. We also use WP All Import/Export and have been trying WP Sheet Editor - but they just really aren't exactly what we are needing either from a client usability standpoint. Does anyone have any other suggestions for this type of thing?

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
    @forEach(passOnwardsAs: "position")
      @intMultiply(with:$limit)
    @export(as:"offsets")
    @remove
}

query CalculateURLs($limit:Int! = 10)
  @depends(on:"CalculateVars")
{
  urls: _echo(value: $offsets)
    @forEach(passOnwardsAs: "offset")
      @applyField(
        name: "_sprintf",
        arguments: {
          string: "https://graphql-api-pro.lndo.site/wp-admin/admin.php?page=graphql_api&query={posts(pagination:{limit:%s,offset:%s}){id}}"
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
    @forEach(passOnwardsAs: "url")
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

Also handle errors in any of the iterations. Eg: using @fail
  Or keep for another guide!?

Another possibility: via bash.

Paginating content:

```bash
# Get the number of comments in the site
GRAPHQL_RESPONSE=$(curl --insecure \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "{\n  commentCount\n}"}' \
  https://graphql-api.lndo.site/graphql/website/)

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
for PAGINATION_NUMBER in $(seq 0 $(($PAGINATION_COUNT - 1))); do sleep 1 && echo "\n\nPagination number: $PAGINATION_NUMBER\n" && curl --insecure -X POST -H "Content-Type: application/json" -d "{\"query\": \"{ comments(pagination: { limit: $ENTRIES_TO_PROCESS, offset: $(($PAGINATION_NUMBER * $ENTRIES_TO_PROCESS)) }) { id date content } }\"}" https://graphql-api.lndo.site/graphql/website/ ; done
```
