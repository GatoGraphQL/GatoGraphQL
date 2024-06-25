# Multilingual Press

Integration with the [Multilingual Press](https://wordpress.org/plugins/multilingualpress/) plugin.

The GraphQL schema is provided the fields to retrieve multilingual data.

## Types `Root`/`QueryRoot`

Query the site metadata configured in Multilingual Press.

| Field | Description |
| --- | --- |
| `multilingualpressDefaultLanguage` | Default language on Multilingual Press, or `null` if there are no languages enabled. |
| `multilingualpressEnabledLanguages` | Enabled languages on Multilingual Press. |

Running this query:

```graphql
{
  multilingualpressDefaultLanguage
  multilingualpressEnabledLanguages
}
```

...might produce:

```json
{
  "data": {
    "multilingualpressDefaultLanguage": "en",
    "multilingualpressEnabledLanguages": [
      "en",
      "es",
      "fr"
    ]
  }
}
```

## Types `Post`, `Page`, `PostTag`, `PostCategory` and `Media`

Query the language for the entity, and the IDs for the translations for that entity.

These types implement interface `MultilingualPressTranslatable`. (Type `Media` does only when media support is enabled, via the Multilingual Press settings.)

| Field | Description |
| --- | --- |
| `multilingualpressLanguage` | Language code of the post or page, or `null` if no language was assigned (eg: Multilingual Press was installed later on). |
| `multilingualpressTranslationLanguageIDs` | Nodes for all the translation languages for the entity, as a JSON object with the language code as key and entity ID as value, or `null` if no language was assigned (eg: Multilingual Press was installed later on). |

Field `multilingualpressTranslationLanguageIDs` provides the post/page IDs for all the translations. It accepts field `includeSelf`, to indicate if to include the queried entity's ID in the results (it's `false` by default).

Running this query:

```graphql
{
  posts {
    __typename
    id
    title
    multilingualpressLanguage
    multilingualpressTranslationLanguageIDs
    multilingualpressTranslationLanguageIDsWithSelf: multilingualpressTranslationLanguageIDs(filter: { includeSelf: true })

    categories {
      __typename
      id
      name
      multilingualpressLanguage
      multilingualpressTranslationLanguageIDs
      multilingualpressTranslationLanguageIDsWithSelf: multilingualpressTranslationLanguageIDs(filter: { includeSelf: true })
    }
    
    tags {
      __typename
      id
      name
      multilingualpressLanguage
      multilingualpressTranslationLanguageIDs
      multilingualpressTranslationLanguageIDsWithSelf: multilingualpressTranslationLanguageIDs(filter: { includeSelf: true })
    }
  }

  pages {
    __typename
    id
    title
    multilingualpressLanguage
    multilingualpressTranslationLanguageIDs
    multilingualpressTranslationLanguageIDsWithSelf: multilingualpressTranslationLanguageIDs(filter: { includeSelf: true })
  }

  mediaItems {
    __typename
    id
    title
    multilingualpressLanguage
    multilingualpressTranslationLanguageIDs
    multilingualpressTranslationLanguageIDsWithSelf: multilingualpressTranslationLanguageIDs(filter: { includeSelf: true })
  }
}
```

...might produce:

```json
{
  "data": {
    "posts": [
      {
        "__typename": "Post",
        "id": 1668,
        "title": "Some post translated using Multilingual Press",
        "multilingualpressLanguage": "en",
        "multilingualpressTranslationLanguageIDs": {
          "fr": 1670,
          "es": 1672
        },
        "multilingualpressTranslationLanguageIDsWithSelf": {
          "en": 1668,
          "fr": 1670,
          "es": 1672
        },
        "categories": [
          {
            "__typename": "PostCategory",
            "id": 61,
            "name": "Category for Multilingual Press",
            "multilingualpressLanguage": "en",
            "multilingualpressTranslationLanguageIDs": {
              "fr": 63,
              "es": 65
            },
            "multilingualpressTranslationLanguageIDsWithSelf": {
              "en": 61,
              "fr": 63,
              "es": 65
            }
          }
        ],
        "tags": [
          {
            "__typename": "PostTag",
            "id": 67,
            "name": "Tag for Multilingual Press",
            "multilingualpressLanguage": "en",
            "multilingualpressTranslationLanguageIDs": {
              "fr": 69,
              "es": 71
            },
            "multilingualpressTranslationLanguageIDsWithSelf": {
              "en": 67,
              "fr": 69,
              "es": 71
            }
          }
        ]
      }
    ],
    "pages": [
      {
        "__typename": "Page",
        "id": 1674,
        "title": "Some page translated using Multilingual Press",
        "multilingualpressLanguage": "en",
        "multilingualpressTranslationLanguageIDs": {
          "fr": 1676,
          "es": 1678
        },
        "multilingualpressTranslationLanguageIDsWithSelf": {
          "en": 1674,
          "fr": 1676,
          "es": 1678
        }
      }
    ],
    "mediaItems": [
      {
        "__typename": "Media",
        "id": 40,
        "title": "Media-for-MultilingualPress",
        "multilingualpressLanguage": "en",
        "multilingualpressTranslationLanguageIDs": {
          "fr": 42,
          "es": 44
        },
        "multilingualpressTranslationLanguageIDsWithSelf": {
          "en": 40,
          "fr": 42,
          "es": 44
        }
      }
    ]
  }
}
```

