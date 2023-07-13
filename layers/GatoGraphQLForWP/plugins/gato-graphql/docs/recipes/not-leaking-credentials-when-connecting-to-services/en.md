# Not leaking credentials when connecting to services

We often need to provide credentials when connecting to external services. For instance, GitHub's GraphQL API requires providing an access token `{ GITHUB_ACCESS_TOKEN }`:

```graphql
query {
  _sendGraphQLHTTPRequest(input:{
    endpoint: "https://api.github.com/graphql",
    query: """    
      query {
        repositoryOwner(login: "leoloso") {
          repositories(first: 3) {
            nodes {
              id
              name
              description
            }
          }
        }
      }
    """,
    options: {
      auth: {
        password: "{ GITHUB_ACCESS_TOKEN }"
      }
    }
  })
}
```

Providing credentials should never be hardcoded in code, hence they should not be part of the GraphQL query.

We could provide it as a variable

Passing these `variables`:

```json
{
  "authorizationToken": "{ GITHUB ACCESS TOKEN }",
  "login": "leoloso"
}
```



```graphql
query RetrieveProxyArtifactDownloadURLs
{
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
    # This directive will remove this entry from the output
    @remove

  # Create the authorization header to send to GitHub
  authorizationHeader: _sprintf(
    string: "Bearer %s",
    # "Field to Input" feature to access value from the field above
    values: [$__githubAccessToken]
  )
    # Do not print in output
    @remove

  # Create the authorization header to send to GitHub
  githubRequestHeaders: _echo(value: [
    {
      name: "Accept",
      value: "application/vnd.github+json"
    },
    {
      name: "Authorization",
      value: $__authorizationHeader
    }
  ])
    # Do not print in output
    @remove
    @export(as: "githubRequestHeaders")
  
  # Use the field from "Send HTTP Request Fields" to connect to GitHub
  gitHubArtifactData: _sendJSONObjectItemHTTPRequest(
    input: {
      url: "https://api.github.com/repos/leoloso/PoP/actions/artifacts?per_page=2",
      options: {
        headers: $__githubRequestHeaders
      }
    }
  )
    # Do not print in output
    @remove
  
  # Finally just extract the URL from within each "artifacts" item
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
    @underEachArrayItem(passValueOnwardsAs: "url")
      @applyField(
        name: "_objectAddEntry",
        arguments: {
          object: {
            options: {
              headers: $githubRequestHeaders,
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

query PrintSpaceSeparatedArtifactDownloadURLs
  @depends(on: "RetrieveActualArtifactDownloadURLs")
{
  spaceSeparatedArtifactDownloadURLs: _arrayJoin(
    array: $artifactDownloadURLs
    separator: " "
  )
}
```
