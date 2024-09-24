# Lesson 29: Importing a post from another WordPress site

This tutorial lesson demonstrates how we can keep WordPress sites in sync, by importing a post from some WordPress site into our local WordPress site.

## GraphQL query to import a post from another WordPress site

The GraphQL query below connects to an upstream website's GraphQL endpoint, fetches the data for a specific post, and imports it locally. The Gato GraphQL plugin (free version) must be installed on the upstream website too.

(If Gato GraphQL is not installed on the upstream site, the query can be adapted to connect to its WP REST API endpoints instead.)

The resources referenced in the post must all exist locally:

- The author
- The featured image (if any)
- The categories
- (Tags also, however if these do not already exist, they are created together with the post, so it's not an issue)

As the common identifier for resources between the upstream and local sites, we use:

- Slugs for categories, tags and media items
- Usernames for users

If any of the resources does not exist in the local site, the GraphQL query prints an error and halts the import.

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  initVariablesWithFalse: _echo(value: false)
    @export(as: "requestProducedErrors")
    @export(as: "responseHasErrors")
    @export(as: "postIsMissing")
    @export(as: "postHasAuthor")
    @export(as: "postHasFeaturedImage")
    @export(as: "postHasCategories")
    @export(as: "postHasTags")
    @remove

  initVariablesWithNull: _echo(value: null)
    @export(as: "existingAuthorUsername")
    @export(as: "existingFeaturedImageSlug")
    @export(as: "featuredImageMutationInput")
    @remove

  initVariablesWithEmptyArray: _echo(value: [])
    @export(as: "existingCategorySlugs")
    @export(as: "existingTagSlugs")
    @remove
}

query CheckIfPostExistsLocally($postSlug: String!)
  @depends(on: "InitializeDynamicVariables")
{
  localPost: post(
    by: { slug: $postSlug }
    status: any
  ) {
    id
  }

  postAlreadyExists: _notNull(value: $__localPost)
    @export(as: "postAlreadyExists")
}

query FailIfPostAlreadyExistsLocally($postSlug: String!)
  @depends(on: "CheckIfPostExistsLocally")
  @include(if: $postAlreadyExists)
{
  errorMessage: _sprintf(
    string: "Post with slug '%s' already exists locally",
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

query ConnectToGraphQLAPI(
  $upstreamServerGraphQLEndpointURL: String!
  $postSlug: String!
)
  @depends(on: "FailIfPostAlreadyExistsLocally")
  @skip(if: $postAlreadyExists)
{
  externalData: _sendGraphQLHTTPRequest(input:{
    endpoint: $upstreamServerGraphQLEndpointURL,
    query: """
    
query GetPost($postSlug: String!) {
  post(by: { slug: $postSlug }) {
    id
    slug
    rawTitle
    rawContent
    rawExcerpt
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
  @depends(on: "ConnectToGraphQLAPI")
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

  # missingTagSlugs: _arrayDiff(
  #   arrays: [$postTagSlugs, $existingTagSlugs]
  # ) @export(as: "missingTagSlugs")
  # areTagsMissing: _notEmpty(
  #   value: $__missingTagSlugs
  # ) @export(as: "areTagsMissing")

  isAnyResourceMissing: _or(
    values: [
      $__isAuthorMissing,
      $__isFeaturedImageMissing,
      $__areCategoriesMissing,
      # $__areTagsMissing,
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
    # @if(condition: $areTagsMissing)
    #   @fail(
    #     message: "Tags are missing in local site"
    #     data: {
    #       missingTagBySlug: $missingTagSlugs
    #     }
    #     condition: ALWAYS
    #   )
  
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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

(As we've seen on previous lessons) We use the **Field to Input** feature (with syntax `$__field`) to pass the field's resolved value to a contiguous _field_.

When we need to pass the field's resolved value to a _directive_, we must instead use directive `@passOnwards` (which is similarly provided by the [**Field to Input**](https://gatographql.com/extensions/query-functions/) extension).

This query:

```graphql
{
  posts {
    id
    hasComments
    notHasComments: _not(value: $__hasComments)
  }
}
```

...is equivalent to this query:

```graphql
{
  posts {
    id
    hasComments
    notHasComments: hasComments
      @passOnwards(as: "postHasComments")
      @applyField(
        name: "_not"
        arguments: {
          value: $postHasComments
        },
        setResultInResponse: true
      )
  }
}
```

`@passOnwards` is useful to produce some computation on the value of the field. For instance, in the GraphQL query above, it is used to check if the `user` property is not `null`, before extracting its `username` property (and overriding the field value with it):

```graphql
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
```

</div>

## [WIP] Automatically importing missing resources

The Gato GraphQL schema must be upgraded with mutations to:

- Create/update users ([#2456](https://github.com/GatoGraphQL/GatoGraphQL/issues/2456))
- Create/update categories ([#2457](https://github.com/GatoGraphQL/GatoGraphQL/issues/2457))
- Upload media items, or import them from a URL ([#2458](https://github.com/GatoGraphQL/GatoGraphQL/issues/2458))

This is a work in progress. Once these mutations are supported, we can then have the GraphQL query automatically import each of the missing resources.
