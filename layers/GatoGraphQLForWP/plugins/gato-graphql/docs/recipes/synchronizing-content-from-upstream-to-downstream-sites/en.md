# Synchronizing content from upstream to downstream sites

Let's say that a media company has a network of WordPress sites for different regions, with every news article being published on a site or not only if it's suitable for that region.

For this situation, it makes sense to implement an architecture where:

- All content is published to (and edited in) a single upstream WordPress site, which acts as the single source of truth for content
- Suitable content is distributed to (but not edited in) each of the regional downstream WordPress sites

This recipe will demonstrate how to implement this architecture, with the upstream WordPress site needing to have the relevant Gato GraphQL extensions active, while the downstream sites need only have the free Gato GraphQL plugin.

## GraphQL query to synchronize content from upstream to downstream sites

The GraphQL query below (which can be triggered by the `post_updated` WordPress hook) is executed on the upstream WordPress site, to synchronize the content of the updated post to the relevant downstream sites. (The query can be adapted to also synchronize the other properties -tags, categories, author and featured image-, as explained in the previous recipe.)

It does the following:

- It receives the slug of the updated post, and its new and previous content
- It retrieves the meta property `"downstreamDomains"`, which is an array containing the domains of the downstream sites which are suitable for that post
- If the meta property does not exist, it then retrieves option `"downstreamDomains"` from the `wp_options` table, which contains the list of all the downstream domains
- It executes an `updatePost` mutation on each of the downstream sites, passing the updated content
- If any downstream site produces an error, the mutation is reverted on all downstream sites

As in the previous recipe, we use the post slug as the common identifier across sites.

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  initVariablesWithEmptyArray: _echo(value: [])
    @export(as: "downstreamDomains")
    @remove
}

query GetCustomDownstreamDomains($postSlug: String!)
  @depends(on: "InitializeDynamicVariables")
{
  post(by: { slug: $postSlug }) {
    customDownstreamDomains: metaValues(key: "downstreamDomains")
      @export(as: "downstreamDomains")

    hasCustomDownstreamDomains: _notEmpty(value: $__customDownstreamDomains)
      @export(as: "hasCustomDownstreamDomains")
      @remove
  }
}

query GetAllDownstreamDomains
  @depends(on: "GetCustomDownstreamDomains")
  @skip(if: $hasCustomDownstreamDomains)
{
  allDownstreamDomains: optionValues(name: "downstreamDomains")
    @default(value: [])
    @export(as: "downstreamDomains")
}

############################################################
# Attach "/graphql" to the domain, to point to that site's
# GraphQL single endpoint
############################################################
query ExportDownstreamGraphQLEndpoints
  @depends(on: "GetAllDownstreamDomains")
{
  downstreamGraphQLEndpoints: _echo(value: $downstreamDomains)
    @underEachArrayItem(
      passValueOnwardsAs: "domain"
    )
      @strAppend(string: "/graphql")
    @export(as: "downstreamGraphQLEndpoints")
}

query ConnectToDownstreamGraphQLEndpoints(
  $upstreamServerGraphQLEndpointURL: String!
  $postSlug: String!
  $newPostContent: String!
)
  @depends(on: "ExportDownstreamGraphQLEndpoints")
{
  externalData: _sendGraphQLHTTPRequest(input: {
    endpoint: $upstreamServerGraphQLEndpointURL,
    query: """
    
query UpdatePost(
  $postSlug: String!
  $postContent: String!
) {
  updatePost(input: {
    id: $postId,
    contentAs: { html: $postContent },
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
      slug
      rawContent
    }
  }
}

    """,
    variables: [
      {
        name: "postSlug",
        value: $postSlug
      }
    ]
  })
    @export(as: "externalData")

  requestProducedErrors: _isNull(value: $__externalData)
    @export(as: "requestProducedErrors")
    @remove
}

query ValidateResponse
  @depends(on: "ConnectToDownstreamGraphQLEndpoints")
  @skip(if: $postAlreadyExists)
  @skip(if: $requestProducedErrors)
{
  responseHasErrors: _propertyIsSetInJSONObject(
    object: $externalData
    by: {
      key: "errors"
    }
  )
    @export(as: "responseHasErrors")
    @remove

  postExists: _propertyIsSetInJSONObject(
    object: $externalData
    by: {
      path: "data.post"
    }
  )
    @remove
    
  postIsMissing: _not(value: $__postExists)
    @export(as: "postIsMissing")
    @remove
}

