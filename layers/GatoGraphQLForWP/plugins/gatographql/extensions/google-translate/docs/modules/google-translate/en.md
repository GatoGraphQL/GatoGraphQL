# Google Translate

Directive `@strTranslate` to translate a field value to over 190 languages using the Google Translate API.

## Description

Add directive `@strTranslate` to any field of type `String`, to translate it to the desired language.

For instance, this query translates the post's `title` and `excerpt` fields from English to French:

```graphql
{
  posts {
    enTitle: title
    frTitle: title @strTranslate(from: "en", to: "fr")

    enExcerpt: excerpt    
    frExcerpt: excerpt @strTranslate(from: "en", to: "fr")
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
        "enExcerpt": "Our privacy policy is at https://gatographql-pro.lndo.site/privacy/, and we are based in Carimano.",
        "frExcerpt": "Notre politique de confidentialité se trouve sur https://gatographql-pro.lndo.site/privacy/, et nous sommes basés à Carimano."
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

## List of languages

You can translate your content to any of the <a href="https://cloud.google.com/translate/docs/languages" target="_blank">following languages</a>:

| Code | Language |
| --- | --- |
| `ab` | Abkhaz |
| `ace` | Acehnese |
| `ach` | Acholi |
| `af` | Afrikaans |
| `sq` | Albanian |
| `alz` | Alur |
| `am` | Amharic |
| `ar` | Arabic |
| `hy` | Armenian |
| `as` | Assamese |
| `awa` | Awadhi |
| `ay` | Aymara |
| `az` | Azerbaijani |
| `ban` | Balinese |
| `bm` | Bambara |
| `ba` | Bashkir |
| `eu` | Basque |
| `btx` | Batak Karo |
| `bts` | Batak Simalungun |
| `bbc` | Batak Toba |
| `be` | Belarusian |
| `bem` | Bemba |
| `bn` | Bengali |
| `bew` | Betawi |
| `bho` | Bhojpuri |
| `bik` | Bikol |
| `bs` | Bosnian |
| `br` | Breton |
| `bg` | Bulgarian |
| `bua` | Buryat |
| `yue` | Cantonese |
| `ca` | Catalan |
| `ceb` | Cebuano |
| `ny` | Chichewa (Nyanja) |
| `zh` | Chinese (Simplified) |
| `zh-CN` | Chinese (Simplified) |
| `zh-TW` | Chinese (Traditional) |
| `cv` | Chuvash |
| `co` | Corsican |
| `crh` | Crimean Tatar |
| `hr` | Croatian |
| `cs` | Czech |
| `da` | Danish |
| `din` | Dinka |
| `dv` | Divehi |
| `doi` | Dogri |
| `dov` | Dombe |
| `nl` | Dutch |
| `dz` | Dzongkha |
| `en` | English |
| `eo` | Esperanto |
| `et` | Estonian |
| `ee` | Ewe |
| `fj` | Fijian |
| `fil` | Filipino (Tagalog) |
| `tl` | Filipino (Tagalog) |
| `fi` | Finnish |
| `fr` | French |
| `fr-FR` | French (French) |
| `fr-CA` | French (Canadian) |
| `fy` | Frisian |
| `ff` | Fulfulde |
| `gaa` | Ga |
| `gl` | Galician |
| `lg` | Ganda (Luganda) |
| `ka` | Georgian |
| `de` | German |
| `el` | Greek |
| `gn` | Guarani |
| `gu` | Gujarati |
| `ht` | Haitian Creole |
| `cnh` | Hakha Chin |
| `ha` | Hausa |
| `haw` | Hawaiian |
| `iw` | Hebrew |
| `he` | Hebrew |
| `hil` | Hiligaynon |
| `hi` | Hindi |
| `hmn` | Hmong |
| `hu` | Hungarian |
| `hrx` | Hunsrik |
| `is` | Icelandic |
| `ig` | Igbo |
| `ilo` | Iloko |
| `id` | Indonesian |
| `ga` | Irish |
| `it` | Italian |
| `ja` | Japanese |
| `jw` | Javanese |
| `jv` | Javanese |
| `kn` | Kannada |
| `pam` | Kapampangan |
| `kk` | Kazakh |
| `km` | Khmer |
| `cgg` | Kiga |
| `rw` | Kinyarwanda |
| `ktu` | Kituba |
| `gom` | Konkani |
| `ko` | Korean |
| `kri` | Krio |
| `ku` | Kurdish (Kurmanji) |
| `ckb` | Kurdish (Sorani) |
| `ky` | Kyrgyz |
| `lo` | Lao |
| `ltg` | Latgalian |
| `la` | Latin |
| `lv` | Latvian |
| `lij` | Ligurian |
| `li` | Limburgan |
| `ln` | Lingala |
| `lt` | Lithuanian |
| `lmo` | Lombard |
| `luo` | Luo |
| `lb` | Luxembourgish |
| `mk` | Macedonian |
| `mai` | Maithili |
| `mak` | Makassar |
| `mg` | Malagasy |
| `ms` | Malay |
| `ms-Arab` | Malay (Jawi) |
| `ml` | Malayalam |
| `mt` | Maltese |
| `mi` | Maori |
| `mr` | Marathi |
| `chm` | Meadow Mari |
| `mni-Mtei` | Meiteilon (Manipuri) |
| `min` | Minang |
| `lus` | Mizo |
| `mn` | Mongolian |
| `my` | Myanmar (Burmese) |
| `nr` | Ndebele (South) |
| `new` | Nepalbhasa (Newari) |
| `ne` | Nepali |
| `nso` | Northern Sotho (Sepedi) |
| `no` | Norwegian |
| `nus` | Nuer |
| `oc` | Occitan |
| `or` | Odia (Oriya) |
| `om` | Oromo |
| `pag` | Pangasinan |
| `pap` | Papiamento |
| `ps` | Pashto |
| `fa` | Persian |
| `pl` | Polish |
| `pt` | Portuguese |
| `pt-PT` | Portuguese (Portugal) |
| `pt-BR` | Portuguese (Brazil) |
| `pa` | Punjabi |
| `pa-Arab` | Punjabi (Shahmukhi) |
| `qu` | Quechua |
| `rom` | Romani |
| `ro` | Romanian |
| `rn` | Rundi |
| `ru` | Russian |
| `sm` | Samoan |
| `sg` | Sango |
| `sa` | Sanskrit |
| `gd` | Scots Gaelic |
| `sr` | Serbian |
| `st` | Sesotho |
| `crs` | Seychellois Creole |
| `shn` | Shan |
| `sn` | Shona |
| `scn` | Sicilian |
| `szl` | Silesian |
| `sd` | Sindhi |
| `si` | Sinhala (Sinhalese) |
| `sk` | Slovak |
| `sl` | Slovenian |
| `so` | Somali |
| `es` | Spanish |
| `su` | Sundanese |
| `sw` | Swahili |
| `ss` | Swati |
| `sv` | Swedish |
| `tg` | Tajik |
| `ta` | Tamil |
| `tt` | Tatar |
| `te` | Telugu |
| `tet` | Tetum |
| `th` | Thai |
| `ti` | Tigrinya |
| `ts` | Tsonga |
| `tn` | Tswana |
| `tr` | Turkish |
| `tk` | Turkmen |
| `ak` | Twi (Akan) |
| `uk` | Ukrainian |
| `ur` | Urdu |
| `ug` | Uyghur |
| `uz` | Uzbek |
| `vi` | Vietnamese |
| `cy` | Welsh |
| `xh` | Xhosa |
| `yi` | Yiddish |
| `yo` | Yoruba |
| `yua` | Yucatec Maya |
| `zu` | Zulu |
