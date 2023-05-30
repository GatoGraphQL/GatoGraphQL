# Creating an API gateway

_httpRequestHeaders is super powerful!
  Can for instance implement API gateway
  Passing an auth header for another service
  Eg:
    Receive header "GitHub-Bearer-Token", forward it to "Bearer-Token"

Talk about validating the different inputs, provided via headers:

```graphql
query One {
  githubBearerToken: _httpRequestHeader(name: "Github-Bearer-Token")
    @export(as: "githubBearerToken")
  slackBearerToken: _httpRequestHeader(name: "Slack-Bearer-Token")
    @export(as: "slackBearerToken")
  hasAllBearerTokens: _and(values: [$__githubBearerToken, $__slackBearerToken])
    @export(as: "hasAllBearerTokens")
}

query Two
  @depends(on: "One")
  @include(if: $hasAllBearerTokens)
{
  # ...
}

query Three
  @depends(on: "One")
  @skip(if: $hasAllBearerTokens)
{
  _fail(...)
}

query Four
  @depends(on: ["Two", "Three"])
{
  
}
```