query FailIfResponseHasErrors
  @depends(on: "ValidateResponse")
  @skip(if: $postAlreadyExists)
  @skip(if: $requestProducedErrors)
  @skip(if: $postIsMissing)
  @include(if: $responseHasErrors)
{
  errors: _objectProperty(
    object: $externalData,
    by: {
      key: "errors"
    }
  ) @remove

  _fail(
    message: "Executing the GraphQL query against the upstream webserver produced error(s)"
    data: {
      errors: $__errors
    }
  ) @remove

  createPost: _echo(value: null)
}

query FailIfPostNotExists($postSlug: String!)
  @depends(on: "FailIfResponseHasErrors")
  @skip(if: $requestProducedErrors)
  @include(if: $postIsMissing)
{
  errorMessage: _sprintf(
    string: "There is no post with slug '%s' in the origin",
    values: [$postSlug]
  ) @remove

  _fail(
    message: $__errorMessage
    data: {
      slug: $postSlug
    }
  ) @remove
  
  createPost: _echo(value: null)
}

query ExportInputs
  @depends(on: "FailIfPostNotExists")
  @skip(if: $postAlreadyExists)
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
{
  postData: _objectProperty(
    object: $externalData,
    by: { path: "data.post" }
  ) @remove

  postTitle: _objectProperty(
    object: $__postData,
    by: { key: "rawTitle" }
  )
    @export(as: "postTitle")
    @remove

  postContent: _objectProperty(
    object: $__postData,
    by: { key: "rawContent" }
  )
    @export(as: "postContent")
    @remove

  postExcerpt: _objectProperty(
    object: $__postData,
    by: { key: "rawExcerpt" }
  )
    @export(as: "postExcerpt")
    @remove

  postAuthorUsername: _objectProperty(
    object: $__postData,
    by: { key: "author" }
  )
    @passOnwards(
      as: "author"
    )
    @applyField(
      name: "_notNull",
      arguments: {
        value: $author
      },
      passOnwardsAs: "hasAuthor"
    )
    @if(condition: $hasAuthor)
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $author,
          by: { key: "username" }
        },
        setResultInResponse: true
      )
    @export(as: "postAuthorUsername")
    @remove

  postHasAuthor: _notNull(
    value: $__postAuthorUsername
  )
    @export(as: "postHasAuthor")
    @remove

  postFeaturedImageSlug: _objectProperty(
    object: $__postData,
    by: { key: "featuredImage" }
  )
    @passOnwards(
      as: "featuredImage"
    )
    @applyField(
      name: "_notNull",
      arguments: {
        value: $featuredImage
      },
      passOnwardsAs: "hasFeaturedImage"
    )
    @if(condition: $hasFeaturedImage)
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $featuredImage,
          by: { key: "slug" }
        },
        setResultInResponse: true
      )
    @export(as: "postFeaturedImageSlug")
    @remove

  postHasFeaturedImage: _notNull(
    value: $__postFeaturedImageSlug
  )
    @export(as: "postHasFeaturedImage")
    @remove

  postCategorySlugs: _objectProperty(
    object: $__postData,
    by: { key: "categories" }
  )
    @underEachArrayItem(
      passValueOnwardsAs: "category"
    )
      @applyField(
        name: "_objectProperty"
        arguments: {
          object: $category,
          by: {
            key: "slug"
          }
        }
        setResultInResponse: true
      )
    @export(as: "postCategorySlugs")
    @remove

  postHasCategories: _notEmpty(
    value: $__postCategorySlugs
  )
    @export(as: "postHasCategories")
    @remove

  postTagSlugs: _objectProperty(
    object: $__postData,
    by: { key: "tags" }
  )
    @underEachArrayItem(
      passValueOnwardsAs: "tag"
    )
      @applyField(
        name: "_objectProperty"
        arguments: {
          object: $tag,
          by: {
            key: "slug"
          }
        }
        setResultInResponse: true
      )
    @export(as: "postTagSlugs")
    @remove

  postHasTags: _notEmpty(
    value: $__postTagSlugs
  )
    @export(as: "postHasTags")
    @remove
}