## Types `GenericCustomPost`, `GenericTag` and `GenericCategory`

These types implement interface `MultilingualPressMaybeTranslatable`.

`GenericCustomPost` is a type used to represent any custom post installed on the site, such as `Portfolio`, `Event`, `Product`, or other. Similarly, `GenericTag` and `GenericCategory` are used to represent their taxonomies.

Each of these CPTs and taxonomies can be defined to be translatable on the Multilingual Press settings. Fields `multilingualpressLanguage` and `multilingualpressTranslationLanguageIDs` will then have the same behavior as for `Post` and the others (described above), and also return `null` if the entity's CPT or taxonomy is not configured to be translated.

In addition, field `multilingualpressIsTranslatable` indicates if the CPT or taxonomy is configured to be translatable.

| Field | Description |
| --- | --- |
| `multilingualpressLanguage` | Language code of the post or page, or `null` if no language was assigned (eg: Multilingual Press was installed later on), or if the entity is not configured to be translated (via Multilingual Press Settings). |
| `multilingualpressTranslationLanguageIDs` | Nodes for all the translation languages for the entity, as a JSON object with the language code as key and entity ID as value, or `null` if no language was assigned (eg: Multilingual Press was installed later on), or if the entity is not configured to be translated (via Multilingual Press Settings). |
| `multilingualpressIsTranslatable` | Indicate if the entity can be translated. |

Running this query:

```graphql
{
  customPosts(filter: { customPostTypes: ["some-cpt", "another-cpt"] }) {
    __typename
    ...on GenericCustomPost {
      id
      title
      customPostType
      multilingualpressIsTranslatable
      multilingualpressLanguage
      multilingualpressTranslationLanguageIDs
      multilingualpressTranslationLanguageIDsWithSelf: multilingualpressTranslationLanguageIDs(filter: { includeSelf: true })
      
      categories(taxonomy: "some-category") {
        __typename
        ...on GenericCategory {
          id
          name
          multilingualpressIsTranslatable
          multilingualpressLanguage
          multilingualpressTranslationLanguageIDs
          multilingualpressTranslationLanguageIDsWithSelf: multilingualpressTranslationLanguageIDs(filter: { includeSelf: true })
        }
      }
      
      tags(taxonomy: "some-tag") {
        __typename
        ...on GenericTag {
          id
          name
          multilingualpressIsTranslatable
          multilingualpressLanguage
          multilingualpressTranslationLanguageIDs
          multilingualpressTranslationLanguageIDsWithSelf: multilingualpressTranslationLanguageIDs(filter: { includeSelf: true })
        }
      }
    }
  }
}
```

...might produce:

```json
{
  "data": {
    "customPosts": [
      {
        "__typename": "GenericCustomPost",
        "id": 10,
        "title": "Some CPT that has Multilingual Press translation enabled",
        "customPostType": "some-cpt",
        "multilingualpressIsTranslatable": true,
        "multilingualpressLanguage": "en",
        "multilingualpressTranslationLanguageIDs": {
          "fr": 12,
          "es": 14
        },
        "multilingualpressTranslationLanguageIDsWithSelf": {
          "en": 10,
          "fr": 12,
          "es": 14
        },
        "categories": [
          {
            "__typename": "GenericCategory",
            "id": 30,
            "name": "Some Category for Multilingual Press",
            "multilingualpressIsTranslatable": true,
            "multilingualpressLanguage": "en",
            "multilingualpressTranslationLanguageIDs": {
              "fr": 32,
              "es": 34
            },
            "multilingualpressTranslationLanguageIDsWithSelf": {
              "en": 30,
              "fr": 32,
              "es": 34
            }
          }
        ],
        "tags": [
          {
            "__typename": "GenericTag",
            "id": 50,
            "name": "Some Tag for Multilingual Press",
            "multilingualpressIsTranslatable": true,
            "multilingualpressLanguage": "en",
            "multilingualpressTranslationLanguageIDs": {
              "fr": 52,
              "es": 54
            },
            "multilingualpressTranslationLanguageIDsWithSelf": {
              "en": 50,
              "fr": 52,
              "es": 54
            }
          }
        ]
      },
      {
        "__typename": "GenericCustomPost",
        "id": 20,
        "title": "Another CPT that does not have Multilingual Press translation enabled",
        "customPostType": "another-cpt",
        "multilingualpressIsTranslatable": false,
        "multilingualpressLanguage": null,
        "multilingualpressTranslationLanguageIDs": null,
        "multilingualpressTranslationLanguageIDsWithSelf": null,
        "categories": [
          {
            "__typename": "GenericCategory",
            "id": 70,
            "name": "Category without support for Multilingual Press",
            "multilingualpressIsTranslatable": false,
            "multilingualpressLanguage": null,
            "multilingualpressTranslationLanguageIDs": null,
            "multilingualpressTranslationLanguageIDsWithSelf": null
          }
        ],
        "tags": [
          {
            "__typename": "GenericTag",
            "id": 72,
            "name": "Tag without support for Multilingual Press",
            "multilingualpressIsTranslatable": false,
            "multilingualpressLanguage": null,
            "multilingualpressTranslationLanguageIDs": null,
            "multilingualpressTranslationLanguageIDsWithSelf": null
          }
        ]
      }
    ]
  }
}
```
