# Site migrations

Replacing old domain to new domain in content

```graphql
query ExportSiteURL
{
  siteURL: optionValue(name: "siteurl")
    # Hack for this test to work in both "Integration Tests" and "PROD Integration Tests"
    @strReplace(
      replace: "-for-prod.lndo.site"
      with: ".lndo.site"
    )
    @export(as: "siteURL")
}

query ExportData(
  $oldPageSlug: String!
  $newPageSlug: String!
)
  @depends(on: "ExportSiteURL")
{
  oldPageURL: _strAppend(
    after: $siteURL,
    append: $oldPageSlug
  ) @export(as: "oldPageURL")

  newPageURL: _strAppend(
    after: $siteURL,
    append: $newPageSlug
  ) @export(as: "newPageURL")
}

mutation ReplaceOldWithNewURLInPosts
  @depends(on: "ExportData")
{
  posts(filter: { search: $oldPageURL }, sort: {by: ID, order: ASC}) {
    id
    contentSource
    adaptedContentSource: _strReplace(
      replace: $oldPageURL
      with: $newPageURL
      in: $__contentSource
    )
    update(input: {
      content: $__adaptedContentSource
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        id
        contentSource
      }
    }
  }
}
```

var:

```json
{
  "oldPageSlug": "/privacy/",
  "newPageSlug": "/user-privacy/"
}
```
