# Creating an API gateway

An API gateway is a component on our application that provides a centralized handling of API communication between the client and the multiple required services.

The API gateway can be implemented via GraphQL Persisted Queries stored in the server and invoked by the client, which interact with one or more backend services, gathering the results and delivering them back to the client in a single response.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

These are some benefits of using GraphQL Persisted Queries to provide an API gateway:

- Clients do not need to handle connections to backend services, thus simplifying their logic
- Access to backend services is centralized
- No credentials are exposed on the client
- Credentials can be provided to the server via environment variables
- The response from the service can be transformed into what the client expects
- If some backend service is upgraded, the Persisted Query could be adapted without producing breaking changes in the client
- The server can store logs of access to the backend services, and extract metrics to enhance analytics

</div>

This recipe demonstrates an API gateway that retrieves the latest artifacts from the GitHub Actions API, and extracts their URL to be downloaded, avoiding the need for the client to be signed in to GitHub.

## GraphQL-powered API gateway to access GitHub Action artifacts

This GraphQL query first retrieves the latest artifacts from GitHub Actions, and extracts the proxy URL to access each of them (because only authenticated users can access the artifacts, these URLs do not point to the actual artifact yet).

It then accesses each of these proxy URLs (which has the artifact uploaded to a public location for a short period of time) and extracts the actual URL from the HTTP response's `Location` header.

Finally it prints all actual URLs, allowing non-authenticated users to download GitHub artifacts.

(The recipe end there, but as a continuation, the GraphQL query could then do something with these URLs: send them by email, upload the files by FTP somewhere, install them in an InstaWP site, etc.)

```graphql
query RetrieveGitHubAccessToken {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
    @export(as: "githubAccessToken")
    @remove
}

query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3)
  @depends(on: "RetrieveGitHubAccessToken")
{
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
          password: $githubAccessToken
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
      @export(as: "artifactDownloadURLs", type: LIST)
  }
}

query PrintArtifactDownloadURLsAsList
  @depends(on: "RetrieveActualArtifactDownloadURLs")
{
  artifactDownloadURLs: _echo(value: $artifactDownloadURLs)
}
```

The response is:

```json
{
  "data": {
    "gitHubProxyArtifactDownloadURLs": [
      "https://api.github.com/repos/leoloso/PoP/actions/artifacts/803444209/zip",
      "https://api.github.com/repos/leoloso/PoP/actions/artifacts/803444208/zip",
      "https://api.github.com/repos/leoloso/PoP/actions/artifacts/803444207/zip"
    ],
    "_sendHTTPRequests": [
      {
        "artifactDownloadURL": "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gato-graphql-testing-schema-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9351393Z&urlSigningMethod=HMACV2&urlSignature=8v8cDVZKAnkXoN8z1GdjXLz4SCGkpv%2Fl0qjlDArac5M%3D"
      },
      {
        "artifactDownloadURL": "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gato-graphql-testing-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9333471Z&urlSigningMethod=HMACV2&urlSignature=ffsyy0p97oeQByMD3X6WKbFyIEbh6nbU%2BFsXKHQHYSM%3D"
      },
      {
        "artifactDownloadURL": "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gato-graphql-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9699160Z&urlSigningMethod=HMACV2&urlSignature=gUi%2F39RS7X5YgVZbEu977ufFt1girQKeNI7LP61gxfY%3D"
      }
    ],
    "artifactDownloadURLs": [
      "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gato-graphql-testing-schema-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9351393Z&urlSigningMethod=HMACV2&urlSignature=8v8cDVZKAnkXoN8z1GdjXLz4SCGkpv%2Fl0qjlDArac5M%3D",
      "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gato-graphql-testing-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9333471Z&urlSigningMethod=HMACV2&urlSignature=ffsyy0p97oeQByMD3X6WKbFyIEbh6nbU%2BFsXKHQHYSM%3D",
      "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gato-graphql-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9699160Z&urlSigningMethod=HMACV2&urlSignature=gUi%2F39RS7X5YgVZbEu977ufFt1girQKeNI7LP61gxfY%3D"
    ]
  }
}
```

## Alternative: Obtaining the GitHub credentials from the HTTP request

We can also allow our users to provide their own GitHub credentials.

This GraphQL query is an adaptation of the previous one, with the following differences:

- Operation `RetrieveGitHubAccessToken` uses the value from incoming header `X-Github-Access-Token` for the credentials
- Operation `ValidateHasGitHubAccessToken` validates that this header was provided, and `FailIfNoGitHubAccessToken` triggers an error when it does not
- All remaining operations have directive `@include(if: $hasGithubAccessToken)`, so that they will be executed only if the access token is provided

