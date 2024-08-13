# Polylang

Integration with the [Polylang](https://wordpress.org/plugins/polylang/) plugin.

The GraphQL schema is provided the fields to retrieve multilingual data.

## Types `Root`/`QueryRoot`

Query the site metadata configured in Polylang.

| Field | Description |
| --- | --- |
| `polylangDefaultLanguage` | Default language on Polylang, or `null` if there are no languages enabled. |
| `polylangLanguages` | List of languages on Polylang. |

Running this query:

```graphql
{
  polylangDefaultLanguage {
    code
  }
  polylangLanguages {
    code
  }
}
```

...might produce:

```json
{
  "data": {
    "polylangDefaultLanguage": {
      "code": "en"
    },
    "polylangLanguages": [
      {
        "code": "en"
      },
      {
        "code": "es"
      },
      {
        "code": "fr"
      }
    ]
  }
}
```

## Types `Post`, `Page`, `PostTag`, `PostCategory` and `Media`

Query the language for the entity, and the IDs for the translations for that entity.

These types implement interface `PolylangTranslatable`. (Type `Media` does only when media support is enabled, via the Polylang settings.)

| Field | Description |
| --- | --- |
| `polylangLanguage` | Language of the post or page, or `null` if no language was assigned (eg: Polylang was installed later on). |
| `polylangTranslationLanguageIDs` | Nodes for all the translation languages for the entity, as a JSON object with the language code as key and entity ID as value, or `null` if no language was assigned (eg: Polylang was installed later on). |

Field `polylangTranslationLanguageIDs` provides the entity IDs for all the translations (i.e. post/page/category/tag/media). It accepts input `includeSelf`, to indicate if to include the queried entity's ID in the results (it's `false` by default), and inputs `includeLanguages` and `excludeLanguages`, to filter the included languages in the results.

Running this query:

