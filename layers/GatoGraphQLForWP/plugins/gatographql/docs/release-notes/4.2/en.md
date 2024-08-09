# Release Notes: 4.2

## Improvements

- Validate `assign_terms` capability on `setCategory` and `setTag` mutations ([#2772](https://github.com/GatoGraphQL/GatoGraphQL/pull/2772))
- Added predefined persisted queries: ([#2774](https://github.com/GatoGraphQL/GatoGraphQL/pull/2774))
  - Create missing translation categories for Polylang
  - Create missing translation tags for Polylang
  - Translate categories for Polylang
  - Translate tags for Polylang
  - Translate categories for MultilingualPress
  - Translate tags for MultilingualPress
- Added translation language mapping to persisted queries ([#2775](https://github.com/GatoGraphQL/GatoGraphQL/pull/2775))

### Added mutations for categories ([#2764](https://github.com/GatoGraphQL/GatoGraphQL/pull/2764))

It is now possible to create, update and delete post categories, with the newly-added mutations:

- `Root.createPostCategory`
- `Root.updatePostCategory`
- `Root.deletePostCategory`
- `PostCategory.update`
- `PostCategory.delete`

And also custom categories, with the newly-added mutations:

- `Root.createCategory`
- `Root.updateCategory`
- `Root.deleteCategory`
- `GenericCategory.update`
- `GenericCategory.delete`

This query creates, updates and deletes post category terms:

```graphql
mutation CreateUpdateDeletePostCategories {
  createPostCategory(input: {
    name: "Some name"
    slug: "Some slug"
    description: "Some description"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      ...PostCategoryData
    }
  }

  updatePostCategory(input: {
    id: 1
    name: "Some updated name"
    slug: "Some updated slug"
    description: "Some updated description"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      ...PostCategoryData
    }
  }

  deletePostCategory(input: {
    id: 1
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

fragment PostCategoryData on PostCategory {
  id
  name
  slug
  description
  parent {
    id
  }
}
```

This query creates, updates and deletes category terms for a custom `some-cat-taxonomy` category:

```graphql
mutation CreateUpdateDeleteCategories {
  createCategory(input: {
    taxonomy: "some-cat-taxonomy",
    name: "Some name"
    slug: "Some slug"
    description: "Some description"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      ...CategoryData
    }
  }

  updateCategory(input: {
    id: 1
    taxonomy: "some-cat-taxonomy"
    name: "Some updated name"
    slug: "Some updated slug"
    description: "Some updated description"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      ...CategoryData
    }
  }

  deleteCategory(input: {
    id: 1
    taxonomy: "some-cat-taxonomy"
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

fragment CategoryData on Category {
  id
  name
  slug
  description
  parent {
    id
  }
}
```

### Added mutations for tags ([#2765](https://github.com/GatoGraphQL/GatoGraphQL/pull/2765))

It is now possible to create, update and delete post tags, with the newly-added mutations:

- `Root.createPostTag`
- `Root.updatePostTag`
- `Root.deletePostTag`
- `PostTag.update`
- `PostTag.delete`

And also custom tags, with the newly-added mutations:

- `Root.createTag`
- `Root.updateTag`
- `Root.deleteTag`
- `GenericTag.update`
- `GenericTag.delete`

This query creates, updates and deletes post category terms:

```graphql
mutation CreateUpdateDeletePostTags {
  createPostTag(input: {
    name: "Some name"
    slug: "Some slug"
    description: "Some description"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      ...PostTagData
    }
  }

  updatePostTag(input: {
    id: 1
    name: "Some updated name"
    slug: "Some updated slug"
    description: "Some updated description"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      ...PostTagData
    }
  }

  deletePostTag(input: {
    id: 1
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

fragment PostTagData on PostTag {
  id
  name
  slug
  description
}
```

This query creates, updates and deletes category terms for a custom `some-tag-taxonomy` category:

```graphql
mutation CreateUpdateDeleteTags {
  createTag(input: {
    taxonomy: "some-tag-taxonomy",
    name: "Some name"
    slug: "Some slug"
    description: "Some description"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      ...TagData
    }
  }

  updateTag(input: {
    id: 1
    taxonomy: "some-tag-taxonomy"
    name: "Some updated name"
    slug: "Some updated slug"
    description: "Some updated description"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      ...TagData
    }
  }

  deleteTag(input: {
    id: 1
    taxonomy: "some-tag-taxonomy"
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

fragment TagData on Tag {
  id
  name
  slug
  description
}
```

## [PRO] Improvements

### Define the Polylang language on tag and category mutations

With the **Polylang integration**, when creating a tag or category (see above), we can pass `polylangLanguageBy` input to already define its language.

For instance, this query creates a post category, and defines its language as Spanish:

```graphql
mutation {
  createPostCategory(input: {
    name: "Noticias"
    polylangLanguageBy: { code: "es" }
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      polylangLanguage {
        locale
      }
      name
    }
  }
}
```
