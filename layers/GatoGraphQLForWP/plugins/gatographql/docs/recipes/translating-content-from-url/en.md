# Translating content from URL

We can combine the fields to execute HTTP requests (provided by the [**HTTP Client**](https://gatographql/extensions/http-client/) extension) with directive `@strTranslate` (provided by the [**Google Translate**](https://gatographql.com/extensions/google-translate/) extension), to translate the content from any URL.

## GraphQL query to translate content from a URL

This query, given a URL as input, its language, and what language to translate it to, fetches the content from the URL and performs the translation using Google Translate:

```graphql
query TranslateContent(
  $url: URL!
  $fromLang: String!
  $toLang: String!
) {
  _sendHTTPRequest(input:{
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
