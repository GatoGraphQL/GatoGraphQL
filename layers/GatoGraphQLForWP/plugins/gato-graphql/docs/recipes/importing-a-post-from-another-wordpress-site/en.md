# Importing a post from another WordPress site

## When the origin server exposes a Gato GraphQL endpoint

Use Gato GraphQL on other end, then can execute this GraphQL query:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  initVariablesWithFalse: _echo(value: false)
    @export(as: "responseHasErrors")
    @export(as: "postIsMissing")
    @remove

  initVariablesWithNull: _echo(value: null)
    @export(as: "existingAuthorSlug")
    @export(as: "existingFeaturedImageSlug")
    @remove

  initVariablesWithEmptyArray: _echo(value: [])
    @export(as: "existingCategorySlugPaths")
    @export(as: "existingTagSlugs")
    @remove
}

query ConnectToGraphQLAPI(
  $upstreamServerGraphQLEndpointURL: String!
  $postId: ID!
)
  @depends(on: "InitializeDynamicVariables")
{
  externalData: _sendGraphQLHTTPRequest(input:{
    endpoint: $upstreamServerGraphQLEndpointURL,
    query: """
    
query GetPost($postId: ID!) {
  post(by: { id: $postId }) {
    id
    slug
    rawTitle
    rawContent
    rawExcerpt
    author {
      id
      slug
    }
    featuredImage {
      id
      slug
    }
    categories {
      id
      slugPath
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
        name: "postId",
        value: $postId
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
    message: "Executing the GraphQL query produced error(s)"
    data: {
      errors: $__errors
    }
  ) @remove
}

query ExportInputs
  @depends(on: "FailIfResponseHasErrors")
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
{
  postData: _objectProperty(
    object: $externalData,
    by: { path: "data.post" }
  ) @remove

  postSlug: _objectProperty(
    object: $__postData,
    by: { key: "slug" }
  )
    @export(as: "postSlug")
    @remove

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

  postAuthorSlug: _objectProperty(
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
          by: { key: "slug" }
        },
        setResultInResponse: true
      )
    @export(as: "postAuthorSlug")
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

  postCategorySlugPaths: _objectProperty(
    object: $__postData,
    by: { key: "categorys" }
  )
    @underEachArrayItem(
      passValueOnwardsAs: "category"
    )
      @applyField(
        name: "_objectProperty"
        arguments: {
          object: $category,
          by: {
            key: "slugPath"
          }
        }
        setResultInResponse: true
      )
    @export(as: "postCategorySlugPaths")
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
}

query ExportExistingResources
  @depends(on: "ExportInputs")
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
{
  existingAuthorBySlug: user(by: { slug: $postAuthorSlug }) {
    id
    slug @export(as: "existingAuthorSlug")
  }

  existingFeaturedImageBySlug: featuredImage(by: { slug: $postFeaturedImageSlug }) {
    id
    slug @export(as: "existingFeaturedImageSlug")
  }

  existingCategoriesBySlugPath: categories(filter: { slugPaths: $postCategorySlugPaths }) {
    id
    slugPath @export(as: "existingCategorySlugPaths")
  }

  existingTagsBySlug: postTags(filter: { slugs: $postTagSlugs }) {
    id
    slug @export(as: "existingTagSlugs")
  }
}

query ExportMissingResources
  @depends(on: "ExportExistingResources")
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
{
  isAuthorMissing: _notEquals(
    value1: $postAuthorSlug,
    value2: $existingAuthorSlug
  ) @export(as: "isAuthorMissing")
  
  isFeaturedImageMissing: _notEquals(
    value1: $postFeaturedImageSlug,
    value2: $existingFeaturedImageSlug
  ) @export(as: "isFeaturedImageMissing")

  missingCategorySlugPaths: _arrayDiff(
    arrays: [$postCategorySlugPaths, $existingCategorySlugPaths]
  ) @export(as: "missingCategorySlugPaths")
  areCategoriesMissing: _notEmpty(
    value: $__missingCategorySlugPaths
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

query FailIfAuthorIsMissing
  @depends(on: "ExportMissingResources")
  @skip(if: $requestProducedErrors)
  @skip(if: $postIsMissing)
  @skip(if: $responseHasErrors)
  @include(if: $isAuthorMissing)
{
  _fail(
    message: "Author is missing"
    data: {
      authorSlug: $postAuthorSlug
    }
  ) @remove
}

query FailIfFeaturedImageIsMissing
  @depends(on: "ExportMissingResources")
  @skip(if: $requestProducedErrors)
  @skip(if: $postIsMissing)
  @skip(if: $responseHasErrors)
  @include(if: $isFeaturedImageMissing)
{
  _fail(
    message: "Featured image is missing"
    data: {
      featuredImageSlug: $postFeaturedImageSlug
    }
  ) @remove
}

query FailIfCategoriesAreMissing
  @depends(on: "ExportMissingResources")
  @skip(if: $requestProducedErrors)
  @skip(if: $postIsMissing)
  @skip(if: $responseHasErrors)
  @include(if: $areCategoriesMissing)
{
  _fail(
    message: "Categories are missing"
    data: {
      missingCategorySlugPaths: $missingCategorySlugPaths
    }
  ) @remove
}

query FailIfTagsAreMissing
  @depends(on: "ExportMissingResources")
  @skip(if: $requestProducedErrors)
  @skip(if: $postIsMissing)
  @skip(if: $responseHasErrors)
  @include(if: $areTagsMissing)
{
  _fail(
    message: "Tags are missing"
    data: {
      missingTagSlugs: $missingTagSlugs
    }
  ) @remove
}

mutation ImportPost
  @depends(on: [
    "FailIfAuthorIsMissing",
    "FailIfFeaturedImageIsMissing",
    "FailIfCategoriesAreMissing",
    "FailIfTagsAreMissing",
  ])
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
      slug: $postAuthorSlug
    },
    featuredImageBy: {
      slug: $postFeaturedImageSlug
    },
    categoriesBy: {
      slugPaths: $postCategorySlugPaths
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
        slug
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

Add tip on `@passOnwards`!!!


Then this single GraphQL query contains all the data.

Current limitations:

<!-- - `categories` is not handling parents! -->
- can only use `setCategoriesOnPost`, so the cat must exist!
- can only use `setTagsOnPost`, so the tag must exist! (because the slug is not enough to create it, as the name could be in uppercase)
- `featuredImageID` cannot be replicated yet, as there's no mutation to upload attachments yet

Process with this query:

```graphql
...
```

Cannot do:
  ## When the origin server exposes WP REST API endpoints
Then:
  Explain why as a tip!

Otherwise can use REST API, but must use context=edit and pass the application password, and make multiple requests.

Use:

https://newapi.getpop.org/wp-json/wp/v2/posts/1178/
https://newapi.getpop.org/wp-json/wp/v2/categories/?include=29
https://newapi.getpop.org/wp-json/wp/v2/tags/?include=79,81,96,105,116
https://newapi.getpop.org/wp-json/wp/v2/users/7

Use (https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/):

```bash
curl \
  --user "USER:PASSWORD" \
  https://mysite.com/wp-json/wp/v2/posts/40/?_fields=id,slug,title.raw,content.raw,excerpt.raw,author,categories,tags&context=edit
```

Mention that we could use this, but instead use other way, because of next recipe:
  https://newapi.getpop.org/wp-json/wp/v2/categories?post=1178
  https://newapi.getpop.org/wp-json/wp/v2/tags?post=1178