```graphql
query RetrieveGitHubAccessToken {
  githubAccessToken: _httpRequestHeader(name: "X-Github-Access-Token")
    @export(as: "githubAccessToken")
    @remove
}

query ValidateHasGitHubAccessToken
  @depends(on: "RetrieveGitHubAccessToken")
{
  hasGithubAccessToken: _notNull(value: $githubAccessToken)
    @export(as: "hasGithubAccessToken")
}

query FailIfNoGitHubAccessToken
  @depends(on: "ValidateHasGitHubAccessToken")
  @skip(if: $hasGithubAccessToken)
{
  _fail(
    message: "Header \"X-Github-Access-Token\" has not been provided"
  ) @remove
}

query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3)
  @depends(on: "ValidateHasGitHubAccessToken")
  @include(if: $hasGithubAccessToken)
{
  # ...
}

query CreateHTTPRequestInputs
  @depends(on: "RetrieveProxyArtifactDownloadURLs")
  @include(if: $hasGithubAccessToken)
{
  # ...
}

query RetrieveActualArtifactDownloadURLs
  @depends(on: "CreateHTTPRequestInputs")
  @include(if: $hasGithubAccessToken)
{
  # ...
}

query PrintArtifactDownloadURLsAsList
  @depends(on: [
    "RetrieveActualArtifactDownloadURLs",
    "FailIfNoGitHubAccessToken"
  ])
  @include(if: $hasGithubAccessToken)
{
  # ...
}
```

When header `X-Github-Access-Token` is not provided, the response will be:

```json
{
  "errors": [
    {
      "message": "Header \"X-Github-Access-Token\" has not been provided",
      "locations": [
        {
          "line": 18,
          "column": 3
        }
      ],
      "extensions": {
        "path": [
          "_fail(message: \"Header \"X-Github-Access-Token\" has not been provided\") @remove",
          "query FailIfNoGitHubAccessToken @depends(on: \"ValidateHasGitHubAccessToken\") @skip(if: $hasGithubAccessToken) { ... }"
        ],
        "type": "QueryRoot",
        "field": "_fail(message: \"Header \"X-Github-Access-Token\" has not been provided\") @remove",
        "id": "root",
        "code": "PoPSchema/FailFieldAndDirective@e1"
      }
    }
  ],
  "data": {
    "hasGithubAccessToken": false
  }
}
```

```graphql
query One {
  githubAccessToken: _httpRequestHeader(name: "X-Github-Access-Token")
    @export(as: "githubAccessToken")
  slackAccessToken: _httpRequestHeader(name: "X-Slack-Access-Token")
    @export(as: "slackAccessToken")
  hasAllAccessTokens: _and(values: [$__githubAccessToken, $__slackAccessToken])
    @export(as: "hasAllAccessTokens")
}

query Two
  @depends(on: "One")
  @include(if: $hasAllAccessTokens)
{
  # ...
}

query Three
  @depends(on: "One")
  @skip(if: $hasAllAccessTokens)
{
  _fail(...)
}

query Four
  @depends(on: ["Two", "Three"])
{
  
}


query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3) {
  githubAccessToken: _httpRequestHeader(name: "X-Github-Access-Token")
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
```

Because an API gateway can communicate with multiple services, we extend the idea for all of them:

```graphql
query One {
  githubAccessToken: _httpRequestHeader(name: "X-Github-Access-Token")
    @export(as: "githubAccessToken")
  slackAccessToken: _httpRequestHeader(name: "X-Slack-Access-Token")
    @export(as: "slackAccessToken")
  hasAllAccessTokens: _and(values: [$__githubAccessToken, $__slackAccessToken])
    @export(as: "hasAllAccessTokens")
}

query Two
  @depends(on: "One")
  @include(if: $hasAllAccessTokens)
{
  # ...
}

query Three
  @depends(on: "One")
  @skip(if: $hasAllAccessTokens)
{
  _fail(...)
}

query Four
  @depends(on: ["Two", "Three"])
{
  
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
    Receive header "X-GitHub-Access-Token", forward it to "Bearer-Token"

Talk about validating the different inputs, provided via headers:

```graphql
query One {
  githubAccessToken: _httpRequestHeader(name: "X-Github-Access-Token")
    @export(as: "githubAccessToken")
  slackAccessToken: _httpRequestHeader(name: "X-Slack-Access-Token")
    @export(as: "slackAccessToken")
  hasAllAccessTokens: _and(values: [$__githubAccessToken, $__slackAccessToken])
    @export(as: "hasAllAccessTokens")
}

query Two
  @depends(on: "One")
  @include(if: $hasAllAccessTokens)
{
  # ...
}

query Three
  @depends(on: "One")
  @skip(if: $hasAllAccessTokens)
{
  _fail(...)
}

query Four
  @depends(on: ["Two", "Three"])
{
  
}
```