# Lesson 4: Duplicating multiple blog posts at once

We can extend the previous tutorial lesson, to duplicate multiple posts with a single GraphQL request.

## GraphQL query to duplicate multiple posts at once

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have the following configuration:

- In block [Payload Types for Mutations](https://gatographql.com/guides/config/returning-a-payload-object-or-the-mutated-entity-for-mutations/), select "Use payload types for mutations, and add fields to query those payload objects"
- In block [Mutation Scheme](https://gatographql.com/guides/schema/using-nested-mutations/), select any of the two "Enable Nested Mutations" options (as to use field `_echo` inside a `mutation`)

</div>

This GraphQL query duplicates the posts retrieved via the provided `$limit` and `$offset` variables:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  postInputs: _echo(value: [])
    @export(as: "postInputs")
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
    rawContent
    excerpt
    featuredImage {
      id
    }
    tags {
      id
    }
    title

    # Already create (and export) the inputs for the mutation
    postInputs: _echo(value: {
      status: draft,
      authorBy: {
        id: $__author
      },
      categoriesBy: {
        ids: $__categories
      },
      contentAs: {
        html: $__rawContent
      },
      excerpt: $__excerpt
      featuredImageBy: {
        id: $__featuredImage
      },
      tagsBy: {
        ids: $__tags
      },
      title: $__title
    })
      @export(as: "postInputs", type: LIST)
      @remove
  }
}

mutation DuplicatePosts
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

## Step by step: creating the GraphQL query

Below is the detailed analysis of how the query works.

### Extending the "Duplicating a blog post" tutorial lesson

The previous lesson employs the following strategy (on the GraphQL query of the second approach):

1. Export the resource IDs from the fields (including connection fields):

```graphql
query GetPostAndExportData {
  post {
    author @export(as: "authorID") {
      id
    }
    categories @export(as: "categoryIDs") {
      id
    }
    rawContent @export(as: "rawContent")
    rawExcerpt @export(as: "excerpt")
    featuredImage @export(as: "featuredImageID") {
      id 
    }
    tags @export(as: "tagIDs") {
      id
    }
    rawTitle @export(as: "title")    
  }
}
```

2. Create the input object for `createPost(input:)` from those dynamic variables:

```graphql
mutation DuplicatePost
  @depends(on: "GetPostAndExportData")
{
  createPost(input: {
    status: draft,
    authorBy: {
      id: $authorID
    },
    categoriesBy: {
      ids: $categoryIDs
    },
    contentAs: {
      html: $rawContent
    },
    excerpt: $excerpt
    featuredImageBy: {
      id: $featuredImageID
    },
    tagsBy: {
      ids: $tagIDs
    },
    title: $title
  }) {
    # ...
  }
}
```

Thanks to the [**Field to Input**](https://gatographql.com/extensions/query-functions/) extension, we can create the input object already on the first operation, and export all the required post data under a single dynamic variable:

```graphql
query GetPostAndExportData {
  post {
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

    postInputs: _echo(value: {
      status: draft,
      authorBy: {
        id: $__author
      },
      categoriesBy: {
        ids: $__categories
      },
      contentAs: {
        html: $__rawContent
      },
      excerpt: $__excerpt
      featuredImageBy: {
        id: $__featuredImage
      },
      tagsBy: {
        ids: $__tags
      },
      title: $__title
    })
      @export(as: "postInputs")
  }
}
```

Then, in the following mutation, `createPost(input:)` directly receives dynamic variable `$postInputs`:

```graphql
mutation DuplicatePost
  @depends(on: "GetPostAndExportData")
{
  createPost(input: $postInputs) {
    # ...
  }
}
```

### Retrieving multiple posts

We must convert the query to retrieve the multiple posts to be duplicated:

- Query the posts via `posts(pagination: { limit : $limit, offset: $offset}) { ... }`
- Export `postInputs` as a list (i.e. an array containing all the inputs for the queried posts)

```graphql
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
    # ...

    postInputs: _echo(value: {
      # ...
    })
      @export(
        as: "postInputs",
        type: LIST
      )
  }
}
```

### Creating multiple posts in a single GraphQL query

Dynamic variable `$postInputs` by now contains an array with all the input data for each of the posts to duplicate:

```json
[
  {
    "status": "draft",
    "authorBy": {
      "id": "2"
    },
    "categoryIDs": [
      1
    ],
    "contentAs": {
      "html": "<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->"
    },
    "excerpt": "Welcome to WordPress. This is your first post. Edit or delete it, then start writing!",
    "featuredImageBy": {
      "id": null
    },
    "tagsBy": {
      "ids": []
    },
    "title": "Hello world!"
  },
  {
    "status": "draft",
    "authorBy": {
      "id": "3"
    },
    "categoryIDs": [
      3
    ],
    "contentAs": {
      "html": "<!-- wp:paragraph -->\n<p>This is a paragraph block. Professionally productize highly efficient results with world-class core competencies. Objectively matrix leveraged architectures vis-a-vis error-free applications. Completely maximize customized portals via fully researched metrics. Enthusiastically generate premier action items through web-enabled e-markets. Efficiently parallel task holistic intellectual capital and client-centric markets.<br><br></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading -->\n<h2>Image Block (Standard)</h2>\n<!-- /wp:heading -->\n\n<!-- wp:image {\"id\":1755} -->\n<figure class=\"wp-block-image\"><img src=\"https://d.pr/i/8pTmgY+\" alt=\"\" class=\"wp-image-1755\"/></figure>\n<!-- /wp:image -->"
    },
    "excerpt": "This is a paragraph block. Professionally productize highly efficient results with world-class core competencies. Objectively matrix leveraged architectures vis-a-vis error-free applications. Completely maximize customized portals via fully researched metrics. Enthusiastically generate premier action items through web-enabled e-markets. Efficiently parallel task holistic intellectual capital and client-centric markets. Image Block (Standard)",
    "featuredImageBy": {
      "id": 1361
    },
    "tagsBy": {
      "ids": [
        11,
        10
      ]
    },
    "title": "Released v0.6, check it out"
  }
]
```

Finally, we call bulk mutation `createPosts` to create all the posts passing the data for the exported inputs:

```graphql
mutation DuplicatePosts
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

### Removing unneeded data

The final step is to remove all fields that are auxiliary (and as such we don't need to print their output in the response) via `@remove`.

The consolidated GraphQL query is:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  postInputs: _echo(value: [])
    @export(as: "postInputs")
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
    rawContent
    excerpt
    featuredImage {
      id
    }
    tags {
      id
    }
    title

    # Already create (and export) the inputs for the mutation
    postInputs: _echo(value: {
      status: draft,
      authorBy: {
        id: $__author
      },
      categoriesBy: {
        ids: $__categories
      },
      contentAs: {
        html: $__rawContent
      },
      excerpt: $__excerpt
      featuredImageBy: {
        id: $__featuredImage
      },
      tagsBy: {
        ids: $__tags
      },
      title: $__title
    })
      @export(as: "postInputs", type: LIST)
      @remove
  }
}

mutation DuplicatePosts
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
