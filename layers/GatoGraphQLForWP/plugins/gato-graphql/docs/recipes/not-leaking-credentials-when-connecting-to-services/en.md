# Not leaking credentials when connecting to services

We often need to provide credentials when connecting to external services.

For instance, GitHub's API requires an access token to execute REST endpoints where data is mutated or private:

```graphql
query {
  credentialsAreNotRequired: _sendJSONObjectItemHTTPRequest(input:{
    url: "https://api.github.com/repos/leoloso/PoP"
  })

  credentialsAreRequired: _sendJSONObjectItemHTTPRequest(input:{
    url: "https://api.github.com/repos/leoloso/PoP",
    method: PATCH,
    options: {
      auth: {
        password: $githubAccessToken
      },
      body: "{\"has_wiki\":false}"
    }
  })
}
```

We must never expose our credentials, so we need to take precautions:

**In the GraphQL query:** Credentials must never be embedded in source code, as these will be in plain text, creating a security hazard.

**In the GraphQL response:** The field connecting to the API might produce an error (for instance, if the target webserver is down), in which case the GraphQL server will add an error message under entry `errors`; this message could print again the name of the field that failed and its arguments, thus printing the credentials in the response.

**In the server logs:** If credentials are provided via a variable, and these are provided via an URL param, then these will be logged somewhere.

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
