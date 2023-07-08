# Duplicating multiple blog posts at once

We can extend the previous recipe, to duplicate multiple posts at once, with a single GraphQL request.

## GraphQL query to duplicate multiple posts at once

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
    contentSource
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
      authorID: $__author,
      categoryIDs: $__categories,
      contentAs: {
        html: $__contentSource
      },
      excerpt: $__excerpt
      featuredImageID: $__featuredImage,
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

### Introducing **Field to Input** to the query from the "Duplicating a blog post" recipe

The previous recipe employs the following strategy on the GraphQL query for the second approach:

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
    contentSource @export(as: "contentSource")
    excerpt @export(as: "excerpt")
    featuredImage @export(as: "featuredImageID") {
      id 
    }
    tags @export(as: "tagIDs") {
      id
    }
    title @export(as: "title")    
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
  }) {
    # ...
  }
}
```

Thanks to the **Field to Input** extension, we can create the input object already on the first operation, and export all the required post data under a single dynamic variable:

```graphql
query GetPostAndExportData {
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

    postInput: _echo(value: {
      status: draft,
      authorID: $__author,
      categoryIDs: $__categories,
      contentAs: {
        html: $__contentSource
      },
      excerpt: $__excerpt
      featuredImageID: $__featuredImage,
      tagsBy: {
        ids: $__tags
      },
      title: $__title
    })
      @export(as: "postInput")
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

The **Field to Input** extension allows us to obtain the value of a field and [input it into another field](https://gatographql.com/guides/schema/using-field-to-input/) in that same operation.

The field to obtain the value from is referenced using the "Variable" syntax `$`, and `__` before the field alias or name:

```graphql
{
  posts {
    excerpt

    # Referencing previous field with name "excerpt"
    isEmptyExcerpt: _isEmpty(value: $__excerpt)

    # Referencing previous field with alias "isEmptyExcerpt"
    isNotEmptyExcerpt: _not(value: $__isEmptyExcerpt)
  }
}
```

</div>

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

Let's iterate on this idea, but 



Tips: Publish it as private

https://mysite.com/graphql/mutations-nested-return-entity/

![Schema Configuration 'Mutations: nested + return entity'](../../images/recipes/schema-config-nested-mutations-and-return-entity.png "Schema Configuration 'Mutations: nested + return entity'"){.width-640}