query ExportExistingResources
  @depends(on: "ExportInputs")
  @skip(if: $postAlreadyExists)
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
{
  existingAuthorByUsername: user(by: { username: $postAuthorUsername })
    @include(if: $postHasAuthor)
  {
    id
    username @export(as: "existingAuthorUsername")
  }

  existingFeaturedImageBySlug: mediaItem(by: { slug: $postFeaturedImageSlug })
    @include(if: $postHasFeaturedImage)
  {
    id
    slug @export(as: "existingFeaturedImageSlug")
  }

  existingCategoriesBySlug: postCategories(filter: { slugs: $postCategorySlugs })
    @include(if: $postHasCategories)
  {
    id
    slug @export(as: "existingCategorySlugs", type: LIST)
  }

  existingTagsBySlug: postTags(filter: { slugs: $postTagSlugs })
    @include(if: $postHasTags)
  {
    id
    slug @export(as: "existingTagSlugs", type: LIST)
  }
}

query ExportMissingResources
  @depends(on: "ExportExistingResources")
  @skip(if: $postAlreadyExists)
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
{
  isAuthorMissing: _notEquals(
    value1: $postAuthorUsername,
    value2: $existingAuthorUsername
  ) @export(as: "isAuthorMissing")
  
  isFeaturedImageMissing: _notEquals(
    value1: $postFeaturedImageSlug,
    value2: $existingFeaturedImageSlug
  ) @export(as: "isFeaturedImageMissing")

  missingCategorySlugs: _arrayDiff(
    arrays: [$postCategorySlugs, $existingCategorySlugs]
  ) @export(as: "missingCategorySlugs")
  areCategoriesMissing: _notEmpty(
    value: $__missingCategorySlugs
  ) @export(as: "areCategoriesMissing")

  missingTagSlugs: _arrayDiff(
    arrays: [$postTagSlugs, $existingTagSlugs]
  ) @export(as: "missingTagSlugs")
  areTagsMissing: _notEmpty(
    value: $__missingTagSlugs
  ) @export(as: "areTagsMissing")

  isAnyResourceMissing: _or(
    values: [
      $__isAuthorMissing,
      $__isFeaturedImageMissing,
      $__areCategoriesMissing,
      $__areTagsMissing,
    ]
  ) @export(as: "isAnyResourceMissing")
}

query FailIfAnyResourceIsMissing
  @depends(on: "ExportMissingResources")
  @skip(if: $postAlreadyExists)
  @skip(if: $requestProducedErrors)
  @skip(if: $postIsMissing)
  @skip(if: $responseHasErrors)
  @include(if: $isAnyResourceMissing)
{
  performingValidations: id
    @if(condition: $isAuthorMissing)
      @fail(
        message: "Author is missing in local site"
        data: {
          missingAuthorByUsername: $postAuthorUsername
        }
        condition: ALWAYS
      )
    @if(condition: $isFeaturedImageMissing)
      @fail(
        message: "Featured image is missing in local site"
        data: {
          missingFeaturedImageBySlug: $postFeaturedImageSlug
        }
        condition: ALWAYS
      )
    @if(condition: $areCategoriesMissing)
      @fail(
        message: "Categories are missing in local site"
        data: {
          missingCategoriesBySlug: $missingCategorySlugs
        }
        condition: ALWAYS
      )
    @if(condition: $areTagsMissing)
      @fail(
        message: "Tags are missing in local site"
        data: {
          missingTagBySlug: $missingTagSlugs
        }
        condition: ALWAYS
      )
  
  createPost: _echo(value: null)
}

query ExportMutationInputs
  @depends(on: "FailIfAnyResourceIsMissing")
  @skip(if: $postAlreadyExists)
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
  @skip(if: $isAnyResourceMissing)
{
  featuredImageMutationInput: _echo(value: {
    slug: $postFeaturedImageSlug
  })
    @include(if: $postHasFeaturedImage)
    @export(as: "featuredImageMutationInput")
    @remove
}

