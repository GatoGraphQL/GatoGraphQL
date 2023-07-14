# Creating an API gateway

An API gateway is a component on our application that provides a centralized handling of API communication between the client and the multiple required services.

The API gateway can be implemented via GraphQL Persisted Queries stored in the server and invoked by the client, which interact with one or more backend services, gathering the results and delivering them back to the client in a single response.

Some benefits of using GraphQL Persisted Queries to provide an API gateway are:

- Clients do not need to handle connections to backend services, thus simplifying their logic
- Access to backend services is centralized
- No credentials are exposed on the client
- Credentials can be provided to the server via environment variables
- The response from the service can be transformed into what the client expects
- If some backend service is upgraded, the Persisted Query could be adapted without producing breaking changes in the client
- The server can store logs of access to the backend services, and extract metrics to enhance analytics

API gateways allow to simplify the logi



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