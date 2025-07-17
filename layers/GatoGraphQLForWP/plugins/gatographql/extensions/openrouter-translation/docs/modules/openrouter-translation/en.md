# OpenRouter Translation

Use OpenRouter as a translation provider into directive `@strTranslate`, to translate a field value to your desired language.

## Description

Make OpenRouter's API available as a translation provider in directive `@strTranslate`.

Add directive `@strTranslate` to any field of type `String`, to translate it to the desired language.

For instance, this query translates the post's `title` and `content` fields from English to French using the **OpenRouter API**:

```graphql
{
  posts {
    title @strTranslate(
      from: "en",
      to: "fr",
      provider: openrouter
    )
    
    content @strTranslate(
      from: "en",
      to: "fr",
      provider: openrouter
    )
  }
}
```