mutation ImportPost(
  $postSlug: String!
)
  @depends(on: "ExportMutationInputs")
  @skip(if: $postAlreadyExists)
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
  @skip(if: $isAnyResourceMissing)
{
  createPost(input: {
    status: draft,
    slug: $postSlug
    title: $postTitle
    contentAs: {
      html: $postContent
    },
    excerpt: $postExcerpt
    authorBy: {
      username: $postAuthorUsername
    },
    featuredImageBy: $featuredImageMutationInput,
    categoriesBy: {
      slugs: $postCategorySlugs
    },
    tagsBy: {
      slugs: $postTagSlugs
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
      date
      status

      slug
      title
      content
      excerpt

      author {
        id
        username
      }
      featuredImage {
        id
        slug
      }
      categories {
        id
        slug
      }
      tags {
        id
        slug
      }
    }
  }
}
```

## Triggering the synchronization via a hook



## Adding a transactional state across all downstream sites

We can also implement a transactional state across all downstream sites, to make sure that they do not get out of sync with each other. To achieve this, , when synchronizing content, if any downstream site fails (for instance, because the server is down), then the mutation is reverted on all other downstream sites.

## Reverting mutations in case of error

Say can also do:

"Testing mutations and restoring the original value"

Remove block by type:

```graphql
query CreateVars {
  foundPosts: posts(filter: { search: "\"<!-- /wp:columns -->\"" } ) {
    id @export(as: "postIDs", type: LIST)
    rawContent
    originalInputs: _echo(value: {
      id: $__id,
      contentAs: { html: $__rawContent }
    }) @export(as: "originalInputs")
  }
}

mutation RemoveBlock
  @depends(on: "CreateVars")
{
  posts(filter: { ids: $postIDs } ) {
    id
    rawContent
    adaptedRawContent: _strRegexReplace(
      in: $__rawContent,
      searchRegex: "#(<!-- wp:columns -->[\\s\\S]+<!-- /wp:columns -->)#",
      replaceWith: ""
    )
    update(input: {
      contentAs: { html: $__adaptedRawContent },
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
        rawContent
      }
    }
  }
}

mutation RestorePosts
  @depends(on: "CreateVars")
{
  restorePosts: _echo(value: $originalInputs)
    @underEachArrayItem(passValueOnwardsAs: "input")
      @applyField(
        name: "updatePost"
        arguments: { input: $input }
      )
}

query CheckRestoredPosts
  @depends(on: "CreateVars")
{
  restoredPosts: posts(filter: { ids: $postIDs } ) {
    id
    rawContent
  }
}

query RemoveBlockAndThenRestorePosts
  @depends(on: ["RemoveBlock", "RestorePosts", "CheckRestoredPosts"])
{
  id
}
```

Detailed:

```graphql
query CreateVars(
  $removeBlockType: String!
) {
  regex: _sprintf(
    string: "#(<!-- %1$s -->[\\s\\S]+<!-- /%1$s -->)#",
    values: [$removeBlockType]
  ) @export(as: "regex")

  search: _sprintf(
    string: "\"<!-- /%1$s -->\"",
    values: [$removeBlockType]
  )
  
  foundPosts: posts(filter: { search: $__search } ) {
    id @export(as: "postIDs", type: LIST)
    rawContent
    originalInputs: _echo(value: {
      id: $__id,
      contentAs: { html: $__rawContent }
    }) @export(as: "originalInputs")
  }
}

mutation RemoveBlock
  @depends(on: "CreateVars")
{
  posts(filter: { ids: $postIDs } ) {
    id
    rawContent
    adaptedRawContent: _strRegexReplace(
      in: $__rawContent,
      searchRegex: $regex,
      replaceWith: ""
    )
    update(input: {
      contentAs: { html: $__adaptedRawContent },
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
        rawContent
      }
    }
  }
}

mutation RestorePosts
  @depends(on: "CreateVars")
{
  restorePosts: _echo(value: $originalInputs)
    @underEachArrayItem(passValueOnwardsAs: "input")
      @applyField(
        name: "updatePost"
        arguments: { input: $input }
      )
}

query CheckRestoredPosts
  @depends(on: "CreateVars")
{
  restoredPosts: posts(filter: { ids: $postIDs } ) {
    id
    rawContent
  }
}

query RemoveBlockAndThenRestorePosts
  @depends(on: ["RemoveBlock", "RestorePosts", "CheckRestoredPosts"])
{
  id
}
```

and vars:

```json
{
  "removeBlockType": "wp:columns"
}
```
