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

The GraphQL query below must be [stored as a Persisted Query](https://gatographql.com/guides/use/creating-a-persisted-query/) (for instance, using slug `retrieve-public-urls-for-github-actions-artifacts`).

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
    string: "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts?per_page=%s",
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
      "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803444209/zip",
      "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803444208/zip",
      "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803444207/zip"
    ],
    "_sendHTTPRequests": [
      {
        "artifactDownloadURL": "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gatographql-testing-schema-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9351393Z&urlSigningMethod=HMACV2&urlSignature=8v8cDVZKAnkXoN8z1GdjXLz4SCGkpv%2Fl0qjlDArac5M%3D"
      },
      {
        "artifactDownloadURL": "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gatographql-testing-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9333471Z&urlSigningMethod=HMACV2&urlSignature=ffsyy0p97oeQByMD3X6WKbFyIEbh6nbU%2BFsXKHQHYSM%3D"
      },
      {
        "artifactDownloadURL": "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gatographql-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9699160Z&urlSigningMethod=HMACV2&urlSignature=gUi%2F39RS7X5YgVZbEu977ufFt1girQKeNI7LP61gxfY%3D"
      }
    ],
    "artifactDownloadURLs": [
      "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gatographql-testing-schema-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9351393Z&urlSigningMethod=HMACV2&urlSignature=8v8cDVZKAnkXoN8z1GdjXLz4SCGkpv%2Fl0qjlDArac5M%3D",
      "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gatographql-testing-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9333471Z&urlSigningMethod=HMACV2&urlSignature=ffsyy0p97oeQByMD3X6WKbFyIEbh6nbU%2BFsXKHQHYSM%3D",
      "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53473/signedartifactscontent?artifactName=gatographql-1.0.0-dev&urlExpires=2023-07-14T03%3A31%3A00.9699160Z&urlSigningMethod=HMACV2&urlSignature=gUi%2F39RS7X5YgVZbEu977ufFt1girQKeNI7LP61gxfY%3D"
    ]
  }
}
```

## Alternative: Obtaining the GitHub credentials from the HTTP request

We can also allow our users to provide their own GitHub credentials via header.

This GraphQL query is an adaptation of the previous one, with the following differences:

- Operation `RetrieveGitHubAccessToken` reads and exports the value from the current HTTP request's `X-Github-Access-Token` header, and indicates if this header has not been provided
- `FailIfGitHubAccessTokenIsMissing` triggers an error when the header is missing
- All other operations have been added directive `@skip(if: $isGithubAccessTokenMissing)`, so that they will not be executed the the token is missing

```graphql
query RetrieveGitHubAccessToken {
  githubAccessToken: _httpRequestHeader(name: "X-Github-Access-Token")
    @export(as: "githubAccessToken")
    @remove

  isGithubAccessTokenMissing: _isEmpty(value: $__githubAccessToken)
    @export(as: "isGithubAccessTokenMissing")
}

query FailIfGitHubAccessTokenIsMissing
  @depends(on: "RetrieveGitHubAccessToken")
  @include(if: $isGithubAccessTokenMissing)
{
  _fail(
    message: "Header 'X-Github-Access-Token' has not been provided"
  ) @remove
}

query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3)
  @depends(on: "RetrieveGitHubAccessToken")
  @skip(if: $isGithubAccessTokenMissing)
{
  # Do same as before
  # ...
}

query CreateHTTPRequestInputs
  @depends(on: "RetrieveProxyArtifactDownloadURLs")
  @skip(if: $isGithubAccessTokenMissing)
{
  # Do same as before
  # ...
}

query RetrieveActualArtifactDownloadURLs
  @depends(on: "CreateHTTPRequestInputs")
  @skip(if: $isGithubAccessTokenMissing)
{
  # Do same as before
  # ...
}

