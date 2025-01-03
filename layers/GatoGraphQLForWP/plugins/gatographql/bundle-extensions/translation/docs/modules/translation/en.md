# Translation

Add a directive `@strTranslate` to translate a field value using your selected service provider, among:

- <a href="https://openai.com/api/" target="_blank">ChatGPT API</a>
- <a href="https://www.deepl.com/en/products/api" target="_blank">DeepL API</a>
- <a href="https://cloud.google.com/translate" target="_blank">Google Translate API</a>

<!-- [Watch “How to use the Translation extension” on YouTube](https://www.youtube.com/watch?v=@todo) -->

---

Use directive `@strTranslate` to translate the field values in your GraphQL query, passing argument `provider` with the name of the provider to use:

- `chatgpt`
- `deepl`
- `google_translate`

For instance, this query translates the post's `title` and `excerpt` fields from English to French using **DeepL**:

```graphql
{
  posts {
    enTitle: title
    frTitle: title @strTranslate(from: "en", to: "fr", provider: deepl)

    enExcerpt: excerpt    
    frExcerpt: excerpt @strTranslate(from: "en", to: "fr", provider: deepl)
  }
}
```

...producing:

```json
{
  "data": {
    "posts": [
      {
        "enTitle": "Welcome to a single post full of blocks!",
        "frTitle": "Bienvenue dans un poste unique plein de blocs !",
        "enExcerpt": "When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds. Life is a gift, life is happiness, every&hellip;",
        "frExcerpt": "Quand je repense à mon passé et que je pense au temps que j'ai perdu pour rien, au temps perdu en futilités, en erreurs, en paresse, en incapacité de vivre ; combien je l'ai peu apprécié, combien de fois j'ai péché contre mon cœur et mon âme, alors mon cœur saigne. La vie est un cadeau, la vie est un bonheur, chaque&hellip;"
      },
      {
        "enTitle": "Explaining the privacy policy",
        "frTitle": "Expliquer la politique de confidentialité",
        "enExcerpt": "Our privacy policy is at https://gato-graphql-pro.lndo.site/privacy/, and we are based in Carimano.",
        "frExcerpt": "Notre politique de confidentialité se trouve sur https://gato-graphql-pro.lndo.site/privacy/, et nous sommes basés à Carimano."
      },
      {
        "enTitle": "HTTP caching improves performance",
        "frTitle": "La mise en cache HTTP améliore les performances",
        "enExcerpt": "Categories Block Latest Posts Block Did you know? We are not rich by what we possess but by what we can do without. Patience is the strength of the weak, impatience is the weakness of the strong.",
        "frExcerpt": "Catégories Bloquer les derniers messages Bloquer Le saviez-vous ? Nous ne sommes pas riches de ce que nous possédons mais de ce dont nous pouvons nous passer. La patience est la force du faible, l'impatience est la faiblesse du fort."
      }
    ]
  }
}
```
