# Not leaking credentials when connecting to services

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
