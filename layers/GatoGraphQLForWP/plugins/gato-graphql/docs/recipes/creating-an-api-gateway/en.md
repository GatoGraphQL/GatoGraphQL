# Creating an API gateway

An API gateway is a component on our application that provides a centralized handling of API communication between the client and the multiple required services.

The API gateway can be implemented via GraphQL Persisted Queries stored in the server and invoked by the client, which interact with one or more backend services, gathering the results and delivering them back to the client in a single response.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

These are some benefits of using GraphQL Persisted Queries to provide an API gateway:

- Clients do not need to handle connections to backend services, thus simplifying their logic
- Access to backend services is centralized
- No credentials are exposed on the client
- The response from the service can be transformed into what the client expects or can handle better
- If some backend service is upgraded, the Persisted Query could be adapted without producing breaking changes in the client
- The server can store logs of access to the backend services, and extract metrics to enhance analytics

</div>

This recipe demonstrates an API gateway that retrieves the latest artifacts from the GitHub Actions API, and extracts their URL to be downloaded, avoiding the need for the client to be signed in to GitHub.

## GraphQL-powered API gateway to access GitHub Action artifacts

The GraphQL query below should be [stored as a Persisted Query](https://gatographql.com/guides/use/creating-a-persisted-query/) (with slug `retrieve-public-urls-for-github-actions-artifacts`).

It retrieves the publicly-accessible download URLs for GitHub Actions artifacts:

- It first fetches the latest X number of artifacts from GitHub Actions, and extracts the proxy URL to access each of them. (Because only authenticated users can access the artifacts, these URLs do not point to the actual artifact yet.)
- It then accesses each of these proxy URLs (which has the artifact uploaded to a public location for a short period of time) and extracts the actual URL from the HTTP response's `Location` header
- Finally it prints all publicly-accessible URLs, allowing non-authenticated users to download GitHub artifacts within that window of time

_(The recipe ends here, but as a continuation, the GraphQL query could then do something with these URLs: send them by email, upload the files by FTP somewhere, install them in an InstaWP site, etc.)_

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

- Operation `RetrieveGitHubAccessToken` reads the value from the current HTTP request's header `X-Github-Access-Token` for the credentials, and indicates if this header has not been provided
- `FailIfNoGitHubAccessToken` triggers an error when the header is missing
- All other operations have been added directive `@skip(if: $isGithubAccessTokenMissing)`, so that they will not be executed the the token is missing

```graphql
query RetrieveGitHubAccessToken {
  githubAccessToken: _httpRequestHeader(name: "X-Github-Access-Token")
    @export(as: "githubAccessToken")
    @remove

  isGithubAccessTokenMissing: _isEmpty(value: $__githubAccessToken)
    @export(as: "isGithubAccessTokenMissing")
}

query FailIfNoGitHubAccessToken
  @depends(on: "RetrieveGitHubAccessToken")
  @include(if: $isGithubAccessTokenMissing)
{
  _fail(
    message: "Header \"X-Github-Access-Token\" has not been provided"
  ) @remove
}

query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3)
  @depends(on: "RetrieveGitHubAccessToken")
  @skip(if: $isGithubAccessTokenMissing)
{
  # ...
}

query CreateHTTPRequestInputs
  @depends(on: "RetrieveProxyArtifactDownloadURLs")
  @skip(if: $isGithubAccessTokenMissing)
{
  # ...
}

query RetrieveActualArtifactDownloadURLs
  @depends(on: "CreateHTTPRequestInputs")
  @skip(if: $isGithubAccessTokenMissing)
{
  # ...
}

query PrintArtifactDownloadURLsAsList
  @depends(on: [
    "RetrieveActualArtifactDownloadURLs",
    "FailIfNoGitHubAccessToken"
  ])
  @skip(if: $isGithubAccessTokenMissing)
{
  # ...
}
```

When the header `X-Github-Access-Token` is provided, the response is the same as above.

When it is not provided, the response will be:

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
          "query FailIfNoGitHubAccessToken @depends(on: \"ValidateHasGitHubAccessToken\") @skip(if: $isGithubAccessTokenMissing) { ... }"
        ],
        "type": "QueryRoot",
        "field": "_fail(message: \"Header \"X-Github-Access-Token\" has not been provided\") @remove",
        "id": "root",
        "code": "PoPSchema/FailFieldAndDirective@e1"
      }
    }
  ],
  "data": {
    "isGithubAccessTokenMissing": false
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

As an API gateway can communicate with multiple services, we can also retrieve the credentials for all of them from the HTTP request:

```graphql
query RetrieveServiceTokens {
  githubAccessToken: _httpRequestHeader(name: "X-Github-Access-Token")
    @export(as: "githubAccessToken")
  slackAccessToken: _httpRequestHeader(name: "X-Slack-Access-Token")
    @export(as: "slackAccessToken")

  isGithubAccessTokenMissing: _isEmpty(value: $__githubAccessToken)
  isSlackAccessTokenMissing: _isEmpty(value: $__slackAccessToken)    
  isAnyAccessTokenMissing: _or(values: [
    $__isGithubAccessTokenMissing,
    $__isSlackAccessTokenMissing
  ])
    @export(as: "isAnyAccessTokenMissing")
}

query FailIfAnyAccessTokenMissing
  @depends(on: "RetrieveServiceTokens")
  @include(if: $isAnyAccessTokenMissing)
{
  _fail(
    message: "Access tokens for GitHub and Slack must be provided"
  ) @remove
}

query RetrieveProxyArtifactDownloadURLs
  @depends(on: "RetrieveServiceTokens")
  @skip(if: $isAnyAccessTokenMissing)
{
  # ...
}

# ...
```

</div>

## Analysis of the novel elements in the GraphQL query

The endpoint to connect to can be dynamically generated, in this case using `_sprintf`:

```graphql
query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3)
  @depends(on: "RetrieveGitHubAccessToken")
{
  githubArtifactsEndpoint: _sprintf(
    string: "https://api.github.com/repos/leoloso/PoP/actions/artifacts?per_page=%s",
    values: [$numberArtifacts]
  )
    @remove

  # ...
}
```

After fetching the data from the API, we navigate to each data item `"archive_download_url"` within the JSON object structure, extract that value using field `_objectProperty` (applied via directive `@applyField`), and override the iterated-upon element by passing argument `setResultInResponse: true`:

```graphql
query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3)
  @depends(on: "RetrieveGitHubAccessToken")
{
  # ...
  
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

We connect to all artifact URLs simultaneously by sending multiple HTTP requests asynchronously via field `_sendHTTPRequests`, and then we query the `Location` header from each of them.

We need to provide argument `input` of type `[HTTPRequestInput]` to  `_sendHTTPRequests`. To do this, we iterate each of the artifact URLs (stored under dynamic variable `$gitHubProxyArtifactDownloadURLs`) and, for each, we dynamically build a JSON object using field `_objectAddEntry` that contains all the required parameters (headers, authentication, and others) and appends the URL to it (available under dynamic variable `$url`).

The generated list of JSON objects will be coerced to `[HTTPRequestInput]` when passed as argument to `_sendHTTPRequests(input:)`.

```graphql
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
```
