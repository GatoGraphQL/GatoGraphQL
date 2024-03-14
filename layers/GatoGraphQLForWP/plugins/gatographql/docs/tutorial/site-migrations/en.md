# Lesson 8: Site migrations

We can execute a batch of GraphQL queries to adapt the content in the site when migrating it to a new domain, moving pages to a different URL, or others.

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

## Adapting content to the new domain

This GraphQL query first filters all posts containing `"https://my-old-domain.com"` in its content, and replaces this string with `"https://my-new-domain.com"`:

```graphql
mutation ReplaceOldWithNewDomainInPosts {
  posts(
    filter: {
      search: "https://my-old-domain.com"
    }
  ) {
    id
    rawContent
    adaptedRawContent: _strReplace(
      search: "https://my-old-domain.com"
      replaceWith: "https://my-new-domain.com"
      in: $__rawContent
    )
    update(input: {
      contentAs: { html: $__adaptedRawContent }
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
        rawContent
      }
    }
  }
}
```

## Adapting content to a new post or page URL

After changing the slug of a post or page, we can convert all content to point to the new URL.

This GraphQL first retrieves the domain from the `"siteurl"` WordPress settings to recreate the page's old and new URLs:

```graphql
query ExportData(
  $oldPageSlug: String!
  $newPageSlug: String!
) {
  siteURL: optionValue(name: "siteurl")

  oldPageURL: _strAppend(
    after: $__siteURL,
    append: $oldPageSlug
  ) @export(as: "oldPageURL")

  newPageURL: _strAppend(
    after: $__siteURL,
    append: $newPageSlug
  ) @export(as: "newPageURL")
}

mutation ReplaceOldWithNewURLInPosts
  @depends(on: "ExportData")
{
  posts(
    filter: {
      search: $oldPageURL
    },
    sort: { by: ID, order: ASC }
  ) {
    id
    rawContent
    adaptedRawContent: _strReplace(
      search: $oldPageURL
      replaceWith: $newPageURL
      in: $__rawContent
    )
    update(input: {
      contentAs: { html: $__adaptedRawContent }
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
        rawContent
      }
    }
  }
}
```

We then provide the old and new page slugs via the `variables` dictionary:

```json
{
  "oldPageSlug": "/privacy/",
  "newPageSlug": "/user-privacy/"
}
```
