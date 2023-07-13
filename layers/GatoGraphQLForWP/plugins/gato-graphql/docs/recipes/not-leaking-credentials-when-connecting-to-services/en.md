# Not leaking credentials when connecting to services

Credentials in this GraphQL query are retrieved from an environment value, and never printed in the response, avoiding security risks:

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
- The `_sendJSONObjectItemHTTPRequest(auth:)` input references dynamic variable `$__githubAccessToken` (provided via the [**Field to Input**](https://gatographql.com/extensions/field-to-input/) extension), hence if field `_sendJSONObjectItemHTTPRequest` produces an error, the literal string `$__githubAccessToken` will be printed in the error message, not its value
