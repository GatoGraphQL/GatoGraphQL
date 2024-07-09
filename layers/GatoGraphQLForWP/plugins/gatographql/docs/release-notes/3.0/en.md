# Release Notes: 3.0

## Breaking changes

### Require at least WordPress v6.0 ([#2719](https://github.com/GatoGraphQL/GatoGraphQL/pull/2719))

When using Gato GraphQL with WordPress `v6.6` (just [ahead of its release](https://wordpress.org/news/2024/07/wordpress-6-6-rc2/)), [blocks in the plugin stopped working](https://github.com/WordPress/gutenberg/issues/63009).

As a solution, blocks were adapted and re-compiled, and the new [compiled files only work only with WordPress `v6.0`+](https://github.com/WordPress/gutenberg/issues/63135#issuecomment-2211631051).

That's why, from now on, Gato GraphQL requires at least WordPress `v6.0`.

### Removed predefined custom endpoint "Nested mutations + Entity as mutation payload type" ([#2720](https://github.com/GatoGraphQL/GatoGraphQL/pull/2720))

Since adding predefined custom endpoint "Bulk mutations" (see below), the predefined custom endpoint "Nested mutations + Entity as mutation payload type" became unnecessary, so it's been removed.

Similarly, its schema configuration "Nested mutations + Entity as mutation payload type" has also been removed.

### Option "Do not use payload types for mutations (i.e. return the mutated entity)" in schema configuration block "Payload Types for Mutations" must be re-selected ([#2720](https://github.com/GatoGraphQL/GatoGraphQL/pull/2720))

Schema configuration block "Payload Types for Mutations" has been added a new option value (see below). For this reason, its inner attribute to store the selected option has changed (it went from storing a `true/false` value, to storing an option string value).

If you have created a schema configuration with option "Do not use payload types for mutations (i.e. return the mutated entity)" selected, after upgrading to `v3.0` this selection value will be lost. You need to edit the schema configuration, select this option again, and save.

## Improvements

### Added compatibility with WordPress 6.6 ([#2717](https://github.com/GatoGraphQL/GatoGraphQL/pull/2717))

Gato GraphQL `3.0` has recompiled all its blocks, to make them compatible with WordPress 6.6. (For all previous versions, blocks will throw a JS error.)

### Added bulk mutation fields (for all mutations in the schema) ([#2721](https://github.com/GatoGraphQL/GatoGraphQL/pull/2721))

Gato GraphQL `3.0` adds "bulk" mutation fields for all mutations in the schema, allowing us to mutate multiple resources.

For instance, mutation `createPosts` (single-resource mutation is `createPost`) will create multiple posts:

```graphql
mutation CreatePosts {
  createPosts(inputs: [
    {
      title: "First post"
      contentAs: {
        html: "This is the content for the first post"
      }
    },
    {
      title: "Second post"
      contentAs: {
        html: "Here is another content, for another post"
      }
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
      title
      content
    }
  }
}
```

All bulk mutations accept two arguments:

- `inputs` (mandatory): The array of input items, where each item contains the data to mutate one resource
- `stopExecutingMutationItemsOnFirstError` (default `false`): Indicate if, in case any of the inputs produces an error, should stop executing the mutation on the following inputs.

All mutations are executed in the same order provided in the `inputs` argument.

Bulk mutations unlock plenty of possibilities for managing our WordPress site. For instance, the following GraphQL query uses `createPosts` to duplicate posts:

```graphql
query GetPostsAndExportData
{
  postsToDuplicate: posts {
    rawTitle
    rawContent
    rawExcerpt
    postInput: _echo(value: {
      title: $__rawTitle
      contentAs: {
        html: $__rawContent
      },
      excerpt: $__rawExcerpt
    })
      @export(as: "postInputs", type: LIST)
      @remove
  }
}

mutation CreatePosts
  @depends(on: "GetPostsAndExportData")
{
  createPosts(inputs: $postInputs) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
      id
      title
      content
      excerpt
    }
  }
}
```

The list of added bulk mutation fields is the following:

- `Root.addCommentToCustomPosts`
- `Root.createCustomPosts`
- `Root.createMediaItems`
- `Root.createPages`
- `Root.createPosts`
- `Root.removeFeaturedImageFromCustomPosts`
- `Root.replyComments`
- `Root.setCategoriesOnPosts`
- `Root.setFeaturedImageOnCustomPosts`
- `Root.setTagsOnPosts`
- `Root.updateCustomPosts`
- `Root.updatePages`
- `Root.updatePosts`
- `Comment.replyWithComments`
- `CustomPost.addComments`

### Added fields to query the mutation payload objects ([#2720](https://github.com/GatoGraphQL/GatoGraphQL/pull/2720))

Every mutation in the schema has been added a corresponding field to query its recently-created payload objects, with name `{mutationName}MutationPayloadObjects` (such as field `createPostMutationPayloadObjects` to query the payload objects from mutation `createPost`).

These fields enable us to retrieve the results of bulk mutations.

For instance, we can duplicate posts in bulk with the following query (using Gato GraphQL PRO):

```graphql
query GetPostsAndExportData
{
  postsToDuplicate: posts {
    rawTitle
    rawContent
    rawExcerpt
    postInput: _echo(value: {
      title: $__rawTitle
      contentAs: {
        html: $__rawContent
      },
      excerpt: $__rawExcerpt
    })
      @export(as: "postInputs", type: LIST)
      @remove
  }
}

mutation CreatePosts
  @depends(on: "GetPostsAndExportData")
{
  createdPostMutationPayloadObjectIDs: _echo(value: $postInputs)
    @underEachArrayItem(
      passValueOnwardsAs: "input"
    )
      @applyField(
        name: "createPost"
        arguments: {
          input: $input
        },
        setResultInResponse: true
      )
    @export(as: "createdPostMutationPayloadObjectIDs")
}

query DuplicatePosts
  @depends(on: "CreatePosts")
{
  createdPostMutationObjectPayloads: createPostMutationPayloadObjects(input: {
    ids: $createdPostMutationPayloadObjectIDs
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
      title
      content
      excerpt
    }
  }
}
```

The list of added fields is the following:

- `addCommentToCustomPostMutationPayloadObjects` (for `addCommentToCustomPost`)
- `createCustomPostMutationPayloadObjects` (for `createCustomPost`)
- `createMediaItemMutationPayloadObjects` (for `createMediaItem`)
- `createPageMutationPayloadObjects` (for `createPage`)
- `createPostMutationPayloadObjects` (for `createPost`)
- `removeFeaturedImageFromCustomPostMutationPayloadObjects` (for `removeFeaturedImageFromCustomPost`)
- `replyCommentMutationPayloadObjects` (for `replyComment`)
- `setCategoriesOnPostMutationPayloadObjects` (for `setCategoriesOnPost`)
- `setFeaturedImageOnCustomPostMutationPayloadObjects` (for `setFeaturedImageOnCustomPost`)
- `setTagsOnPostMutationPayloadObjects` (for `setTagsOnPost`)
- `updateCustomPostMutationPayloadObjects` (for `updateCustomPost`)
- `updatePageMutationPayloadObjects` (for `updatePage`)
- `updatePostMutationPayloadObjects` (for `updatePost`)

By default, these fields are not added to the GraphQL schema. For that, the new option "Use payload types for mutations, and add fields to query those payload objects" must be selected (see below).

### Added option to schema configuration block "Payload Types for Mutations" ([#2720](https://github.com/GatoGraphQL/GatoGraphQL/pull/2720))

The schema configuration block "Payload Types for Mutations" has been added a new option value:

- Use payload types for mutations, and add fields to query those payload objects

This option will add those fields to query the mutation payload objects (see above) to the GraphQL schema.

### Added predefined custom endpoint "Bulk mutations" ([#2720](https://github.com/GatoGraphQL/GatoGraphQL/pull/2720))

Custom endpoint "Bulk mutations" (alongside its schema configuration with the same name) is created by default when installing the plugin.

This custom endpoint is useful for executing CRUD operations in bulk, such as for persisted queries "Duplicate posts" and "Import posts from CSV".

## Fixed

- Catch exception if dependency version is not semver ([#2712](https://github.com/GatoGraphQL/GatoGraphQL/pull/2712))
- Convert entries in JSON dictionary of variables in persisted query from array to object ([#2715](https://github.com/GatoGraphQL/GatoGraphQL/pull/2715))
