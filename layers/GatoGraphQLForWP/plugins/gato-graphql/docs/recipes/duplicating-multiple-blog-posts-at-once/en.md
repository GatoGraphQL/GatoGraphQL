# Duplicating multiple blog posts at once

We can extend the previous recipe, to duplicate multiple posts at once, with a single GraphQL request.

## GraphQL query to duplicate multiple posts at once

This GraphQL query duplicates the posts retrieved via the provided `$limit` and `$offset` variables:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  postAuthorID: _echo(value: {})
    @export(as: "postAuthorID")
    @remove

  postCategoryIDs: _echo(value: {})
    @export(as: "postCategoryIDs")
    @remove

  postFeaturedImageID: _echo(value: {})
    @export(as: "postFeaturedImageID")
    @remove

  postTagIDs: _echo(value: {})
    @export(as: "postTagIDs")
    @remove
}

query GetPostsAndExportData($limit: Int! = 5, $offset: Int! = 0)
  @depends(on: "InitializeDynamicVariables")
{
  posts(
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
    id
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

mutation DuplicatePosts
  @depends(on: "GetPostsAndExportData")
{
  createPost(input: {
    status: draft,
    postAuthorID: $postAuthorID,
    postCategoryIDs: $postCategoryIDs,
    contentAs: {
      html: $contentSource
    },
    excerpt: $excerpt
    postFeaturedImageID: $postFeaturedImageID,
    tagsBy: {
      ids: $postTagIDs
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
}
```

## Step by step: creating the GraphQL query

Below is the detailed analysis of how the query works.

### Retrieving the post data

This GraphQL query retrieves the fundamental data for a post:
