# Release Notes: 3.0

## Breaking changes

### Require at least WordPress v6.0 ([#2719](https://github.com/GatoGraphQL/GatoGraphQL/pull/2719))

When using Gato GraphQL with WordPress `v6.6` (just [ahead of its release](https://wordpress.org/news/2024/07/wordpress-6-6-rc2/)), [blocks in the plugin stopped working](https://github.com/WordPress/gutenberg/issues/63009).

As a solution, blocks were adapted and re-compiled, and the new [compiled files only work only with WordPress `v6.0`+](https://github.com/WordPress/gutenberg/issues/63135#issuecomment-2211631051).

That's why, from now on, Gato GraphQL requires at least WordPress `v6.0`.

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
      excerpt: "The cup is within reach"
    },
    {
      title: "Third post"
      contentAs: {
        html: "This is yet another piece of content"
      },
      authorBy: {
        id: 1
      },
      status: draft
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
      title
      content
      excerpt
      author {
        name
      }
      status
    }
  }
}
```

All bulk mutations accept two arguments:

- `inputs` (mandatory): The array of input items, where each item contains the data to mutate one resource
- `stopExecutingMutationItemsOnFirstError` (default `false`): Indicate if, in case any of the inputs produces an error, should stop executing the mutation on the following inputs.

All mutations are executed in the same order provided in the `inputs` argument.

Bulk mutations unlock possibilities for managing our WordPress site. For instance, the following GraphQL query uses `createPosts` (and Multiple Query Execution, provided by Gato GraphQL PRO) to duplicate posts:

```graphql
query ExportPostData
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
  @depends(on: "ExportPostData")
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

In addition to a bulk mutation, every mutation in the GraphQL schema has also been added a corresponding field to query its recently-created payload objects, with name `{mutationName}MutationPayloadObjects` (such as field `createPostMutationPayloadObjects` to query the payload objects from mutation `createPost`).

These fields provide a method to execute a mutation via the `@applyField` directive (for instance, while iterating all items in an array via `@underEachArrayItem`), and then be able to retrieve the results of the mutation.

For instance, this is an alternative way for duplicating posts in bulk:

```graphql
query ExportPostData
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
  @depends(on: "ExportPostData")
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

- `Root.addCommentToCustomPostMutationPayloadObjects` (for `Root.addCommentToCustomPost`)
- `Root.createCustomPostMutationPayloadObjects` (for `Root.createCustomPost`)
- `Root.createMediaItemMutationPayloadObjects` (for `Root.createMediaItem`)
- `Root.createPageMutationPayloadObjects` (for `Root.createPage`)
- `Root.createPostMutationPayloadObjects` (for `Root.createPost`)
- `Root.removeFeaturedImageFromCustomPostMutationPayloadObjects` (for `Root.removeFeaturedImageFromCustomPost`)
- `Root.replyCommentMutationPayloadObjects` (for `Root.replyComment`)
- `Root.setCategoriesOnPostMutationPayloadObjects` (for `Root.setCategoriesOnPost`)
- `Root.setFeaturedImageOnCustomPostMutationPayloadObjects` (for `Root.setFeaturedImageOnCustomPost`)
- `Root.setTagsOnPostMutationPayloadObjects` (for `Root.setTagsOnPost`)
- `Root.updateCustomPostMutationPayloadObjects` (for `Root.updateCustomPost`)
- `Root.updatePageMutationPayloadObjects` (for `Root.updatePage`)
- `Root.updatePostMutationPayloadObjects` (for `Root.updatePost`)

By default, these fields are not added to the GraphQL schema. For that, the new option "Use payload types for mutations, and add fields to query those payload objects" must be selected (see below).

### Added option to schema configuration block "Payload Types for Mutations" ([#2720](https://github.com/GatoGraphQL/GatoGraphQL/pull/2720))

The schema configuration block "Payload Types for Mutations" has been added a new option value:

- Use payload types for mutations, and add fields to query those payload objects

This option will add those fields to query the mutation payload objects (see above) to the GraphQL schema.

### Added predefined custom endpoint "Bulk mutations" ([#2720](https://github.com/GatoGraphQL/GatoGraphQL/pull/2720))

Custom endpoint "Bulk mutations" (alongside its schema configuration with the same name) is created by default when installing the plugin.

This custom endpoint is useful for executing CRUD operations in bulk, such as for persisted queries "Duplicate posts" and "Import posts from CSV".

### Removed predefined custom endpoint "Nested mutations + Entity as mutation payload type" ([#2720](https://github.com/GatoGraphQL/GatoGraphQL/pull/2720))

Since adding predefined custom endpoint "Bulk mutations", the predefined custom endpoint "Nested mutations + Entity as mutation payload type" became unnecessary, so it's been removed.

Similarly, its schema configuration "Nested mutations + Entity as mutation payload type" has also been removed.

(This is not a breaking change, because these items will simply not be installed anymore; If you have them already installed, they will not get deleted.)

## Fixed

- Catch exception if dependency version is not semver ([#2712](https://github.com/GatoGraphQL/GatoGraphQL/pull/2712))
- Convert entries in JSON dictionary of variables in persisted query from array to object ([#2715](https://github.com/GatoGraphQL/GatoGraphQL/pull/2715))
