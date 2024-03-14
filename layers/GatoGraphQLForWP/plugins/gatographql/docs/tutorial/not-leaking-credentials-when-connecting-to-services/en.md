# Lesson 21: Not leaking credentials when connecting to services

This GraphQL query retrieves credentials from an environment value, and avoids them getting printed in the response or logs, thus avoiding security risks:

```graphql
query {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
    @remove

  _sendJSONObjectItemHTTPRequest(input:{
    url: "https://api.github.com/repos/GatoGraphQL/GatoGraphQL",
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

Below is an explanation of how this query works.

## How could credentials be leaked

We often need to provide credentials when connecting to external services. For instance, GitHub's REST API requires an access token for endpoints where data is private or is mutated:

```graphql
query {
  _sendJSONObjectItemHTTPRequest(input:{
    url: "https://api.github.com/repos/GatoGraphQL/GatoGraphQL",
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

We need to be careful and avoid exposing our credentials:

- **In the GraphQL query:** Credentials must never be embedded in source code, as these will be in plain text, creating a security hazard
- **In the GraphQL response:** If the field connecting to the service produces an error, an error message will be added in the GraphQL response under entry `errors`; this message might print the name of the field that failed alongside its arguments, thus printing the credentials
- **In the server logs:** If credentials are accessed via a variable, and this variable is provided as an URL param, then it might be recorded in the webserver's logs

## GraphQL query that avoids leaking credentials

This GraphQL query passes the credentials to GitHub's API while avoding leaking the credentials:

```graphql
query {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
    @remove

  _sendJSONObjectItemHTTPRequest(input:{
    url: "https://api.github.com/repos/GatoGraphQL/GatoGraphQL",
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

This is because:

- The credentials are retrieved from an environment variable `GITHUB_ACCESS_TOKEN`, hence they need not be embedded in the source code
- Field `githubAccessToken` is `@remove`d, hence it is not printed in the response
- The `_sendJSONObjectItemHTTPRequest(auth:)` input references dynamic variable `$__githubAccessToken`, hence if the field produces an error, it is the literal string `"$__githubAccessToken"` that will be printed in the error message (not its value)

To demonstrate the latter item, providing the URL of a non-existing repository `"leoloso/NonExisting"` to GitHub's API raises an error, and we get this response (notice `auth: {password: $__githubAccessToken}` in the error message):

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
