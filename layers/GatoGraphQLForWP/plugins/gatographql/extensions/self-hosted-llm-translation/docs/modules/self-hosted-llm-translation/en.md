# Self-Hosted LLM Translation

Inject a self-hosted LLM (eg: via <a href="https://ollama.com/" target="_blank" rel="nofollow noreferrer noopener">Ollama</a>) as a translation provider into directive `@strTranslate`, to translate a field value to any desired language.

## Description

Make a self-hosted LLM available as a translation provider in directive `@strTranslate`.

Add directive `@strTranslate` to any field of type `String`, to translate it to the desired language.

For instance, this query translates the post's `title` and `content` fields from English to French using your **self-hosted LLM**:

```graphql
{
  posts {
    title @strTranslate(
      from: "en",
      to: "fr",
      provider: self_hosted_llm
    )
    
    content @strTranslate(
      from: "en",
      to: "fr",
      provider: self_hosted_llm
    )
  }
}
```
