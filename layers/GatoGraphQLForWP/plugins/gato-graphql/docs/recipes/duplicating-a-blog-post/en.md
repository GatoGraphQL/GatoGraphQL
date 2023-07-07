# Duplicating a blog post

You can think of GraphQL as a Swiss Army Knife for dealing with data in a WordPress site, as it allows to retrieve, manipulate and store again any piece of data, in any desired way.

An example is the ability to duplicate a blog post. Let's explore how to do this.

## Retrieving the post data

This GraphQL query retrieves the fundamental data for a post:

```graphql
query GetPost($postId: ID!) {
  post(by: { id : $postId }) {
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

Executing the query (passing the `$postId` variable), the response may be:

```json
{
  "data": {
    "post": {
      "id": 25,
      "slug": "public-or-private-api-mode-for-extra-security",
      "date": "2020-12-12T04:06:52+00:00",
      "author": {
        "id": 2
      },
      "categories": [
        {
          "id": 4
        },
        {
          "id": 3
        },
        {
          "id": 2
        }
      ],
      "contentSource": "<!-- wp:heading -->\n<h2>Verse Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Write poetry and other literary expressions honoring all spaces and line-breaks.</pre>\n<!-- /wp:verse -->\n\n<!-- wp:heading -->\n<h2>Table Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:table {\"className\":\"is-style-stripes\"} -->\n<figure class=\"wp-block-table is-style-stripes\"><table><tbody><tr><td>Row 1 Column 1</td><td>Row 1 Column 2</td></tr><tr><td>Row 2 Column 1</td><td>Row 2 Column 2</td></tr><tr><td>Row 3 Column 1</td><td>Row 3 Column 2</td></tr></tbody></table></figure>\n<!-- /wp:table -->\n\n<!-- wp:heading -->\n<h2>Separator Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:separator -->\n<hr class=\"wp-block-separator\"/>\n<!-- /wp:separator -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Spacer Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:spacer -->\n<div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->",
      "excerpt": "Verse Block Write poetry and other literary expressions honoring all spaces and line-breaks. Table Block Row 1 Column 1 Row 1 Column 2 Row 2 Column 1 Row 2 Column 2 Row 3 Column 1 Row 3 Column 2 Separator Block Spacer Block",
      "featuredImage": {
        "id": 362
      },
      "tags": [
        {
          "id": 12
        },
        {
          "id": 7
        }
      ],
      "title": "Public or Private API mode, for extra security"
    }
  }
}
```

Notice that some fields are meant to be duplicated (including the author, title, and content), while others are not (such as the id, slug and creation date).

## Duplicating the post

With the **Multiple Query Execution** extension, we are able to export the post's data items, and inject them again into another `query` or `mutation` in the same GraphQL document.

The following query creates a pipeline of two operations in the GraphQL document (`GetPostAndExportData` and `DuplicatePost`), which can share data with each other:

- `DuplicatePost` indicates to execute `GetPostAndExportData` first, via directive `@depends`
- `GetPostAndExportData` exports data via directive `@export` into dynamic variables
- `DuplicatePost` then reads the dynamic variables, and inputs them into the `createPost` mutation

```graphql
query GetPostAndExportData($postId: ID!) {
  post(by: { id : $postId }) {
    # Fields not to be duplicated
    id
    slug
    date
    status

    # Fields to be duplicated
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

In the response, we can visualize that the fields of the new post are indeed the same:

```json
{
  "data": {
    "post": {
      "id": 25,
      "slug": "public-or-private-api-mode-for-extra-security",
      "date": "2020-12-12T04:06:52+00:00",
      "status": "publish",
      "author": {
        "id": 2
      },
      "categories": [
        {
          "id": 4
        },
        {
          "id": 3
        },
        {
          "id": 2
        }
      ],
      "contentSource": "<!-- wp:heading -->\n<h2>Verse Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Write poetry and other literary expressions honoring all spaces and line-breaks.</pre>\n<!-- /wp:verse -->\n\n<!-- wp:heading -->\n<h2>Table Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:table {\"className\":\"is-style-stripes\"} -->\n<figure class=\"wp-block-table is-style-stripes\"><table><tbody><tr><td>Row 1 Column 1</td><td>Row 1 Column 2</td></tr><tr><td>Row 2 Column 1</td><td>Row 2 Column 2</td></tr><tr><td>Row 3 Column 1</td><td>Row 3 Column 2</td></tr></tbody></table></figure>\n<!-- /wp:table -->\n\n<!-- wp:heading -->\n<h2>Separator Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:separator -->\n<hr class=\"wp-block-separator\"/>\n<!-- /wp:separator -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Spacer Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:spacer -->\n<div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->",
      "excerpt": "Verse Block Write poetry and other literary expressions honoring all spaces and line-breaks. Table Block Row 1 Column 1 Row 1 Column 2 Row 2 Column 1 Row 2 Column 2 Row 3 Column 1 Row 3 Column 2 Separator Block Spacer Block",
      "featuredImage": {
        "id": 362
      },
      "tags": [
        {
          "id": 12
        },
        {
          "id": 7
        }
      ],
      "title": "Public or Private API mode, for extra security"
    },
    "createPost": {
      "status": "SUCCESS",
      "errors": null,
      "post": {
        "id": 1207,
        "slug": "public-or-private-api-mode-for-extra-security-2",
        "date": "2023-07-07T02:06:17+00:00",
        "status": "draft",
        "author": {
          "id": 2
        },
        "categories": [
          {
            "id": 4
          },
          {
            "id": 3
          },
          {
            "id": 2
          }
        ],
        "contentSource": "<!-- wp:heading -->\n<h2>Verse Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Write poetry and other literary expressions honoring all spaces and line-breaks.</pre>\n<!-- /wp:verse -->\n\n<!-- wp:heading -->\n<h2>Table Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:table {\"className\":\"is-style-stripes\"} -->\n<figure class=\"wp-block-table is-style-stripes\"><table><tbody><tr><td>Row 1 Column 1</td><td>Row 1 Column 2</td></tr><tr><td>Row 2 Column 1</td><td>Row 2 Column 2</td></tr><tr><td>Row 3 Column 1</td><td>Row 3 Column 2</td></tr></tbody></table></figure>\n<!-- /wp:table -->\n\n<!-- wp:heading -->\n<h2>Separator Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:separator -->\n<hr class=\"wp-block-separator\"/>\n<!-- /wp:separator -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Spacer Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:spacer -->\n<div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->",
        "excerpt": "Verse Block Write poetry and other literary expressions honoring all spaces and line-breaks. Table Block Row 1 Column 1 Row 1 Column 2 Row 2 Column 1 Row 2 Column 2 Row 3 Column 1 Row 3 Column 2 Separator Block Spacer Block",
        "featuredImage": {
          "id": 362
        },
        "tags": [
          {
            "id": 12
          },
          {
            "id": 7
          }
        ],
        "title": "Public or Private API mode, for extra security"
      }
    }
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Multiple Query Execution pipelines can help us manage the GraphQL document into a series a logical/atomic units:

- There is no limit in how many operations can be added to the pipeline
- Any operation can declare more than one dependency (eg: `@depends(on: ["SomePreviousOp", "AnotherPreviousOp"]`))
- Both `query` and `mutation` operations can depend on each other

</div>

---

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

@todo Add createPost(input.author) as sensitive field!

</div>

## Duplicating a post with empty fields

## Duplicating meta

## Duplicating blog posts in bulk
