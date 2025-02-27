# DeepSeek Translation

Use DeepSeek as a translation provider into directive `@strTranslate`, to translate a field value to your desired language.

## Description

Make DeepSeek's API available as a translation provider in directive `@strTranslate`.

Add directive `@strTranslate` to any field of type `String`, to translate it to the desired language.

For instance, this query translates the post's `title` and `content` fields from English to French using the **DeepSeek API**:

```graphql
{
  posts {
    title @strTranslate(
      from: "en",
      to: "fr",
      provider: deepseek
    )
    
    content @strTranslate(
      from: "en",
      to: "fr",
      provider: deepseek
    )
  }
}
```
