# Retrieving and downloading GitHub Artifacts

Check that installing plugin in WP-CLI doesn't work:
  For instance, this doesn't work:
    https://api.github.com/repos/leoloso/PoP/actions/artifacts/416480816/zip
  But resolving to, works well:
    https://pipelines.actions.githubusercontent.com/serviceHosts/a6be3ecc-6518-4aaa-b5ec-232be0438a37/_apis/pipelines/1/runs/35501/signedartifactscontent?artifactName=graphql-api-testing&urlExpires=2022-10-29T11%3A17%3A05.4139998Z&urlSigningMethod=HMACV2&urlSignature=hBUqwaCmakdI5JzHR1K8Dxs1nhc4MC5pBx5Fy46ZWkg%3D
Source:
    Downloading installation package from https://api.github.com/repos/leoloso/PoP/actions/artifacts/418953849/zip...
    Warning: Download failed. "Forbidden"
    Warning: The 'https://api.github.com/repos/leoloso/PoP/actions/artifacts/418953849/zip' plugin could not be found.
Then, GraphQL API is the perfect complement for WP-CLI!
  The missing "5%" to reach "100%"
Create a solution using GraphQL to obtain and install the artifacts from GitHub!!!
  Check the URL to download comes in the "location" header

```bash
# Execute the GraphQL query against the standalone server
GRAPHQL_RESPONSE=...

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

print-github-actions-artifact-download-urls.gql:

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
    @forEach(passOnwardsAs: "artifactItem")
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
    @forEach(passOnwardsAs: "url")
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

more bash:

```bash
########################################################################
# Inputs
# ----------------------------------------------------------------------
VARIABLES_FILE_NAME="$1"
LANDO_WEBSERVER_PWD="$2"
########################################################################

# Save location of Lando webserver
if [ -z "$LANDO_WEBSERVER_PWD" ]; then
  LANDO_WEBSERVER_PWD="$( pwd )"
fi
ENV_FILE="$LANDO_WEBSERVER_PWD/defaults.local.env"

# Current directory
# @see: https://stackoverflow.com/questions/59895/how-to-get-the-source-directory-of-a-bash-script-from-within-the-script-itself#comment16925670_59895
BASH_SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
cd $BASH_SCRIPT_DIR

ARTIFACT_DOWNLOAD_URLS=$(bash -x print-github-actions-artifact-download-urls.sh "$VARIABLES_FILE_NAME" "" "$ENV_FILE")

echo "Installing plugins from locations: ${ARTIFACT_DOWNLOAD_URLS}"

cd $LANDO_WEBSERVER_PWD

# Install the plugins using WP-CLI
# @see https://developer.wordpress.org/cli/commands/plugin/install/
lando wp plugin install $ARTIFACT_DOWNLOAD_URLS --force --activate --url="graphql-api-pro-for-prod.lndo.site" --path=/app/wordpress
```
