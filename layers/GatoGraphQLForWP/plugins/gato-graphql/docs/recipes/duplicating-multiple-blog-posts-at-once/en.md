# Duplicating multiple blog posts at once

We can extend the previous recipe, to duplicate multiple posts at once, with a single GraphQL request.

## GraphQL query to duplicate multiple posts at once

This GraphQL query duplicates the posts retrieved via the provided `$limit` and `$offset` variables:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  postID: _echo(value: [])
    @export(as: "postID")
    @remove
}

query GetPostsAndExportData($limit: Int! = 5, $offset: Int! = 0)
  @depends(on: "InitializeDynamicVariables")
{
  postsToDuplicate: posts(
    pagination: {
      limit : $limit
      offset: $offset
    }
    sort: {
      by: ID,
      order: ASC
    }
) {
    # Fields not to be duplicated
    id @export(as: "postID", type: LIST)
    slug
    date
    status

    # Fields to be duplicated
    author @export(as: "postAuthorID", type: DICTIONARY) {
      id
    }
    categories @export(as: "postCategoryIDs", type: DICTIONARY) {
      id
    }
    contentSource @export(as: "postContentSource", type: DICTIONARY)
    excerpt @export(as: "postExcerpt", type: DICTIONARY)
    featuredImage @export(as: "postFeaturedImageID", type: DICTIONARY) {
      id
    }
    tags @export(as: "postTagIDs", type: DICTIONARY) {
      id
    }
    title @export(as: "postTitle", type: DICTIONARY)
  }
}

query GeneratePostInputData
  @depends(on: "GetPostsAndExportData")
{
  postInput: _echo(value: $postID)
    # For each entry: Create a new post
    @underEachArrayItem(
      passValueOnwardsAs: "id"
      affectDirectivesUnderPos: [1, 2, 3, 4, 5, 6, 7, 8]
    )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $postAuthorID,
          by: {
            key: $id,
          }
        },
        passOnwardsAs: "authorID"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $postCategoryIDs,
          by: {
            key: $id,
          }
        },
        passOnwardsAs: "categoryIDs"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $postContentSource,
          by: {
            key: $id,
          }
        },
        passOnwardsAs: "contentSource"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $postExcerpt,
          by: {
            key: $id,
          }
        },
        passOnwardsAs: "excerpt"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $postFeaturedImageID,
          by: {
            key: $id,
          }
        },
        passOnwardsAs: "featuredImageID"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $postTagIDs,
          by: {
            key: $id,
          }
        },
        passOnwardsAs: "tagIDs"
      )
      @applyField(
        name: "_objectProperty",
        arguments: {
          object: $postTitle,
          by: {
            key: $id,
          }
        },
        passOnwardsAs: "title"
      )
      @applyField(
        name: "_echo"
        arguments: {
          value: {
            status: draft,
            authorID: $authorID,
            categoryIDs: $categoryIDs,
            contentAs: {
              html: $contentSource
            },
            excerpt: $excerpt
            featuredImageID: $featuredImageID,
            tagsBy: {
              ids: $tagIDs
            },
            title: $title
          }
        },
        setResultInResponse: true
      )
    @export(as: "postInput")
    @remove
}

mutation DuplicatePosts
  @depends(on: "GeneratePostInputData")
{
  createdPostIDs: _echo(value: $postInput)
    # For each entry: Create a new post
    @underEachArrayItem(
      passValueOnwardsAs: "input"
    )
      # The result is the list of IDs of the created posts
      @applyField(
        name: "createPost"
        arguments: {
          input: $input
        },
        setResultInResponse: true
      )
    @export(as: "createdPostIDs")
    @remove
}

query RetrieveCreatedPosts
  @depends(on: "DuplicatePosts")
{
  createdPosts: posts(
    filter: {
      ids: $createdPostIDs,
      status: [draft]
    }
  ) {
    # Fields not to be duplicated
    id
    slug
    date
    status

    # Fields to be duplicated
    author {
      id
    }
    categories {
      id
    }
    contentSource
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
```

## Step by step: creating the GraphQL query

Below is the detailed analysis of how the query works.

### Retrieving the post data

This GraphQL query retrieves the fundamental data for a post:
