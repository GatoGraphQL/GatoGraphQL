# Lesson 4: Duplicating multiple blog posts at once

We can extend the previous tutorial lesson, to duplicate multiple posts with a single GraphQL request.

## GraphQL query to duplicate multiple posts at once

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have the following configuration:

- In block [Payload Types for Mutations](https://gatographql.com/guides/config/returning-a-payload-object-or-the-mutated-entity-for-mutations/), select "Do not use Payload Types for Mutations (i.e. return the mutated entity)" (so that dynamic variable `$createdPostIDs` will contain the IDs of the created posts)
- In block [Mutation Scheme](https://gatographql.com/guides/schema/using-nested-mutations/), select any of the two "Enable Nested Mutations" options (as to use field `_echo` inside a `mutation`)

</div>

This GraphQL query duplicates the posts retrieved via the provided `$limit` and `$offset` variables:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  postInput: _echo(value: [])
    @export(as: "postInput")
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
    postInput: _echo(value: {
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
      @export(as: "postInput", type: LIST)
      @remove
  }
}

mutation DuplicatePosts
  @depends(on: "GetPostsAndExportData")
{
  createdPostIDs: _echo(value: $postInput)
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
    @export(as: "createdPostIDs")
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

Thanks to the [**Field to Input**](https://gatographql.com/extensions/field-to-input/) extension, we can create the input object already on the first operation, and export all the required post data under a single dynamic variable:

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

    postInput: _echo(value: {
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
      @export(as: "postInput")
  }
}
```

Then, in the following mutation, `createPost(input:)` directly receives dynamic variable `$postInput`:

```graphql
mutation DuplicatePost
  @depends(on: "GetPostAndExportData")
{
  createPost(input: $postInput) {
    # ...
  }
}
```

### Retrieving multiple posts

We must convert the query to retrieve the multiple posts to be duplicated:

- Query the posts via `posts(pagination: { limit : $limit, offset: $offset}) { ... }`
- Export `postInput` as a list (i.e. an array containing all the inputs for the queried posts)

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

    postInput: _echo(value: {
      # ...
    })
      @export(
        as: "postInput",
        type: LIST
      )
  }
}
```

### Creating multiple posts in a single GraphQL query

Dynamic variable `$postInput` by now contains an array with all the input data for each of the posts to duplicate:

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

In the Gato GraphQL schema, there is no mutation to create multiple posts:

- Mutation `createPost` receives a single `input` object, not an array of them
- There is no mutation `createPosts`

The solution is to use extensions:

- [**Field Value Iteration and Manipulation**](https://gatographql.com/extensions/field-value-iteration-and-manipulation/) provides directive `@underEachArrayItem` to iterate over all the items in `$postInput`
- [**Field on Field**](https://gatographql.com/extensions/field-on-field/) provides directive `@applyField`, to apply the `createPost` mutation on each iterated-upon item from the array

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

`@underEachArrayItem` is a [composable directive](https://gatographql.com/guides/schema/using-composable-directives/) (or "meta directive", it is a directive which can contain nested directives) that iterates over an array of elements, and applies its nested directive on each of them.

`@underEachArrayItem` helps bridge GraphQL types, as it can make a field that returns a `[String]` value, be applied a directive that receives a `String` value as input (or other combinations).

For instance, in the query below:

- Field `User.capabilities` returns `[String]`
- Directive `@strUpperCase` receives `String`

Thanks to `@underEachArrayItem`, we can convert all capability items to upper case:

```graphql
{
  user(by: { id: 3 }) {
    capabilities
      @underEachArrayItem
        @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "user": {
      "capabilities": [
        "LEVEL_0",
        "READ",
        "READ_PRIVATE_EVENTS",
        "READ_PRIVATE_LOCATIONS"
      ]
    }
  }
}
```

</div>

This GraphQL query passes iterates all items in `$postInput`, assigns each of them to dynamic variable `$input`, injects into the `createPost` mutation, and finally exports the IDs of the created posts under dynamic variable `$createdPostIDs`:

```graphql
mutation DuplicatePosts
  @depends(on: "GetPostsAndExportData")
{
  createdPostIDs: _echo(value: $postInput)
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
    @export(as: "createdPostIDs")
}
```

Finally, we can use dynamic variable `$createdPostIDs` to retrieve the data for the newly-created posts:

```graphql
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
```

### Removing unneeded data

The final step is to remove all fields that are auxiliary (and as such we don't need to print their output in the response) via `@remove`.

The consolidated GraphQL query is:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  postInput: _echo(value: [])
    @export(as: "postInput")
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
    postInput: _echo(value: {
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
      @export(as: "postInput", type: LIST)
      @remove
  }
}

mutation DuplicatePosts
  @depends(on: "GetPostsAndExportData")
{
  createdPostIDs: _echo(value: $postInput)
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
    @export(as: "createdPostIDs")
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
```
