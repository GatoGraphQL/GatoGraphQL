# Importing a post from another WordPress site

## When associated resources already exist

Mention that the "slug" is the common ID between origin and local websites. Then, if the local website already has a post with that same slug, the process is halted.

Use Gato GraphQL on other end, then can execute this GraphQL query:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  initVariablesWithFalse: _echo(value: false)
    @export(as: "requestProducedErrors")
    @export(as: "responseHasErrors")
    @export(as: "postIsMissing")
    @export(as: "postHasCategories")
    @export(as: "postHasTags")
    @remove

  initVariablesWithNull: _echo(value: null)
    @export(as: "existingAuthorUsername")
    @export(as: "existingFeaturedImageSlug")
    @remove

  initVariablesWithEmptyArray: _echo(value: [])
    @export(as: "existingCategorySlugPaths")
    @export(as: "existingTagSlugs")
    @remove
}

query CheckIfPostExistsLocally($postSlug: String!)
  @depends(on: "InitializeDynamicVariables")
{
  localPost: post(
    by: { slug: $postSlug }
    status: [
      draft,
      future,
      inherit,
      pending,
      private,
      publish,
      trash,
    ]
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
    message: "Executing the GraphQL query produced error(s)"
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

  # postSlug: _objectProperty(
  #   object: $__postData,
  #   by: { key: "slug" }
  # )
  #   @export(as: "postSlug")
  #   @remove

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

  postHasCategories: _notEmpty(
    value: $__postCategorySlugPaths
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
  existingAuthorByUsername: user(by: { username: $postAuthorUsername }) {
    id
    username @export(as: "existingAuthorUsername")
  }

  existingFeaturedImageBySlug: featuredImage(by: { slug: $postFeaturedImageSlug }) {
    id
    slug @export(as: "existingFeaturedImageSlug")
  }

  existingCategoriesBySlugPath: categories(filter: { slugPaths: $postCategorySlugPaths })
    @include(if: $postHasCategories)
  {
    id
    slugPath @export(as: "existingCategorySlugPaths", type: LIST)
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
        message: "Author is missing"
        data: {
          authorUsername: $postAuthorUsername
        }
        condition: ALWAYS
      )
    @if(condition: $isFeaturedImageMissing)
      @fail(
        message: "Featured image is missing"
        data: {
          featuredImageSlug: $postFeaturedImageSlug
        }
        condition: ALWAYS
      )
    @if(condition: $areCategoriesMissing)
      @fail(
        message: "Categories are missing"
        data: {
          categorySlugPaths: $missingCategorySlugPaths
        }
        condition: ALWAYS
      )
    @if(condition: $areTagsMissing)
      @fail(
        message: "Tags are missing"
        data: {
          tagSlugs: $missingTagSlugs
        }
        condition: ALWAYS
      )
  
  createPost: _echo(value: null)
}

mutation ImportPost(
  $postSlug: String!
)
  @depends(on: "FailIfAnyResourceIsMissing")
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

## When associated resources must also be imported

Make this section a `@todo`, pointing to the GitHub issues for the missing mutations.

Recursive process: have a similar GraphQL query to import users, media items, categories and tags, and invoke them to also import associated resources.

How to do it: Store the "Import User", "Import Category", etc queries as Persisted Queries, and execute them locally via an HTTP request.

Then check again that the resources were indeed created! Otherwise show error and exit.

This code is different, from a previous/deprecated idea! So no need to use!

<!-- ```graphql
query ExportMissingResources
  @depends(on: "ExportExistingResources")
  @skip(if: $requestProducedErrors)
  @skip(if: $responseHasErrors)
  @skip(if: $postIsMissing)
{
  # ...

  # Format arrays as strings, to input into query
  missingCategorySlugPathsAsString: _arrayJoin(
    array: $__missingCategorySlugPaths
    separator: "\""
  )
    @strPrepend(
      string: "[\""
    )
    @strAppend(
      string: "\"]"
    )
    @export(as: "missingCategorySlugPathsAsString")
  missingTagSlugsAsString: _arrayJoin(
    array: $__missingTagSlugs
    separator: "\", \""
  )
    @strPrepend(
      string: "[\""
    )
    @strAppend(
      string: "\"]"
    )
    @export(as: "missingTagSlugsAsString")
}

query ExportGraphQLQueryToFetchMissingResources
  @depends(on: "ExportMissingResources")
  @skip(if: $requestProducedErrors)
  @skip(if: $postIsMissing)
  @skip(if: $responseHasErrors)
  @include(if: $isAnyResourceMissing)
{
  missingResourcesQuery: _echo(value:
    """    
    query GetMissingResources {
    """
  )
    @if(condition: $isAuthorMissing)
      @strAppend(string:
        """
        user(by: { username: "{$postAuthorUsername}" })  {
          id
          username
          name
        }
        """
      )
    @if(condition: $isFeaturedImageMissing)
      @strAppend(string:
        """
        mediaItem(by: { slug: "{$postFeaturedImageSlug}" }) {
          id
          slug
          src
          width
          height
        }
        """
      )
    @if(condition: $areCategoriesMissing)
      @strAppend(string:
        """
        postCategories(filter: { slugPaths: {$postCategorySlugPaths} }) {
          id
          slugPath
          name
          description
        }
        """
      )
    @if(condition: $areTagsMissing)
      @strAppend(string:
        """
        postTags(filter: { slugs: {$postTagSlugs} }) {
          id
          slug
          name
          description
        }
        """
      )
    @strAppend(string: 
      """
      }
      """)
    @strReplaceMultiple(
      search: [
        "{$postAuthorUsername}",
        "{$postFeaturedImageSlug}",
        "{$postCategorySlugPaths}",
        "{$postTagSlugs}",
      ],
      replaceWith: [
        $missingAuthorUsername,
        $missingFeaturedImageSlug,
        $missingCategorySlugPathsAsString,
        $missingTagSlugsAsString,
      ]
    )
    @export(as: "missingResourcesQuery")
}

query GetMissingResourcesFromGraphQLAPI(
  $upstreamServerGraphQLEndpointURL: String!
)
  @depends(on: "ExportGraphQLQueryToFetchMissingResources")
  @skip(if: $requestProducedErrors)
  @skip(if: $postIsMissing)
  @skip(if: $responseHasErrors)
  @include(if: $isAnyResourceMissing)
{    
  missingResourcesData: _sendGraphQLHTTPRequest(input:{
    endpoint: $upstreamServerGraphQLEndpointURL,
    query: $missingResourcesQuery
  })
    @export(as: "missingResourcesData")

  missingResourcesRequestProducedErrors: _isNull(value: $__missingResourcesData)
    @export(as: "requestProducedErrors")
    @remove
}
``` -->


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
