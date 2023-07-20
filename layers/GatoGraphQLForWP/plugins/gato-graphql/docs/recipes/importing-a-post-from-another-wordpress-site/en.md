# Importing a post from another WordPress site

## When the origin server exposes a Gato GraphQL endpoint

Use Gato GraphQL on other end, then can execute this GraphQL query:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  defaultResponseHasErrors: _echo(value: false)
    @export(as: "responseHasErrors")
    @remove
  defaultPostIsMissing: _echo(value: false)
    @export(as: "postIsMissing")
    @remove
}

query ConnectToGraphQLAPI($postId: ID!)
  @depends(on: "InitializeDynamicVariables")
{
  externalData: _sendGraphQLHTTPRequest(input:{
    endpoint: "https://upstreamsite.com/graphql",
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
      slug
      name
    }
    tags {
      id
      slug
      name
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

query ExportInputsForMutation($postId: ID!)
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

  postFeaturedImageSlug: _objectProperty(
    object: $__postData,
    by: { path: "featuredImage.slug" }
  )
    @export(as: "postFeaturedImageSlug")
    @remove

  postAuthorSlug: _objectProperty(
    object: $__postData,
    by: { path: "author.slug" }
  )
    @export(as: "postAuthorSlug")
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

  postCategoryTags: _objectProperty(
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

mutation ImportPost
  @depends(on: "ExportInputsForMutation")
  @skip(if: $requestHasErrors)
  @skip(if: $dataHasErrors)
  @skip(if: $postIsMissing)
{
  createPost(input: {
    status: draft,
    slug: $postSlug
    title: $postTitle
    contentAs: {
      html: $postContent
    },
    excerpt: $postExcerpt
    # authorID: $authorID,
    # featuredImageID: $featuredImageID,
    # categoryIDs: $categoryIDs,
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

Use:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  authorID: _echo(value: null)
    @export(as: "authorID")
    @remove

  categoryIDs: _echo(value: [])
    @export(as: "categoryIDs")
    @remove

  featuredImageID: _echo(value: null)
    @export(as: "featuredImageID")
    @remove

  tagIDs: _echo(value: [])
    @export(as: "tagIDs")
    @remove
}

query GetPostAndExportData($postId: ID!)
  @depends(on: "InitializeDynamicVariables")
{
  post(by: { id : $postId }) {
    # Fields not to be imported
    id
    slug
    date
    status

    # Fields to be imported
    author {
      id @export(as: "authorID")
    }
    categories {
      id @export(as: "categoryIDs", type: LIST)
    }
    rawContent @export(as: "rawContent")
    excerpt @export(as: "excerpt")
    featuredImage {
      id @export(as: "featuredImageID")
    }
    tags {
      id @export(as: "tagIDs", type: LIST)
    }
    title @export(as: "title")
  }
}

mutation DuplicatePost
  @depends(on: "GetPostAndExportData")
{
  createPost(input: {
    status: draft,
    authorID: $authorID,
    categoryIDs: $categoryIDs,
    contentAs: {
      html: $rawContent
    },
    excerpt: $excerpt
    featuredImageID: $featuredImageID,
    tagsBy: {
      ids: $tagIDs
    },
    title: $title
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
      # Fields not to be imported
      id
      slug
      date
      status

      # Fields to be imported
      author {
        id
      }
      categories {
        id
      }
      rawContent
      excerpt
      featuredImage {
        id
      }
      tags {
        id
      }
      title
    }
  }
}
```

Before:

```graphql
query CreatePostInputs {
  githubPullRequestEntries: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: "https://api.github.com/repos/leoloso/PoP/pulls?state=closed&per_page=2&page=75"
    }
  ) @remove

  postInputs: _echo(value: $__githubPullRequestEntries)
    # For each entry: Extract the title and body
    @underEachArrayItem(
      affectDirectivesUnderPos: [1, 2, 3],
      passValueOnwardsAs: "item"
    )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $item,
          by: {
            key: "title"
          }
        },
        passOnwardsAs: "itemTitle"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $item,
          by: {
            key: "body"
          }
        },
        passOnwardsAs: "itemBody"
      )
      @applyField(
        name: "_echo",
        arguments: {
          value: {
            title: $itemTitle,
            contentAs: { html: $itemBody },
            status: draft
          }
        },
        setResultInResponse: true
      )
    @export(as: "postInputs")
}

mutation LogUserIn {
  loginUser(by: {
    credentials: {
      usernameOrEmail: "admin",
      password: "password"
    }
  }) @remove {
    status
    user {
      id
      username
    }
  }
}

mutation ImportContentAsNewPosts
  @depends(on: "CreatePostInputs")
{
  createdPostIDs: _echo(value: $postInputs)
    # For each entry: Create a new post
    @underEachArrayItem(
      passValueOnwardsAs: "postInput"
    )
      # The result is the list of IDs of the created posts
      @applyField(
        name: "createPost"
        arguments: {
          input: $postInput
        },
        setResultInResponse: true
      )
    @export(as: "createdPostIDs")
    # Can't print, because BrainFaker will generate a random ID each time
    @remove
}

mutation LogUserOut {
  logoutUser @remove {
    status
    userID
  }
}

query RetrieveCreatedPosts
  @depends(on: ["LogUserIn", "ImportContentAsNewPosts", "LogUserOut"])
{
  posts(filter: { ids: $createdPostIDs, status: [draft] }) {
    # Can't print, because BrainFaker will generate a random ID each time
    # id
    title
    rawContent
    status
  }
}
```
