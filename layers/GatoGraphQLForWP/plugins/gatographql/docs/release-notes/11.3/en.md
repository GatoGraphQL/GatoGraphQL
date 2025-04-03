# Release Notes: 11.3

## Fixed

- Passing a non-`post` CPT to `updatePost` will show an error ([#3070](https://github.com/GatoGraphQL/GatoGraphQL/pull/3070))

## Improvements

- Made meta field `metaValue` handle any scalar type (previously only `String`) ([#3061](https://github.com/GatoGraphQL/GatoGraphQL/pull/3061))
- Made meta field `metaValues` handle any scalar type (previously only built-in ones), such as `JSONObject` ([#3061](https://github.com/GatoGraphQL/GatoGraphQL/pull/3061))
- Allow to hook inputs into tag/category mutations ([#3062](https://github.com/GatoGraphQL/GatoGraphQL/pull/3062))

## Added

- Fields `meta: ListValueJSONObject!` and `metaKeys: [String!]!` for types `Comment/CustomPost/TaxonomyTerm/User` ([#3060](https://github.com/GatoGraphQL/GatoGraphQL/pull/3060))
- Type `ListValueJSONObject` ([#3060](https://github.com/GatoGraphQL/GatoGraphQL/pull/3060))
- Documentation for new field `_objectRecursiveMerge`, from the **Schema Functions** extension ([#3074](https://github.com/GatoGraphQL/GatoGraphQL/pull/3074))

### Meta mutations

Added meta mutations for all entities:

- Custom posts ([#3067](https://github.com/GatoGraphQL/GatoGraphQL/pull/3067))
- Categories ([#3063](https://github.com/GatoGraphQL/GatoGraphQL/pull/3063))
- Tags ([#3064](https://github.com/GatoGraphQL/GatoGraphQL/pull/3064))
- Users ([#3072](https://github.com/GatoGraphQL/GatoGraphQL/pull/3072))
- Comments ([#3072](https://github.com/GatoGraphQL/GatoGraphQL/pull/3072))

Below are some examples.

#### Adding meta

You can add meta entries to custom posts, tags, categories, comments, and users.

This query adds a meta entry to post with ID `4`:

```graphql
mutation {
  addCustomPostMeta(input: {
    id: 4
    key: "some_key"
    value: "Some value"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    customPost {
      id
      metaValue(key: "some_key") 
    }
  }
}
```

#### Updating meta

Update a category meta entry:

```graphql
mutation {
  updateCategoryMeta(input: {
    id: 20
    key: "_source"
    value: "Updated source value"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    category {
      __typename
      id
      metaValue(key: "_source") 
    }
  }
}
```

This query uses nested mutations to update a meta value in a post:

```graphql
mutation {
  post(by: {id: 1}) {
    updateMeta(input: {
      key: "some_key"
      value: "Updated description"
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        id
        metaValue(key: "single_meta_key") 
      }
    }
  }
}
```

#### Deleting meta

Delete a meta entry from a post:

```graphql
mutation {
  deletePostMeta(input: {
    id: 5
    key: "some_key"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
      id
      metaValue(key: "some_key") 
    }
  }
}
```

Delete the same meta entry from multiple posts:

```graphql
mutation {
  deletePostMetas(inputs: [
    {
      id: 5
      key: "some_key"
    },
    {
      id: 6
      key: "some_key"
    }
  ]) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
      id
      metaValue(key: "some_key") 
    }
  }
}
```

#### Setting multiple meta entries at once

You can set multiple meta entries at once via the `set{Entity}Meta` mutation:

```graphql
mutation {
  setCustomPostMeta(input: {
    id: 4
    entries: {
      single_meta_key: [
        "This is a single entry",
      ],
      object_meta_key: [
        {
          key: "This is a key",
          value: "This is a value",
        },
      ],
      array_meta_key: [
        "This is a string",
        "This is another string",
      ],
      object_array_meta_key: [
        [
          {
            key: "This is a key 1",
            value: "This is a value 1",
          },
          {
            key: "This is a key 2",
            value: "This is a value 2",
          },
        ]
      ],
    }
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    customPost {
      id
      meta(keys: ["single_meta_key", "object_meta_key", "array_meta_key", "object_array_meta_key"])
    }
  }
}
```

#### Setting meta entries when creating/updating an entity

You can define meta entries directly when creating or updating a custom post, tag, category, or comment, via param `meta`.

This query sets meta when adding a comment:

```graphql
mutation {
  addCommentToCustomPost(input: {
    customPostID: 1130
    commentAs: { html: "New comment" }
    meta: {
      some_meta_key: [
        "This is a single entry",
      ],
      another_meta_key: [
        "This is an array entry 1",
        "This is an array entry 2",
      ],
    }
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    comment {
      id
      meta(keys: ["some_meta_key", "another_meta_key"]) 
    }
  }
}
```

This query injects the meta in nested mutation `Post.update`:

```graphql
mutation {
  post(by: {id: 1}) {
    update(input: {
      meta: {
        single_meta_key: [
          "This is an updated value",
        ]
      }
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        id
        metaValue(key: "single_meta_key") 
      }
    }
  }
}
```