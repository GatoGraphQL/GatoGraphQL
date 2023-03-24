# Using the GraphQL server without WordPress

Use contents of these files:

StandaloneGraphQLServerScriptHelper.php
AbstractScriptingStandaloneGraphQLServer.php
RetrieveGithubActionsArtifactDownloadURLsScriptingStandaloneGraphQLServer.php

And then all the rest:

```php
<?php

declare(strict_types=1);

use PoPDevHelpers\StandaloneGraphQLServerScripts\RetrieveGithubActionsArtifactDownloadURLsScriptingStandaloneGraphQLServer;

$rootDir = dirname(__DIR__, 2);

/**
 * There are 2 ways to execute the Standalone GraphQL server:
 *
 * 1. Via the already-installed generated plugins:
 *    By passing env var `USE_GENERATED_PLUGINS_AS_STANDALONE_CODE=true`
 * 2. Via the source code, otherwise
 */

/** @var string|false */
$useGeneratedPluginsAsStandaloneCode = getenv('USE_GENERATED_PLUGINS_AS_STANDALONE_CODE');
if ($useGeneratedPluginsAsStandaloneCode === 'true') {
    $prodWebserverDir = $rootDir . '/webservers/graphql-api-pro-for-prod';
    require_once ($prodWebserverDir . '/wordpress/wp-content/plugins/graphql-api/vendor/scoper-autoload.php');
    require_once ($prodWebserverDir . '/wordpress/wp-content/plugins/graphql-api-pro/vendor/scoper-autoload.php');
} else {
    require_once ($rootDir . '/vendor/autoload.php');
}

require_once (__DIR__ . '/vendor/autoload.php');

/**
 * Command line inputs
 */
$variablesFileName = $argv[1] ?? null;
if ($variablesFileName === "") {
    $variablesFileName = null;
}

$retrieveGithubActionsArtifactDownloadURLsGraphQLServer = new RetrieveGithubActionsArtifactDownloadURLsScriptingStandaloneGraphQLServer(
    $variablesFileName,
);
echo $retrieveGithubActionsArtifactDownloadURLsGraphQLServer->execute();
exit;
```


```bash
# Execute the GraphQL query against the standalone server
GRAPHQL_RESPONSE=$(php -f ../standalone-graphql-server-scripts/retrieve-github-actions-artifact-download-urls.php $VARIABLES_FILE_NAME)

# Print the full response, or extract just the needed data (default)
if [ -z "$PRINT_FULL_RESPONSE" ]; then
    echo $GRAPHQL_RESPONSE \
        | grep -E -o '"spaceSeparatedArtifactDownloadURLs\":"(.*)"' \
        | cut -d':' -f2- | cut -d'"' -f2- | rev | cut -d'"' -f2- | rev \
        | sed 's/\\\//\//g'
else
    echo $GRAPHQL_RESPONSE
fi
```

```json
{
  "repoOwner": "leoloso",
  "repoProject": "PoP",
  "perPage": 2
}
```

```graphql
query RetrieveProxyArtifactDownloadURLs(
  $repoOwner: String!
  $repoProject: String!
  $perPage: Int = 1
  $artifactName: String = ""
) {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
    # This directive will remove this entry from the output
    @remove

  # Create the authorization header to send to GitHub
  authorizationHeader: _sprintf(
    string: "Bearer %s"
    # "Field to Input" feature to access value from the field above
    values: [$__githubAccessToken]
  )
    # Do not print in output
    @remove

  # Create the authorization header to send to GitHub
  githubRequestHeaders: _echo(
    value: [
      { name: "Accept", value: "application/vnd.github+json" }
      { name: "Authorization", value: $__authorizationHeader }
    ]
  )
    # Do not print in output
    @remove
    @export(as: "githubRequestHeaders")

  githubAPIEndpoint: _sprintf(
    string: "https://api.github.com/repos/%s/%s/actions/artifacts?per_page=%s&name=%s"
    values: [$repoOwner, $repoProject, $perPage, $artifactName]
  )

  # Use the field from "HTTP Request Fields" to connect to GitHub
  gitHubArtifactData: _requestJSONObjectItem(
    input: {
      url: $__githubAPIEndpoint
      options: { headers: $__githubRequestHeaders }
    }
  )
    # Do not print in output
    @remove

  # Finally just extract the URL from within each "artifacts" item
  gitHubProxyArtifactDownloadURLs: _objectProperty(
    object: $__gitHubArtifactData
    by: { key: "artifacts" }
  )
    @forEach(passOnwardsAs: "artifactItem")
      @applyField(
        name: "_objectProperty"
        arguments: { object: $artifactItem, by: { key: "archive_download_url" } }
        setResultInResponse: true
      )
    @export(as: "gitHubProxyArtifactDownloadURLs")
}

query CreateHTTPRequestInputs
  @depends(on: "RetrieveProxyArtifactDownloadURLs")
{
  httpRequestInputs: _echo(value: $gitHubProxyArtifactDownloadURLs)
    @forEach(passOnwardsAs: "url")
      @applyField(
        name: "_objectAddEntry"
        arguments: {
          object: {
            options: { headers: $githubRequestHeaders, allowRedirects: null }
          }
          key: "url"
          value: $url
        }
        setResultInResponse: true
      )
    @export(as: "httpRequestInputs")
    @remove
}

query RetrieveActualArtifactDownloadURLs
  @depends(on: "CreateHTTPRequestInputs")
{
  _asyncRequest(inputs: $httpRequestInputs) {
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