```graphql
{
  posts {
    __typename
    id
    title
    polylangLanguage {
      code
    }
    polylangTranslationLanguageIDs
    polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })

    categories {
      __typename
      id
      name
      polylangLanguage {
        code
      }
      polylangTranslationLanguageIDs
      polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
    }
    
    tags {
      __typename
      id
      name
      polylangLanguage {
        code
      }
      polylangTranslationLanguageIDs
      polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
    }
  }

  pages {
    __typename
    id
    title
    polylangLanguage {
      code
    }
    polylangTranslationLanguageIDs
    polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
  }

  mediaItems {
    __typename
    id
    title
    polylangLanguage {
      code
    }
    polylangTranslationLanguageIDs
    polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
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
        "title": "Some post translated using Polylang",
        "polylangLanguage": {
          "code": "en"
        },
        "polylangTranslationLanguageIDs": {
          "fr": 1670,
          "es": 1672
        },
        "polylangTranslationLanguageIDsWithSelf": {
          "en": 1668,
          "fr": 1670,
          "es": 1672
        },
        "categories": [
          {
            "__typename": "PostCategory",
            "id": 61,
            "name": "Category for Polylang",
            "polylangLanguage": {
              "code": "en"
            },
            "polylangTranslationLanguageIDs": {
              "fr": 63,
              "es": 65
            },
            "polylangTranslationLanguageIDsWithSelf": {
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
            "name": "Tag for Polylang",
            "polylangLanguage": {
              "code": "en"
            },
            "polylangTranslationLanguageIDs": {
              "fr": 69,
              "es": 71
            },
            "polylangTranslationLanguageIDsWithSelf": {
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
        "title": "Some page translated using Polylang",
        "polylangLanguage": {
          "code": "en"
        },
        "polylangTranslationLanguageIDs": {
          "fr": 1676,
          "es": 1678
        },
        "polylangTranslationLanguageIDsWithSelf": {
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
        "title": "Media-for-Polylang",
        "polylangLanguage": {
          "code": "en"
        },
        "polylangTranslationLanguageIDs": {
          "fr": 42,
          "es": 44
        },
        "polylangTranslationLanguageIDsWithSelf": {
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

These types implement interface `PolylangMaybeTranslatable`.

`GenericCustomPost` is a type used to represent any custom post installed on the site, such as `Portfolio`, `Event`, `Product`, or other. Similarly, `GenericTag` and `GenericCategory` are used to represent their taxonomies.

Each of these CPTs and taxonomies can be defined to be translatable on the Polylang settings. Fields `polylangLanguage` and `polylangTranslationLanguageIDs` will then have the same behavior as for `Post` and the others (described above), and also return `null` if the entity's CPT or taxonomy is not configured to be translated.

In addition, field `polylangIsTranslatable` indicates if the CPT or taxonomy is configured to be translatable.

| Field | Description |
| --- | --- |
| `polylangLanguage` | Language of the post or page, or `null` if no language was assigned (eg: Polylang was installed later on), or if the entity is not configured to be translated (via Polylang Settings). |
| `polylangTranslationLanguageIDs` | Nodes for all the translation languages for the entity, as a JSON object with the language code as key and entity ID as value, or `null` if no language was assigned (eg: Polylang was installed later on), or if the entity is not configured to be translated (via Polylang Settings). |
| `polylangIsTranslatable` | Indicate if the entity can be translated. |

Running this query:

```graphql
{
  customPosts(filter: { customPostTypes: ["some-cpt", "another-cpt"] }) {
    __typename
    ...on GenericCustomPost {
      id
      title
      customPostType
      polylangIsTranslatable
      polylangLanguage {
        code
      }
      polylangTranslationLanguageIDs
      polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
      
      categories(taxonomy: "some-category") {
        __typename
        ...on GenericCategory {
          id
          name
          polylangIsTranslatable
          polylangLanguage {
            code
          }
          polylangTranslationLanguageIDs
          polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
        }
      }
      
      tags(taxonomy: "some-tag") {
        __typename
        ...on GenericTag {
          id
          name
          polylangIsTranslatable
          polylangLanguage {
            code
          }
          polylangTranslationLanguageIDs
          polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
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
        "title": "Some CPT that has Polylang translation enabled",
        "customPostType": "some-cpt",
        "polylangIsTranslatable": true,
        "polylangLanguage": {
          "code": "en"
        },
        "polylangTranslationLanguageIDs": {
          "fr": 12,
          "es": 14
        },
        "polylangTranslationLanguageIDsWithSelf": {
          "en": 10,
          "fr": 12,
          "es": 14
        },
        "categories": [
          {
            "__typename": "GenericCategory",
            "id": 30,
            "name": "Some Category for Polylang",
            "polylangIsTranslatable": true,
            "polylangLanguage": {
              "code": "en"
            },
            "polylangTranslationLanguageIDs": {
              "fr": 32,
              "es": 34
            },
            "polylangTranslationLanguageIDsWithSelf": {
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
            "name": "Some Tag for Polylang",
            "polylangIsTranslatable": true,
            "polylangLanguage": {
              "code": "en"
            },
            "polylangTranslationLanguageIDs": {
              "fr": 52,
              "es": 54
            },
            "polylangTranslationLanguageIDsWithSelf": {
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
        "title": "Another CPT that does not have Polylang translation enabled",
        "customPostType": "another-cpt",
        "polylangIsTranslatable": false,
        "polylangLanguage": null,
        "polylangTranslationLanguageIDs": null,
        "polylangTranslationLanguageIDsWithSelf": null,
        "categories": [
          {
            "__typename": "GenericCategory",
            "id": 70,
            "name": "Category without support for Polylang",
            "polylangIsTranslatable": false,
            "polylangLanguage": null,
            "polylangTranslationLanguageIDs": null,
            "polylangTranslationLanguageIDsWithSelf": null
          }
        ],
        "tags": [
          {
            "__typename": "GenericTag",
            "id": 72,
            "name": "Tag without support for Polylang",
            "polylangIsTranslatable": false,
            "polylangLanguage": null,
            "polylangTranslationLanguageIDs": null,
            "polylangTranslationLanguageIDsWithSelf": null
          }
        ]
      }
    ]
  }
}
```

## Mutations

The GraphQL schema is provided with mutations to:

- Establish the language for custom posts, tags and categories, and
- Define associations among them (i.e. indicate that a set of custom posts, tags or categories is a translation for each other).

| Mutation | Description |
| --- | --- |
| `polylangSetCustomPostLanguage` | Set the language of the custom post. |
| `polylangSetTaxonomyTermLanguage` | Set the language of the taxonomy term. |
| `polylangSetMediaItemLanguage` | Set the language of the media item. |
| `polylangSaveCustomPostTranslationAssociation` | Set the translation association for the custom post. |
| `polylangSaveTaxonomyTermTranslationAssociation` | Set the translation association for the taxonomy term. |
| `polylangSaveMediaItemTranslationAssociation` | Set the translation association for the media item. |

For instance, the following query defines the language for 3 posts (to English, Spanish and French), and then defines that these 3 posts are a translation of each other:

```graphql
mutation {
  post1: polylangSetCustomPostLanguage(input: {id: 1, languageBy: { code: "en" }}) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
  post2: polylangSetCustomPostLanguage(input: {id: 2, languageBy: { code: "es" }}) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
  post3: polylangSetCustomPostLanguage(input: {id: 3, languageBy: { code: "fr" }}) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
  polylangSaveCustomPostTranslationAssociation(input: {
    ids: [1, 2, 3]
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
}
```

## Filter data by language

We can provide the language to filter by when fetching data for:

- Posts
- Pages
- Custom posts
- Categories
- Tags
- Media items

The corresponding fields receive input `polylangLanguageBy`, and we can filter by code or locale, and by 1 or more than 1 language.

For instance, passing `$languageCodes: ["es"]` will fetch data in Spanish:

```graphql
query FilterByLanguage($languageCodes: [String!])
{
  posts(filter: {
    polylangLanguagesBy: { codes: $languageCodes }
  }) {
    id
    title
  }

  pages(filter: {
    polylangLanguagesBy: { codes: $languageCodes }
  }) {
    id
    title
  }

  customPosts(filter: {
    customPostTypes: ["some-cpt"]
    polylangLanguagesBy: { codes: $languageCodes }
  }) {
    id
    title
  }

  postCategories(filter: {
    polylangLanguagesBy: { codes: $languageCodes }
  }) {
    id
    name
  }

  postTags(filter: {
    polylangLanguagesBy: { codes: $languageCodes }
  }) {
    id
    name
  }

  categories(
    taxonomy: "some-category"
    filter: { polylangLanguagesBy: { codes: $languageCodes } }
  ) {
    id
    name
  }

  tags(
    taxonomy: "some-tag"
    filter: { polylangLanguagesBy: { codes: $languageCodes } }
  ) {
    id
    name
  }

  mediaItems(filter: {
    polylangLanguagesBy: { codes: $languageCodes }
  }) {
    id
    title
  }
}
```
