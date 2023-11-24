# Release Notes: 1.2

Here's a description of all the changes.

## Added "Translating content from URL" as recipe and predefined persisted query

This query, given a URL as input, its language, and what language to translate it to, fetches the content from the URL and performs the translation using Google Translate:

```graphql
query TranslateContent(
  $url: URL!
  $fromLang: String!
  $toLang: String!
) {
  _sendHTTPRequest(input: {
    url: $url,
    method: GET
  }) {
    body
    translated: body @strTranslate(
      from: $fromLang
      to: $toLang
    )
  }
}
```

It's been added as a predefined Persisted Query, and also to the Recipes section.

## Fixed

- In predefined persisted queries "Translate post" and "Translate posts", added `failIfNonExistingKeyOrPath: false` when selecting a block's `attributes.{something}` property (as it may sometimes not be defined)
- In predefined persisted query "Import post from WordPress site", added status `any` to select the post
