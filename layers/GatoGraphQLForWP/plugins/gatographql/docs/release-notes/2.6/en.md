# Release Notes: 2.6

## Improvements

- Added documentation for integration with MultilingualPress ([#2699](https://github.com/GatoGraphQL/GatoGraphQL/pull/2699))
- Added GraphQL variables `$translateFromLanguage`, `$includeLanguagesToTranslate` and `$excludeLanguagesToTranslate` to persisted queries ([#2694](https://github.com/GatoGraphQL/GatoGraphQL/pull/2694) / [#2700](https://github.com/GatoGraphQL/GatoGraphQL/pull/2700)):
  - [PRO] Translate posts for Polylang (Gutenberg)
  - [PRO] Translate posts for Polylang (Classic editor)
- Added scalar type `IntValueJSONObject` to the GraphQL schema ([#2703](https://github.com/GatoGraphQL/GatoGraphQL/pull/2703))

### Added `siteURL` field ([#2697](https://github.com/GatoGraphQL/GatoGraphQL/pull/2697))

Added the following field to the GraphQL schema, via the "Site" module:

- `Root.siteURL`

For instance, executing the following query:

```graphql
{
  siteURL
}
```

...will produce:

```json
{
  "data": {
    "siteURL": "https://mysite.com"
  }
}
```

### Added fields to fetch multisite data ([#2698](https://github.com/GatoGraphQL/GatoGraphQL/pull/2698))

The GraphQL schema now supports fetching data from a WordPress multisite network, provided via the new "Multisite" module.

This module adds the following fields to the GraphQL schema:

- `Root.networkSites`
- `Root.networkSiteCount`

Field `networkSites` returns an array with all the sites in the network, each of the new `NetworkSite` type, which contains the following fields:

- `id`
- `name`
- `url`
- `locale`
- `language`

These fields are only enabled when multisite is enabled (i.e. when method `is_multisite()` returns `true`).

For instance, executing the following query:

```graphql
{
  networkSiteCount
  networkSites {
    id
    name
    url
    locale
    language
  }
}
```

might return:

```json
{
  "data": {
    "networkSiteCount": 3,
    "networkSites": [
      {
        "id": 1,
        "name": "Site in English",
        "url": "https://mymultisite.com",
        "locale": "en_US",
        "language": "en"
      },
      {
        "id": 2,
        "name": "Site in Spanish",
        "url": "https://es.mymultisite.com",
        "locale": "es_AR",
        "language": "es"
      },
      {
        "id": 3,
        "name": "Site in French",
        "url": "https://fr.mymultisite.com",
        "locale": "fr_FR",
        "language": "fr"
      }
    ]
  }
}
```

## [PRO] Improvements

- [PRO] Added input `valueWhenNonExistingKeyOrPath` to field `_objectProperty`

### Added integration with MultilingualPress

Gato GraphQL PRO now has an integration with the [MultilingualPress](https://multilingualpress.org/) plugin.

The GraphQL schema is provided the fields to retrieve multilingual data.

#### Types `Post`, `Page`, `PostTag` and `PostCategory`

Query the language for the entity, and the IDs for the translations for that entity.

These types implement interface `MultilingualPressTranslatable`.

| Field | Description |
| --- | --- |
| `multilingualpressTranslationSiteRelationshipIDs` | Nodes for all the translation relationships for the entity, as a JSON object with the network site ID and entity ID as value, or `null` if no relationship was assigned. |

Field `multilingualpressTranslationSiteRelationshipIDs` provides the post/page IDs for all the translations. It accepts field `includeSelf`, to indicate if to include the queried entity's ID in the results (it's `false` by default).

For instance, you can now run this query:

```graphql
{
  posts {
    __typename
    id
    title
    multilingualpressTranslationSiteRelationshipIDs
    multilingualpressTranslationSiteRelationshipIDsWithSelf: multilingualpressTranslationSiteRelationshipIDs(filter: { includeSelf: true })

    categories {
      __typename
      id
      name
      multilingualpressTranslationSiteRelationshipIDs
      multilingualpressTranslationSiteRelationshipIDsWithSelf: multilingualpressTranslationSiteRelationshipIDs(filter: { includeSelf: true })
    }
    
    tags {
      __typename
      id
      name
      multilingualpressTranslationSiteRelationshipIDs
      multilingualpressTranslationSiteRelationshipIDsWithSelf: multilingualpressTranslationSiteRelationshipIDs(filter: { includeSelf: true })
    }
  }

  pages {
    __typename
    id
    title
    multilingualpressTranslationSiteRelationshipIDs
    multilingualpressTranslationSiteRelationshipIDsWithSelf: multilingualpressTranslationSiteRelationshipIDs(filter: { includeSelf: true })
  }
}
```

#### Types `GenericCustomPost`, `GenericTag` and `GenericCategory`

These types implement interface `MultilingualPressMaybeTranslatable`.

`GenericCustomPost` is a type used to represent any custom post installed on the site, such as `Portfolio`, `Event`, `Product`, or other. Similarly, `GenericTag` and `GenericCategory` are used to represent their taxonomies.

Each of these CPTs and taxonomies can be defined to be translatable on the MultilingualPress settings. Field `multilingualpressTranslationSiteRelationshipIDs` will then have the same behavior as for `Post` and the others (described above), and also return `null` if the entity's CPT or taxonomy is not configured to be translated.

In addition, field `multilingualpressIsTranslatable` indicates if the CPT or taxonomy is configured to be translatable.

| Field | Description |
| --- | --- |
| `multilingualpressTranslationSiteRelationshipIDs` | Nodes for all the translation relationships for the entity, as a JSON object with the network site ID and entity ID as value, or `null` if no relationship was assigned, or if the entity is not configured to be translated (via MultilingualPress Settings). |
| `multilingualpressIsTranslatable` | Indicate if the entity can be translated. |

For instance, you can now run this query:

```graphql
{
  customPosts(filter: { customPostTypes: ["some-cpt", "another-cpt"] }) {
    __typename
    ...on GenericCustomPost {
      id
      title
      customPostType
      multilingualpressIsTranslatable
      multilingualpressTranslationSiteRelationshipIDs
      multilingualpressTranslationSiteRelationshipIDsWithSelf: multilingualpressTranslationSiteRelationshipIDs(filter: { includeSelf: true })
      
      categories(taxonomy: "some-category") {
        __typename
        ...on GenericCategory {
          id
          name
          multilingualpressIsTranslatable
          multilingualpressTranslationSiteRelationshipIDs
          multilingualpressTranslationSiteRelationshipIDsWithSelf: multilingualpressTranslationSiteRelationshipIDs(filter: { includeSelf: true })
        }
      }
      
      tags(taxonomy: "some-tag") {
        __typename
        ...on GenericTag {
          id
          name
          multilingualpressIsTranslatable
          multilingualpressTranslationSiteRelationshipIDs
          multilingualpressTranslationSiteRelationshipIDsWithSelf: multilingualpressTranslationSiteRelationshipIDs(filter: { includeSelf: true })
        }
      }
    }
  }
}
```
