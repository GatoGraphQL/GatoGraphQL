# Creating an API gateway

HTTPRequest.headers is super powerful!
  Can for instance implement API gateway
  Passing an auth header for another service
  Eg:
    Receive header "GitHub-Bearer-Token", forward it to "Bearer-Token"

Talk about validating the different inputs, provided via headers:

```graphql
query One {
  _httpRequest: {
    githubBearerToken: _header(name: "Github-Bearer-Token")
      @export(as: "githubBearerToken")
    slackBearerToken: _header(name: "Slack-Bearer-Token")
      @export(as: "slackBearerToken")
  }  
}

query Two {
  hasAllBearerTokens: _and(values: [$githubBearerToken, $slackBearerToken])
    @export(as: "hasAllBearerTokens")
}

query Three
  @include(if: $hasAllBearerTokens)
{
  # ...
}

query Four
  @skip(if: $hasAllBearerTokens)
{
  _fail(...)
}

query Five
  @depends(on: ["Three", "Four"])
{
  
}
```