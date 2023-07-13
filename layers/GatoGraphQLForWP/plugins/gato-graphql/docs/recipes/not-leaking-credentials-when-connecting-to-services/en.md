# Not leaking credentials when connecting to services

This GraphQL query retrieves credentials from an environment value, and avoids them ever getting printed in the response or logs, thus avoiding security risks:

```graphql
query {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
    @remove

  _sendJSONObjectItemHTTPRequest(input:{
    url: "https://api.github.com/repos/leoloso/PoP",
    method: PATCH,
    options: {
      auth: {
        password: $__githubAccessToken
      },
      body: "{\"has_wiki\":false}"
    }
  })
}
```

## Where could credentials be exposed

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
        password: "{ GITHUB_ACCESS_TOKEN }"
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

## GraphQL query that avoids leaking credentials

This GraphQL query passes the credentials to GitHub's API:

```graphql
query {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
    @remove

  _sendJSONObjectItemHTTPRequest(input:{
    url: "https://api.github.com/repos/leoloso/PoP",
    method: PATCH,
    options: {
      auth: {
        password: $__githubAccessToken
      },
      body: "{\"has_wiki\":false}"
    }
  })
}
```

...while avoding leaking the credentials, because:

- The credentials are retrieved from an environment variable `GITHUB_ACCESS_TOKEN`, hence they need not be embedded in the source code
- Field `githubAccessToken` is `@remove`d, hence it is not printed in the response
- The `_sendJSONObjectItemHTTPRequest(auth:)` input references dynamic variable `$__githubAccessToken` (provided via the [**Field to Input**](https://gatographql.com/extensions/field-to-input/) extension), hence if the field produces an error, it is the literal string `"$__githubAccessToken"` that will be printed in the error message (not its value)

To demonstrate the latter item, if providing the URL of a non-existing repository `"leoloso/NonExisting"`, we get this response (notice `auth: {password: $__githubAccessToken}` in the error message):

```json
{
  "errors": [
    {
      "message": "Client error: `PATCH https://api.github.com/repos/leoloso/NonExisting` resulted in a `404 Not Found` response:\n{\"message\":\"Not Found\",\"documentation_url\":\"https://docs.github.com/rest/repos/repos#update-a-repository\"}\n",
      "locations": [
        {
          "line": 21,
          "column": 3
        }
      ],
      "extensions": {
        "path": [
          "_sendJSONObjectItemHTTPRequest(input: {url: \"https://api.github.com/repos/leoloso/NonExisting\", method: PATCH, options: {auth: {password: $__githubAccessToken}, body: \"{\"has_wiki\":false}\"}})",
          "query { ... }"
        ],
        "type": "QueryRoot",
        "field": "_sendJSONObjectItemHTTPRequest(input: {url: \"https://api.github.com/repos/leoloso/NonExisting\", method: PATCH, options: {auth: {password: $__githubAccessToken}, body: \"{\"has_wiki\":false}\"}})",
        "id": "root",
        "code": "PoP/ComponentModel@e1"
      }
    }
  ],
  "data": {
    "_sendJSONObjectItemHTTPRequest": null
  }
}
```
