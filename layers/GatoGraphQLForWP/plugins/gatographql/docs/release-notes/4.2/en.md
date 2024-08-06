# Release Notes: 4.2

## Improvements

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
