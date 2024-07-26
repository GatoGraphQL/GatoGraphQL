# Release Notes: 4.1

## Improvements

- Send the referer on Guzzle requests ([#2754](https://github.com/GatoGraphQL/GatoGraphQL/pull/2754))
- Use `@strQuoteRegex` in predefined persisted queries ([#2758](https://github.com/GatoGraphQL/GatoGraphQL/pull/2758))

## [PRO] Improvements

- Use enums types to return Polylang language codes, locales and names
- Automation: Handle `new` and `auto-draft` old status in `{$old_status}_to_{$new_status}` hook
- Predefined automation rules: In addition to `draft_to_publish`, also trigger from `new`, `auto-draft`, `pending`, `future`, and `private` states
- Added field `_strQuoteRegex` and directive `@strQuoteRegex`

### Polylang: Filter data by language

With the **Polylang** extension, we can now filter data by language.

We can provide the language to filter by when fetching data for:

- Posts
- Pages
- Custom posts
- Categories
- Tags
- Media items

The corresponding fields receive input `polylangLanguage`, and we can filter by code or locale, and by 1 or more than 1 language.

For instance, passing `$languageCodes: ["es"]` will fetch data in Spanish:

```graphql
query FilterByLanguage($languageCodes: [String!])
{
  posts(filter: {
    polylangLanguages: { codes: $languageCodes }
  }) {
    id
    title
  }

  pages(filter: {
    polylangLanguages: { codes: $languageCodes }
  }) {
    id
    title
  }

  customPosts(filter: {
    customPostTypes: ["some-cpt"]
    polylangLanguages: { codes: $languageCodes }
  }) {
    id
    title
  }

  postCategories(filter: {
    polylangLanguages: { codes: $languageCodes }
  }) {
    id
    name
  }

  postTags(filter: {
    polylangLanguages: { codes: $languageCodes }
  }) {
    id
    name
  }

  categories(
    taxonomy: "some-category"
    filter: { polylangLanguages: { codes: $languageCodes } }
  ) {
    id
    name
  }

  tags(
    taxonomy: "some-tag"
    filter: { polylangLanguages: { codes: $languageCodes } }
  ) {
    id
    name
  }

  mediaItems(filter: {
    polylangLanguages: { codes: $languageCodes }
  }) {
    id
    title
  }
}
```
