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

This recipe demonstrates an API gateway that retrieves the latest artifacts from the GitHub Actions API, and extracts their URL to be downloaded, avoiding the need for the client to be signed in to GitHub.

## GraphQL-powered API gateway to access GitHub Action artifacts

This GraphQL query first retrieves the latest artifacts from GitHub Actions, and extracts the proxy URL to access each of them (because only authenticated users can access the artifacts, these URLs do not point to the actual artifact yet).

It then accesses each of these proxy URLs (which has the artifact uploaded to a public location for a short period of time) and extracts the actual URL from the HTTP response's `Location` header.

Finally it prints all actual URLs, allowing non-authenticated users to download GitHub artifacts.

```graphql
query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3) {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
    @export(as: "githubAccessToken")
    @remove

  githubArtifactsEndpoint: _sprintf(
    string: "https://api.github.com/repos/leoloso/PoP/actions/artifacts?per_page=%s",
    values: [$numberArtifacts]
  )
    @remove

  # Retrieve Artifact data from GitHub Actions API
  gitHubArtifactData: _sendJSONObjectItemHTTPRequest(
    input: {
      url: $__githubArtifactsEndpoint,
      options: {
        auth: {
          password: $__githubAccessToken
        },
        headers: [
          {
            name: "Accept",
            value: "application/vnd.github+json"
          }
        ]
      }
    }
  )
    @remove
  
  # Extract the URL from within each "artifacts" item
  gitHubProxyArtifactDownloadURLs: _objectProperty(
    object: $__gitHubArtifactData,
    by: {
      key: "artifacts"
    }
  )
    @underEachArrayItem(passValueOnwardsAs: "artifactItem")
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $artifactItem,
          by: {
            key: "archive_download_url"
          }
        },
        setResultInResponse: true
      )
    @export(as: "gitHubProxyArtifactDownloadURLs")
}

query CreateHTTPRequestInputs
  @depends(on: "RetrieveProxyArtifactDownloadURLs")
{
  httpRequestInputs: _echo(value: $gitHubProxyArtifactDownloadURLs)
    @underEachArrayItem(
      passValueOnwardsAs: "url"
    )
      @applyField(
        name: "_objectAddEntry",
        arguments: {
          object: {
            options: {
              auth: {
                password: $githubAccessToken
              },
              headers: {
                name: "Accept",
                value: "application/vnd.github+json"
              },
              allowRedirects: null
            }
          },
          key: "url",
          value: $url
        },
        setResultInResponse: true
      )
    @export(as: "httpRequestInputs")
    @remove
}

query RetrieveActualArtifactDownloadURLs
  @depends(on: "CreateHTTPRequestInputs")
{
  _sendHTTPRequests(
    inputs: $httpRequestInputs
  ) {
    artifactDownloadURL: header(name: "Location")
      @export(as: "artifactDownloadURLs")
  }
}

query PrintArtifactDownloadURLsAsList
  @depends(on: "RetrieveActualArtifactDownloadURLs")
{
  artifactDownloadURLs: _echo(value: $artifactDownloadURLs)
}
```

## Step by step: creating the GraphQL query

Below is the detailed analysis of how the query works.

### ...




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