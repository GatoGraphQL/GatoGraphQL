# DeepL

Inject the DeepL API as a translation provider into directive `@strTranslate`, to translate a field value to over 30 languages.

## Description

Make DeepL's API available as a translation provider in directive `@strTranslate`.

Add directive `@strTranslate` to any field of type `String`, to translate it to the desired language.

For instance, this query translates the post's `title` and `excerpt` fields from English to French using the **DeepL API**:

```graphql
{
  posts {
    title @strTranslate(
      from: "en",
      to: "fr",
      provider: deepl
    )
    
    content @strTranslate(
      from: "en",
      to: "fr",
      provider: deepl
    )
  }
}
```

## List of languages

You can translate your content to any of the <a href="https://developers.deepl.com/docs/resources/supported-languages" target="_blank">following languages</a>:

| Code | Language |
| --- | --- |
| `AR` | Arabic |
| `BG` | Bulgarian |
| `CS` | Czech |
| `DA` | Danish |
| `DE` | German |
| `EL` | Greek |
| `EN` | English (all English variants) |
| `ES` | Spanish |
| `ET` | Estonian |
| `FI` | Finnish |
| `FR` | French |
| `HU` | Hungarian |
| `ID` | Indonesian |
| `IT` | Italian |
| `JA` | Japanese |
| `KO` | Korean |
| `LT` | Lithuanian |
| `LV` | Latvian |
| `NB` | Norwegian Bokmål |
| `NL` | Dutch |
| `PL` | Polish |
| `PT` | Portuguese (all Portuguese variants) |
| `RO` | Romanian |
| `RU` | Russian |
| `SK` | Slovak |
| `SL` | Slovenian |
| `SV` | Swedish |
| `TR` | Turkish |
| `UK` | Ukrainian |
| `ZH` | Chinese (all Chinese variants) |