query PrintArtifactDownloadURLsAsList
  @depends(on: [
    "RetrieveActualArtifactDownloadURLs",
    "FailIfGitHubAccessTokenIsMissing"
  ])
  @skip(if: $isGithubAccessTokenMissing)
{
  # Do same as before
  # ...
}
```

When the header `X-Github-Access-Token` is provided, the response is the same as above.

When it is not provided, the response will be:

```json
{
  "errors": [
    {
      "message": "Header 'X-Github-Access-Token' has not been provided",
      "locations": [
        {
          "line": 18,
          "column": 3
        }
      ],
      "extensions": {
        "path": [
          "_fail(message: \"Header 'X-Github-Access-Token' has not been provided\") @remove",
          "query FailIfGitHubAccessTokenIsMissing @depends(on: \"ValidateHasGitHubAccessToken\") @skip(if: $isGithubAccessTokenMissing) { ... }"
        ],
        "type": "QueryRoot",
        "field": "_fail(message: \"Header 'X-Github-Access-Token' has not been provided\") @remove",
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

We can retrieve from headers the credentials for multiple services used in the API gateway, while validating that they have all been provided:

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
  # Do something
  # ...
}

# Do something
# ...
```

</div>

## Step by step: creating the GraphQL query

Below is the detailed analysis of how the query works.

The endpoint to connect to can be dynamically generated, in this case using `_sprintf`:

```graphql
query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3)
  @depends(on: "RetrieveGitHubAccessToken")
{
  githubArtifactsEndpoint: _sprintf(
    string: "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts?per_page=%s",
    values: [$numberArtifacts]
  )
    @remove

  # ...
}
```

The response from the GitHub Actions API is bulky and of no interest to us, so we `@remove` it from the response. However, during development, we disable this directive, as to visualize and understand the shape of the returned JSON object, and identify the data items we need to extract:

```graphql
query RetrieveProxyArtifactDownloadURLs($numberArtifacts: Int! = 3)
  @depends(on: "RetrieveGitHubAccessToken")
{
  # ...

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
    # @remove   <= Disabled to visualize output
}
```

The response is:

```json
{
  "data": {
    "gitHubArtifactData": {
      "total_count": 8344,
      "artifacts": [
        {
          "id": 803739808,
          "node_id": "MDg6QXJ0aWZhY3Q4MDM3Mzk4MDg=",
          "name": "gatographql-testing-schema-1.0.0-dev",
          "size_in_bytes": 62952,
          "url": "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803739808",
          "archive_download_url": "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803739808/zip",
          "expired": false,
          "created_at": "2023-07-14T06:25:57Z",
          "updated_at": "2023-07-14T06:25:59Z",
          "expires_at": "2023-08-13T06:17:15Z",
          "workflow_run": {
            "id": 5551097653,
            "repository_id": 66721227,
            "head_repository_id": 66721227,
            "head_branch": "Enable-headers-in-GraphiQL",
            "head_sha": "31e69ccab2a8d1fdea942e71f7a93ec484bdd9c8"
          }
        },
        {
          "id": 803739806,
          "node_id": "MDg6QXJ0aWZhY3Q4MDM3Mzk4MDY=",
          "name": "gatographql-testing-1.0.0-dev",
          "size_in_bytes": 123914,
          "url": "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803739806",
          "archive_download_url": "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803739806/zip",
          "expired": false,
          "created_at": "2023-07-14T06:25:57Z",
          "updated_at": "2023-07-14T06:25:59Z",
          "expires_at": "2023-08-13T06:17:11Z",
          "workflow_run": {
            "id": 5551097653,
            "repository_id": 66721227,
            "head_repository_id": 66721227,
            "head_branch": "Enable-headers-in-GraphiQL",
            "head_sha": "31e69ccab2a8d1fdea942e71f7a93ec484bdd9c8"
          }
        },
        {
          "id": 803739803,
          "node_id": "MDg6QXJ0aWZhY3Q4MDM3Mzk4MDM=",
          "name": "gatographql-1.0.0-dev",
          "size_in_bytes": 33394234,
          "url": "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803739803",
          "archive_download_url": "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803739803/zip",
          "expired": false,
          "created_at": "2023-07-14T06:25:57Z",
          "updated_at": "2023-07-14T06:25:59Z",
          "expires_at": "2023-08-13T06:21:42Z",
          "workflow_run": {
            "id": 5551097653,
            "repository_id": 66721227,
            "head_repository_id": 66721227,
            "head_branch": "Enable-headers-in-GraphiQL",
            "head_sha": "31e69ccab2a8d1fdea942e71f7a93ec484bdd9c8"
          }
        }
      ]
    }
  }
}
```

The data item of our interest is property `"archive_download_url"`. We navigate to each of these data items within the JSON object structure, extract that value using field `_objectProperty` (applied via directive `@applyField`), and override the iterated-upon element by passing argument `setResultInResponse: true`:

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

We connect to all the extracted artifact URLs simultaneously via field `_sendHTTPRequests` (sending the multiple HTTP requests asynchronously), and we query the `Location` header from each response.

As field `_sendHTTPRequests` receives argument `input` (of type `[HTTPRequestInput]`), we dynamically generate this input, by:

- Iterating each of the artifact URLs (stored under dynamic variable `$gitHubProxyArtifactDownloadURLs`)
- Dynamically building a JSON object for each of them (using field `_objectAddEntry`) that contains all the required parameters (headers, authentication, and others)
- Appending the URL to this JSON object (available under dynamic variable `$url`)

This list of dynamically-created JSON objects will be coerced to `[HTTPRequestInput]` when passed as argument to `_sendHTTPRequests(input:)`. If our procedure was not right, and any item cannot be coerced to `HTTPRequestInput` (eg: because we did not provide a mandatory property, or provide a non-existing property), then the GraphQL server will produce a coercion error.

_Notice that we must `@remove` field `httpRequestInputs`, as it contains the GitHub token (under `password: $githubAccessToken`), which we do not want to print in the response. During development, though, we can disable this directive._

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
    # @remove   <= Disabled to visualize output
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

As `@remove` is now commented out, we can now visualize the generated JSON object inputs in the response (under entry `httpRequestInputs`), and then the resulting `Location` header from each HTTP response (under alias `artifactDownloadURL`):

```json
{
  "data": {
    "gitHubProxyArtifactDownloadURLs": [
      // ...
    ],
    "httpRequestInputs": [
      {
        "options": {
          "auth": {
            "password": "ghp_{some_github_access_token}"
          },
          "headers": {
            "name": "Accept",
            "value": "application/vnd.github+json"
          },
          "allowRedirects": null
        },
        "url": "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803739808/zip"
      },
      {
        "options": {
          "auth": {
            "password": "ghp_{some_github_access_token}"
          },
          "headers": {
            "name": "Accept",
            "value": "application/vnd.github+json"
          },
          "allowRedirects": null
        },
        "url": "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803739806/zip"
      },
      {
        "options": {
          "auth": {
            "password": "ghp_{some_github_access_token}"
          },
          "headers": {
            "name": "Accept",
            "value": "application/vnd.github+json"
          },
          "allowRedirects": null
        },
        "url": "https://api.github.com/repos/GatoGraphQL/GatoGraphQL/actions/artifacts/803739803/zip"
      }
    ],
    "_sendHTTPRequests": [
      {
        "artifactDownloadURL": "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53479/signedartifactscontent?artifactName=gatographql-testing-schema-1.0.0-dev&urlExpires=2023-07-14T07%3A26%3A47.2766840Z&urlSigningMethod=HMACV2&urlSignature=Ype82npdlUlLk4gcGZcBiz80e0ZuvcvnC2rdaSDg9p8%3D"
      },
      {
        "artifactDownloadURL": "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53479/signedartifactscontent?artifactName=gatographql-testing-1.0.0-dev&urlExpires=2023-07-14T07%3A26%3A47.2961965Z&urlSigningMethod=HMACV2&urlSignature=FdWAh8JXNPJsVIPNuiYN8R7i0vRnN8eCGc57VZDNUEc%3D"
      },
      {
        "artifactDownloadURL": "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53479/signedartifactscontent?artifactName=gatographql-1.0.0-dev&urlExpires=2023-07-14T07%3A26%3A47.2861087Z&urlSigningMethod=HMACV2&urlSignature=0Go8QnkZqIbn0urTQqfbMW4rQtjMfDAR9fSm6fCePjw%3D"
      }
    ]
  }
}
```

Finally we print all the `artifactDownloadURL` items together as a list (available under dynamic variable `$artifactDownloadURLs`), using `_echo`:

```graphql
query PrintArtifactDownloadURLsAsList
  @depends(on: "RetrieveActualArtifactDownloadURLs")
{
  artifactDownloadURLs: _echo(value: $artifactDownloadURLs)
}
```

This will print:

```json
{
  "data": {
    // ...
    "artifactDownloadURLs": [
      "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53479/signedartifactscontent?artifactName=gatographql-testing-schema-1.0.0-dev&urlExpires=2023-07-14T07%3A37%3A42.4998268Z&urlSigningMethod=HMACV2&urlSignature=1c1qNRfD9KFwSuzMjw9tsumq9B5I1c9H4LWgSbR0Kwg%3D",
      "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53479/signedartifactscontent?artifactName=gatographql-testing-1.0.0-dev&urlExpires=2023-07-14T07%3A37%3A42.4878741Z&urlSigningMethod=HMACV2&urlSignature=htjc1HrmZpbecECpBQnEHhlP7lkqkdyjzATb0vFnzDE%3D",
      "https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/53479/signedartifactscontent?artifactName=gatographql-1.0.0-dev&urlExpires=2023-07-14T07%3A37%3A42.5240496Z&urlSigningMethod=HMACV2&urlSignature=YDuHFqweL9m6LIycLsVy0bJJ4zePc4pWkHz8RfjfzCg%3D"
    ]
  }
}
```
