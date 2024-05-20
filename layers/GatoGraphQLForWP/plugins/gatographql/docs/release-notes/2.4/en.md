# Release Notes: 2.4

## Improvements

- Support for subfolder-based Multisite network ([#2677](https://github.com/GatoGraphQL/GatoGraphQL/pull/2677))
- Added documentation for new PRO field `_strBase64Encode` ([#2673](https://github.com/GatoGraphQL/GatoGraphQL/pull/2673))
- Link extensions to the Extensions Reference in gatographql.com ([#2675](https://github.com/GatoGraphQL/GatoGraphQL/pull/2675))
- Added YouTube channel link to About page ([#2676](https://github.com/GatoGraphQL/GatoGraphQL/pull/2676))

### Support Application Passwords ([#2672](https://github.com/GatoGraphQL/GatoGraphQL/pull/2672))

It is now possible to use WordPress [Application Passwords](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/) to send an authenticated request to the GraphQL endpoint.

For instance, we can pass the application password when executing the `curl` command against the GraphQL server:

```bash
curl -i \
  --user "USERNAME:PASSWORD" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "{ me { name } }"}' \
  https://mysite.com/graphql
```

When using Gato GraphQL PRO, thanks to the newly added `_strBase64Encode` field, we can use GraphQL to execute aunthenticated HTTP requests against another WordPress site.

The query below receives the username, application password, and endpoint, creates the required authentication header (of type "Basic base64encoded(username:password)"), and sends a GraphQL request against the server:

```graphql
query GetDataFromExternalWPSite(
  $username: String!
  $appPassword: String!
  $endpoint: URL!
) {
  loginCredentials: _sprintf(
    string: "%s:%s",
    values: [$username, $appPassword]
  )
    @remove

  base64EncodedLoginCredentials: _strBase64Encode(
    string: $__loginCredentials
  )
    @remove

  loginCredentialsHeaderValue: _sprintf(
    string: "Basic %s",
    values: [$__base64EncodedLoginCredentials]
  )
    @remove

  externalHTTPRequestWithUserPassword: _sendGraphQLHTTPRequest(input:{
    endpoint: $endpoint,
    query: """
  
{
  me {
    name
  }
}

    """,
    options: {
      headers: [
        {
          name: "Authorization",
          value: $__loginCredentialsHeaderValue
        }
      ]
    }
  })
}
```

## Fixed

- Highlight extensions and enable link to visit in website ([#2674](https://github.com/GatoGraphQL/GatoGraphQL/pull/2674))
