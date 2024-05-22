# Release Notes: 2.4

## Improvements

- Install "internal" private custom endpoint ([#2684](https://github.com/GatoGraphQL/GatoGraphQL/pull/2684))
- Support for subfolder-based Multisite network ([#2677](https://github.com/GatoGraphQL/GatoGraphQL/pull/2677))
- Added documentation for new PRO field `_strBase64Encode` ([#2673](https://github.com/GatoGraphQL/GatoGraphQL/pull/2673))
- Link extensions to the Extensions Reference in gatographql.com ([#2675](https://github.com/GatoGraphQL/GatoGraphQL/pull/2675))
- Added YouTube channel link to About page ([#2676](https://github.com/GatoGraphQL/GatoGraphQL/pull/2676))
- Added predefined persisted queries:
  - [PRO] Translate and create all pages for a multilingual site (Multisite / Gutenberg) ([#2688](https://github.com/GatoGraphQL/GatoGraphQL/pull/2688))
  - [PRO] Translate and create all pages for a multilingual site (Multisite / Classic editor) ([#2688](https://github.com/GatoGraphQL/GatoGraphQL/pull/2688))

### Added page mutations to the GraphQL schema ([#2682](https://github.com/GatoGraphQL/GatoGraphQL/pull/2682))

Added the following mutations to the GraphQL schema:

- `Root.createPage`
- `Root.updatePage`
- `Page.update`

For instance, you can now execute this GraphQL query to modify a page:

```graphql
mutation UpdatePage {
  updatePage(input: {
    id: 2
    title: "Updated title"
    contentAs: { html: "Updated content" },
    status: pending
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    page {
      id
      rawTitle
      rawContent
      status
    }
  }
}
```

### Added fields to fetch the logged-in user's pages ([#2682](https://github.com/GatoGraphQL/GatoGraphQL/pull/2682))

`v2.4` also adds fields to retrieve the logged-in user's pages.

The previously-existing fields `Root.page`, `Root.pages` and `Root.pageCount` retrieve pages for any user, but only public ones (i.e. those with status `"publish"`).

From this version on, we can fetch public or private pages from the logged-in user (i.e. with status `"publish"`, `"pending"`, `"draft"` or `"trash"`), using these new fields:

- `Root.myPage`
- `Root.myPages`
- `Root.myPageCount`

```graphql
query {
  myPages(filter: { status: [draft, pending] }) {
    id
    title
    status
  }
}
```

### Added fields to fetch the site's locale and language ([#2685](https://github.com/GatoGraphQL/GatoGraphQL/pull/2685))

Added the following fields to the GraphQL schema:

- `Root.siteLocale`
- `Root.siteLanguage`

For instance, executing the following query:

```graphql
{
  siteLocale
  siteLanguage
}
```

...might produce:

```json
{
  "data": {
    "siteLocale": "en_US",
    "siteLanguage": "en"
  }
}
```

These fields are provided via the new "Site" module. Disabling this module will remove the fields from the GraphQL schema.

### Support Application Passwords ([#2672](https://github.com/GatoGraphQL/GatoGraphQL/pull/2672))

It is now possible to use WordPress [Application Passwords](https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/) to send an authenticated request to the GraphQL endpoint.

For instance, we can pass the application password when executing the `curl` command against the GraphQL server, replacing the `USERNAME` and `PASSWORD` values:

```bash
curl -i \
  --user "USERNAME:PASSWORD" \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"query": "{ me { name } }"}' \
  https://mysite.com/graphql
```

When using Gato GraphQL PRO, thanks to the newly added `_strBase64Encode` field, we can use GraphQL to execute authenticated HTTP requests against another WordPress site.

The query below receives the username and application password (and the endpoint to connect to), creates the required authentication header (of type "Basic base64encoded(username:password)"), and sends an HTTP request against the GraphQL server, passing the GraphQL query to execute:

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
- GraphiQL client (for LocalWP) now uses site URL as endpoint ([#2686](https://github.com/GatoGraphQL/GatoGraphQL/pull/2686))
