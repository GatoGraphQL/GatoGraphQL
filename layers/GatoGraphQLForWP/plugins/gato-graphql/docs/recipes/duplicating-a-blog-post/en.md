# Duplicating a blog post

This GraphQL query retrieves the fundamental data for a post.

```graphql
query GetPost($id: ID!) {
  post(by: { id : $id }) {
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

With the **Multiple Query Execution** extension, we are able to export these data items, and inject them again into the `createPost` mutation, to create a new post:

```graphql
query GetPostAndExportData($id: ID!) {
  post(by: { id : $id }) {
    author {
      id @export(as: "authorID")
    }
    categories {
      id @export(as: "categoryIDs", type: LIST)
    }
    contentSource @export(as: "contentSource")
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
    author: $authorID,
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
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

@todo Add createPost(input.author) as sensitive field!

</div>
