# Pinging external services

We can ping external services about new resources added to our website, passing along data both stored in the website and/or provided via parameters or headers.

In this query, we retrieve the IDs of the comments added in the last 24 hs and, for each, send a ping to some external service, passing their ID as a parameter in the URL, and forwarding some headers from the current HTTP request:

```graphql
{
  timeNow: _time  
  time24HsAgo: _intSubstract(substract: 86400, from: $__timeNow)
  date24HsAgo: _date(format: "Y-m-d\\TH:i:sO", timestamp: $__time24HsAgo)

  comments(filter: { dateQuery: { after: $__date24HsAgo } } ) {
    commentID: id
    url: _urlAddParams(
      url: "https://somewebsite.com/ping-new-comment",
      params: {
        commentID: $__commentID
      }
    )
    headers: _httpRequestHeaders
      @remove
    requiredHeaders: _objectKeepProperties(
      object: $__headers,
      keys: ["user-agent", "origin"]
    )
      @remove
    headerNameValueEntryList: _objectConvertToNameValueEntryList(
      object: $__requiredHeaders
    )
    _sendHTTPRequest(input: {
      url: $__url
      method: GET
      options: {
        headers: $__headerNameValueEntryList
      }
    }) {
      statusCode
      contentType
      body
    }
  }
}
```

If the external service can receive the data for multiple resources, we can collect all of them, and then send a single ping:

```graphql
query ExportData {
  timeNow: _time  
  time24HsAgo: _intSubstract(substract: 86400, from: $__timeNow)
  date24HsAgo: _date(format: "Y-m-d\\TH:i:sO", timestamp: $__time24HsAgo)

  comments(filter: { dateQuery: { after: $__date24HsAgo } } )
    @export(as: "commentIDs")
  {
    id
  }

  hasComments: _notEmpty(value: $__comments)
    @export(as: "hasComments")
    @remove
}

query SendPing
  @depends(on: "ExportData")
  @include(if: $hasComments)
{
  url: _urlAddParams(
    url: "https://somewebsite.com/ping-new-comments",
    params: {
      commentIDs: $commentIDs
    }
  )
  headers: _httpRequestHeaders
    @remove
  requiredHeaders: _objectKeepProperties(
    object: $__headers,
    keys: ["user-agent", "origin"]
  )
    @remove
  headerNameValueEntryList: _objectConvertToNameValueEntryList(
    object: $__requiredHeaders
  )
  _sendHTTPRequest(input: {
    url: $__url
    method: GET
    options: {
      headers: $__headerNameValueEntryList
    }
  }) {
    statusCode
    contentType
    body
  }
}
```
