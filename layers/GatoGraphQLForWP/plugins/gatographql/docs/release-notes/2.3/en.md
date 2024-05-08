# Release Notes: 2.3

## Improvements

- Added documentation for integration with Polylang ([#2664](https://github.com/GatoGraphQL/GatoGraphQL/pull/2664))
- Added module type "Integrations" ([#2665](https://github.com/GatoGraphQL/GatoGraphQL/pull/2665))
- Return an EnumString type on `GenericCategory.taxonomy` and `GenericTag.taxonomy` ([#2666](https://github.com/GatoGraphQL/GatoGraphQL/pull/2666))
- Added predefined persisted queries:
  - [PRO] Translate posts for Polylang (Gutenberg) ([#2667](https://github.com/GatoGraphQL/GatoGraphQL/pull/2667))
  - [PRO] Translate posts for Polylang (Classic editor) ([#2667](https://github.com/GatoGraphQL/GatoGraphQL/pull/2667))
  - [PRO] Sync featured image for Polylang ([#2669](https://github.com/GatoGraphQL/GatoGraphQL/pull/2669))
  - [PRO] Sync tags and categories for Polylang ([#2670](https://github.com/GatoGraphQL/GatoGraphQL/pull/2670))

### Added fields `GenericCustomPost.update`, `Root.updateCustomPost` and `Root.createCustomPost` ([#2663](https://github.com/GatoGraphQL/GatoGraphQL/pull/2663))

We have added mutations to create an update custom posts!

For instance, this query updates the title and content for a Custom Post Type `"my-portfolio"`:

```graphql
mutation UpdateCustomPost {
  updateCustomPost(input: {
    id: 1616
    customPostType: "my-portfolio"
    title: "Updated title"
    contentAs: { html: "Updated content" }
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    customPost {
      __typename
      ...on CustomPost {
        id
        title
        content
      }
    }
  }
}
```

Concerning Custom Post Types (CPT) of your own creation, and which do not require any additional fields over those from a Post, then you can use both `createCustomPost` and `updateCustomPost` without any fear or restraint. For instance, a `MyPortfolio` CPT that simply uses the standard fields `title` and `content`, and has no extra fields, can be fully managed via these new fields.

Custom post types that are provided by 3rd-party plugins, though, may need to be created (and possibly updated too) by the corresponding plugin only. This is because they may have their custom data (either in `wp_postmeta` or in a proprietary table) that needs to be added too, and that Gato GraphQL is unaware of.

To manage these CPTs appropriately, a corresponding integration between that plugin and Gato GraphQL should be created, which would provide the mapping for all the fields for the CPT.

For instance, to work with WooCommerce, we can currently use field `Root.updateCustomPost` to translate and update the title and content of an WooCommerce product (i.e. from the Product CPT).

However, we cannot create an WooCommerce product. For that, we must wait until the "WooCommerce for Gato GraphQL" extension is available.

## Support alternative filenames from 3rd-party plugins for extensions ([#2671](https://github.com/GatoGraphQL/GatoGraphQL/pull/2671))

Gato GraphQL extensions with 3rd-party plugins now support alternative filenames from the plugin, where any of them being active will make the extension be enabled.

For instance, the Polylang extension in Gato GraphQL PRO will be enabled if either `polylang/polylang.php` or `polylang-pro/polylang.php` is active.

## [PRO] Improvements

- Added automation rules:
  - Polylang: When publishing a post, translate it to all languages (Gutenberg)
  - Polylang: When publishing a post, translate it to all languages (Classic editor)
  - Polylang: When publishing a post, set the featured image for each language on all translation posts
  - Polylang: When publishing a post, set the tags and categories for each language on all translation posts

### Added integration with Polylang

Gato GraphQL PRO now has an integration with the [Polylang](https://wordpress.org/plugins/polylang/) plugin.

The GraphQL schema is provided the fields to retrieve multilingual data.

#### Types `Root`/`QueryRoot`

Query the site metadata configured in Polylang.

| Field | Description |
| --- | --- |
| `polylangDefaultLanguage` | Default language on Polylang, or `null` if there are no languages enabled. |
| `polylangEnabledLanguages` | Enabled languages on Polylang. |

Running this query:

```graphql
{
  polylangDefaultLanguage
  polylangEnabledLanguages
}
```

...might produce:

```json
{
  "data": {
    "polylangDefaultLanguage": "en",
    "polylangEnabledLanguages": [
      "en",
      "es",
      "fr"
    ]
  }
}
```

#### Types `Post`, `Page`, `PostTag`, `PostCategory` and `Media`

Query the language for the entity, and the IDs for the translations for that entity.

These types implement interface `PolylangTranslatable`. (Type `Media` does only when media support is enabled, via the Polylang settings.)

| Field | Description |
| --- | --- |
| `polylangLanguage` | Language code of the post or page, or `null` if no language was assigned (eg: Polylang was installed later on). |
| `polylangTranslationLanguageIDs` | Nodes for all the translation languages for the entity, as a JSON object with the language code as key and entity ID as value, or `null` if no language was assigned (eg: Polylang was installed later on). |

Field `polylangTranslationLanguageIDs` provides the post/page IDs for all the translations. It accepts field `includeSelf`, to indicate if to include the queried entity's ID in the results (it's `false` by default).

Running this query:

```graphql
{
  posts {
    __typename
    id
    title
    polylangLanguage
    polylangTranslationLanguageIDs
    polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })

    categories {
      __typename
      id
      name
      polylangLanguage
      polylangTranslationLanguageIDs
      polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
    }
    
    tags {
      __typename
      id
      name
      polylangLanguage
      polylangTranslationLanguageIDs
      polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
    }
  }

  pages {
    __typename
    id
    title
    polylangLanguage
    polylangTranslationLanguageIDs
    polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
  }

  mediaItems {
    __typename
    id
    title
    polylangLanguage
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
        "polylangLanguage": "en",
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
            "polylangLanguage": "en",
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
            "polylangLanguage": "en",
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
        "polylangLanguage": "en",
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
        "polylangLanguage": "en",
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

#### Types `GenericCustomPost`, `GenericTag` and `GenericCategory`

These types implement interface `PolylangMaybeTranslatable`.

`GenericCustomPost` is a type used to represent any custom post installed on the site, such as `Portfolio`, `Event`, `Product`, or other. Similarly, `GenericTag` and `GenericCategory` are used to represent their taxonomies.

Each of these CPTs and taxonomies can be defined to be translatable on the Polylang settings. Fields `polylangLanguage` and `polylangTranslationLanguageIDs` will then have the same behavior as for `Post` and the others (described above), and also return `null` if the entity's CPT or taxonomy is not configured to be translated.

In addition, field `polylangIsTranslatable` indicates if the CPT or taxonomy is configured to be translatable.

| Field | Description |
| --- | --- |
| `polylangLanguage` | Language code of the post or page, or `null` if no language was assigned (eg: Polylang was installed later on), or if the entity is not configured to be translated (via Polylang Settings). |
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
      polylangLanguage
      polylangTranslationLanguageIDs
      polylangTranslationLanguageIDsWithSelf: polylangTranslationLanguageIDs(filter: { includeSelf: true })
      
      categories(taxonomy: "some-category") {
        __typename
        ...on GenericCategory {
          id
          name
          polylangIsTranslatable
          polylangLanguage
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
          polylangLanguage
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
        "polylangLanguage": "en",
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
            "polylangLanguage": "en",
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
            "polylangLanguage": "en",
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
