# Release Notes: 4.0

## Breaking changes

- Updated internal PHP hook structure for error payloads ([#2739](https://github.com/GatoGraphQL/GatoGraphQL/pull/2739))

## Improvements

- Use bulk mutations in predefined persisted queries ([#2728](https://github.com/GatoGraphQL/GatoGraphQL/pull/2728))
- Added documentation for new PRO module **Polylang Mutations** ([#2733](https://github.com/GatoGraphQL/GatoGraphQL/pull/2733))
- Added documentation for new PRO field `_arrayIntersect` ([#2735](https://github.com/GatoGraphQL/GatoGraphQL/pull/2735))
- Added predefined persisted query:
  - [PRO] Create missing translation posts for Polylang ([#2740](https://github.com/GatoGraphQL/GatoGraphQL/pull/2740))
  
## [PRO] Improvements

- Added field `_arrayIntersect`

### Added Polylang Mutations

The new PRO module **Polylang Mutations** provides mutations for the integration with the [Polylang](https://wordpress.org/plugins/polylang/) plugin.

The GraphQL schema is provided with mutations to:

- Establish the language for custom posts, tags and categories, and
- Define associations among them (i.e. indicate that a set of custom posts, tags or categories is a translation for each other).

| Mutation | Description |
| --- | --- |
| `polylangSetCustomPostLanguage` | Set the language of the custom post. |
| `polylangSetTaxonomyTermLanguage` | Set the language of the taxonomy term. |
| `polylangSaveCustomPostTranslationAssociation` | Set the translation association for the custom post. |
| `polylangSaveTaxonomyTermTranslationAssociation` | Set the translation association for the taxonomy term. |

For instance, the following query defines the language for 3 posts (to English, Spanish and French), and then defines that these 3 posts are a translation of each other:

```graphql
mutation {
  post1: polylangSetCustomPostLanguage(input: {id: 1, language: "en"}) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
  post2: polylangSetCustomPostLanguage(input: {id: 2, language: "es"}) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
  post3: polylangSetCustomPostLanguage(input: {id: 3, language: "fr"}) {
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

## Fixed

- Don't replace chars in translation persisted queries ([#2731](https://github.com/GatoGraphQL/GatoGraphQL/pull/2731))
