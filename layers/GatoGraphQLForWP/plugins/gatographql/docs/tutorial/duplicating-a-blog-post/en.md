# Duplicating a blog post

Duplicating a post is an example of Gato GraphQL's ability to retrieve, manipulate and store again data in the site.

## GraphQL query to duplicate blog post

This GraphQL query duplicates the post indicated by variable `$postId`:

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
    rawContent @export(as: "rawContent")
    rawExcerpt @export(as: "excerpt")
    featuredImage {
      id @export(as: "featuredImageID")
    }
    tags {
      id @export(as: "tagIDs", type: LIST)
    }
    rawTitle @export(as: "title")
  }
}

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

### Retrieving the post data

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
      "rawContent": "<!-- wp:heading -->\n<h2>Verse Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Write poetry and other literary expressions honoring all spaces and line-breaks.</pre>\n<!-- /wp:verse -->\n\n<!-- wp:heading -->\n<h2>Table Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:table {\"className\":\"is-style-stripes\"} -->\n<figure class=\"wp-block-table is-style-stripes\"><table><tbody><tr><td>Row 1 Column 1</td><td>Row 1 Column 2</td></tr><tr><td>Row 2 Column 1</td><td>Row 2 Column 2</td></tr><tr><td>Row 3 Column 1</td><td>Row 3 Column 2</td></tr></tbody></table></figure>\n<!-- /wp:table -->\n\n<!-- wp:heading -->\n<h2>Separator Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:separator -->\n<hr class=\"wp-block-separator\"/>\n<!-- /wp:separator -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Spacer Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:spacer -->\n<div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->",
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

### Duplicating the post: First approach

With the [**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/) extension, we are able to export the post's data items, and inject them again into another `query` or `mutation` in the same GraphQL document.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

[**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/) allows us to [execute complex functionality within a single request](https://gatographql.com/guides/schema/executing-multiple-queries-concurrently/), and better organize the logic by splitting the GraphQL document into a series a logical/atomic units:

- There is no limit in how many operations can be added to the pipeline
- Any operation can declare more than one dependency:

```graphql
query SomeQuery @depends(on: ["SomePreviousOp", "AnotherPreviousOp"]) {
  # ...
}
```

- Any operation can depend on another operation, which itself depends on another operation, and so on:

```graphql
query ExecuteFirst
  # ...
}
query ExecuteSecond @depends(on: ["ExecuteFirst"]) {
  # ...
}
query ExecuteThird @depends(on: ["ExecuteSecond"]) {
  # ...
}
```

- We can execute any of the operations in the document:
  - `?operationName=ExecuteThird` executes `ExecuteFirst` > `ExecuteSecond` > `ExecuteThird`
  - `?operationName=ExecuteSecond` executes `ExecuteFirst` > `ExecuteSecond`
  - `?operationName=ExecuteFirst` executes `ExecuteFirst`

- When `@depends` receives only one operation, it can receive a `String` (instead of `[String]`):

```graphql
query ExecuteFirst
  # ...
}
query ExecuteSecond @depends(on: "ExecuteFirst") {
  # ...
}
```

- Both `query` and `mutation` operations can depend on each other:

```graphql
query GetAndExportData
  # ...
}
mutation MutateData @depends(on: "GetAndExportData") {
  # ...
}
query CountMutatedResults @depends(on: "MutateData") {
  # ...
}
```

- [Dynamic variables](https://gatographql.com/guides/augment/dynamic-variables/) do not need to be declared in the operation
- Via input `@export(type:)` we can select the output of the data exported into the dynamic variable:
  - `SINGLE` (default): A single field value
  - `LIST`: An array containing the field value of multiple resources
  - `DICTIONARY`: A dictionary containing the field value of multiple resources, with key: `${resource ID}` and value: `${field value}`

</div>

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
    rawContent @export(as: "rawContent")
    rawExcerpt @export(as: "excerpt")
    featuredImage {
      id @export(as: "featuredImageID")
    }
    tags {
      id @export(as: "tagIDs", type: LIST)
    }
    rawTitle @export(as: "title")
  }
}

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
      "rawContent": "<!-- wp:heading -->\n<h2>Verse Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Write poetry and other literary expressions honoring all spaces and line-breaks.</pre>\n<!-- /wp:verse -->\n\n<!-- wp:heading -->\n<h2>Table Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:table {\"className\":\"is-style-stripes\"} -->\n<figure class=\"wp-block-table is-style-stripes\"><table><tbody><tr><td>Row 1 Column 1</td><td>Row 1 Column 2</td></tr><tr><td>Row 2 Column 1</td><td>Row 2 Column 2</td></tr><tr><td>Row 3 Column 1</td><td>Row 3 Column 2</td></tr></tbody></table></figure>\n<!-- /wp:table -->\n\n<!-- wp:heading -->\n<h2>Separator Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:separator -->\n<hr class=\"wp-block-separator\"/>\n<!-- /wp:separator -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Spacer Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:spacer -->\n<div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->",
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
        "rawContent": "<!-- wp:heading -->\n<h2>Verse Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:verse -->\n<pre class=\"wp-block-verse\">Write poetry and other literary expressions honoring all spaces and line-breaks.</pre>\n<!-- /wp:verse -->\n\n<!-- wp:heading -->\n<h2>Table Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:table {\"className\":\"is-style-stripes\"} -->\n<figure class=\"wp-block-table is-style-stripes\"><table><tbody><tr><td>Row 1 Column 1</td><td>Row 1 Column 2</td></tr><tr><td>Row 2 Column 1</td><td>Row 2 Column 2</td></tr><tr><td>Row 3 Column 1</td><td>Row 3 Column 2</td></tr></tbody></table></figure>\n<!-- /wp:table -->\n\n<!-- wp:heading -->\n<h2>Separator Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:separator -->\n<hr class=\"wp-block-separator\"/>\n<!-- /wp:separator -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Spacer Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:spacer -->\n<div style=\"height:100px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->",
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

### Issues with the first approach

The query above will return an error when a connection field is empty, as the dynamic variable will not be exported.

For instance, when the post to duplicate does not have a featured image, field `featuredImage` will be `null`, and so `id @export(as: "featuredImageID")` will never be executed:

```graphql
{
  post {
    featuredImage {
      id @export(as: "featuredImageID")
    }
  }
}
```

As dynamic variable `$featuredImageID` will then not exist, the response will give an error:

```json
{
  "errors": [
    {
      "message": "No value has been exported for dynamic variable 'featuredImageID'",
      "locations": [
        {
          "line": 39,
          "column": 22
        }
      ]
    }
  ],
  "data": {
    // ...
  }
}
```

The following two approaches deal with this problem.

### Duplicating the post: Second approach

Connection fields also store a value in Gato GraphQL. When first resolved, these fields will contain the ID(s) of the resource(s) they point to (either the ID of the linked resource, or an array with IDs of the linked resources). Only later on, when the connection is resolved, will the ID(s) be replaced with the actual resource object(s).

For instance, in the following query:

```graphql
{
  post {
    featuredImage {
      id
    }
    tags {
      id
    }
  }
}
```

...field `featuredImage` will initially contain `362` (that's the featured image's ID), and field `tags` will contain array `[12, 7]` (those are the tag IDs).

When the value to export is an ID (such as `$featuredImageID`) or array of IDs (such as `$tagIDs`), we can benefit from this characteristic and already export the ID(s) in the connection field.

Instead of doing this:

```graphql
{
  post {
    featuredImage {
      id @export(as: "featuredImageID")
    }
    tags {
      id @export(as: "tagIDs", type: LIST)
    }
  }
}
```

...we can do this:

```graphql
{
  post {
    featuredImage @export(as: "featuredImageID") {
      id 
    }
    tags @export(as: "tagIDs") {
      id
    }
  }
}
```

_(Notice that argument `type: LIST` was removed when exporting `$tagIDs`, as the connection field is already a list.)_

Now, these dynamic variables will always be exported, with value:

- `null` for `$featuredImageID` when the post has no featured image
- the empty array `[]` for `$tagIDs` when the post has no tags

Adapting the GraphQL query, it now becomes:

```graphql
query GetPostAndExportData($postId: ID!) {
  post(by: { id : $postId }) {
    # Fields not to be duplicated
    id
    slug
    date
    status

    # Fields to be duplicated
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

...the response now works properly:

```json
{
  "data": {
    "post": {
      "id": 23,
      "slug": "graphql-or-rest-you-can-have-both",
      "date": "2020-12-12T04:04:54+00:00",
      "status": "publish",
      "author": {
        "id": 2
      },
      "categories": [
        {
          "id": 1
        }
      ],
      "rawContent": "<!-- wp:heading -->\n<h2>Audio Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:audio -->\n<figure class=\"wp-block-audio\"><audio controls src=\"https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3\"></audio></figure>\n<!-- /wp:audio -->\n\n<!-- wp:heading -->\n<h2>Video Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:video -->\n<figure class=\"wp-block-video\"><video controls src=\"https://archive.org/download/SlowMotionFlame/slomoflame_512kb.mp4\"></video></figure>\n<!-- /wp:video -->\n\n<!-- wp:heading -->\n<h2>Custom HTML Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:html -->\n<strong>This is a HTML block.</strong>\n<!-- /wp:html -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Preformatted Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:preformatted -->\n<pre class=\"wp-block-preformatted\">This is some preformatted text. Preformatted text keeps your s p a c e s, tabs and<br>linebreaks as they are.</pre>\n<!-- /wp:preformatted -->",
      "excerpt": "Audio Block Video Block Custom HTML Block This is a HTML block. Preformatted Block This is some preformatted text. Preformatted text keeps your s p a c e s, tabs andlinebreaks as they are.",
      "featuredImage": null,
      "tags": [],
      "title": "GraphQL or REST? Why not both?"
    },
    "createPost": {
      "status": "SUCCESS",
      "errors": null,
      "post": {
        "id": 1209,
        "slug": "graphql-or-rest-why-not-both",
        "date": "2023-07-07T03:24:31+00:00",
        "status": "draft",
        "author": {
          "id": 2
        },
        "categories": [
          {
            "id": 1
          }
        ],
        "rawContent": "<!-- wp:heading -->\n<h2>Audio Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:audio -->\n<figure class=\"wp-block-audio\"><audio controls src=\"https://freemusicarchive.org/file/music/WFMU/Broke_For_Free/Directionless_EP/Broke_For_Free_-_01_-_Night_Owl.mp3\"></audio></figure>\n<!-- /wp:audio -->\n\n<!-- wp:heading -->\n<h2>Video Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:video -->\n<figure class=\"wp-block-video\"><video controls src=\"https://archive.org/download/SlowMotionFlame/slomoflame_512kb.mp4\"></video></figure>\n<!-- /wp:video -->\n\n<!-- wp:heading -->\n<h2>Custom HTML Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:html -->\n<strong>This is a HTML block.</strong>\n<!-- /wp:html -->\n\n<!-- wp:heading {\"className\":\"has-top-margin\"} -->\n<h2 class=\"has-top-margin\">Preformatted Block</h2>\n<!-- /wp:heading -->\n\n<!-- wp:preformatted -->\n<pre class=\"wp-block-preformatted\">This is some preformatted text. Preformatted text keeps your s p a c e s, tabs and<br>linebreaks as they are.</pre>\n<!-- /wp:preformatted -->",
        "excerpt": "Audio Block Video Block Custom HTML Block This is a HTML block. Preformatted Block This is some preformatted text. Preformatted text keeps your s p a c e s, tabs andlinebreaks as they are.",
        "featuredImage": null,
        "tags": [],
        "title": "GraphQL or REST? Why not both?"
      }
    }
  }
}
```

### Issues with the second approach

The solution above only works for exporting IDs (as these are the values stored in the connection fields). It will not work for anything else, such as tag slugs:

```graphql
{
  post {
    tags {
      slug @export(as: "tagSlugs", type: LIST)
    }
  }
}
```

The following approach deals with this problem.

### Duplicating the post: Third approach

We can execute an additional operation at the beginning to initialize each of the dynamic variables with a `null` or empty value (via global field `_echo` from the [**PHP Functions Via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension).

Then, each dynamic variable will always be exported, at least once. When the field value is not empty, then it will be exported again, and this second value will override the first one.

In this query, dynamic variable `$tagSlugs` is initialized with an empty array, and will then be exported again if the post has slugs:

```graphql
query InitializeDynamicVariables {
  tagSlugs: _echo(value: []) @export(as: "tagSlugs")
}

query ExportData
  @depends(on: "InitializeDynamicVariables")
{
  post {
    tags {
      slug @export(as: "tagSlugs", type: LIST)
    }
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- Global field `_echo` returns anything that is provided, whatever its type:

```graphql
query {
  string: _echo(value: "page")
  int: _echo(value: 3)
  bool: _echo(value: true)
  jsonObject: _echo(value: {
    name: "Robert"
    surname: "Spencer"
  })
  null: _echo(value: null)
  arrayOfString: _echo(value: ["something", "new"])
  arrayOfInt: _echo(value: [1, 3, 5])
  arrayOfArraysOfBool: _echo(value: [[true, false], [false]])
  arrayOfMixed: _echo(value: [1, true, "string", [1, 3, 5], {key: "value"}])
}
```

</div>

This solution is more comprehensive than the previous one, as it works to export any type of data (whether IDs or other).

Adapting the GraphQL query, it now becomes:

```graphql
query InitializeDynamicVariables {
  authorID: _echo(value: null) @export(as: "authorID")
  categoryIDs: _echo(value: []) @export(as: "categoryIDs")
  featuredImageID: _echo(value: null) @export(as: "featuredImageID")
  tagIDs: _echo(value: []) @export(as: "tagIDs")}

query GetPostAndExportData($postId: ID!)
  @depends(on: "InitializeDynamicVariables")
{
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
    rawContent @export(as: "rawContent")
    rawExcerpt @export(as: "excerpt")
    featuredImage {
      id @export(as: "featuredImageID")
    }
    tags {
      id @export(as: "tagIDs", type: LIST)
    }
    rawTitle @export(as: "title")
  }
}

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

### Warnings in the third approach

Whenever a dynamic variable is exported more than once, the GraphQL engine by default appends a warning to the GraphQL response:

```json
{
  "extensions": {
    "warnings": [
      {
        "message": "Dynamic variable with name 'tagSlugs' had already been set, had its value overridden",
        "locations": [
          {
            "line": 22,
            "column": 21
          }
        ]
      }
    ]
  },
  "data": {
    // ...
  }
}
```

The consolidated approach next deals with this warning.

### Duplicating the post: Consolidated approach

We use the GraphQL query from the previous approach, and optimize it by:

- Not raising the "duplicate dynamic variable" warning
- Not printing the values for the fields in `InitializeDynamicVariables` in the GraphQL response (as there's no need for them, they are just helper fields)

We deal with these elements by (respectively):

- Adding directive `@configureWarningsOnExportingDuplicateVariable(enabled: false)` to the operation, which disables raising the warning
- Adding the `@remove` directive (from the [**Field Response Removal**](https://gatographql.com/extensions/field-response-removal/) extension) to each of the fields to remove

This is the consolidated GraphQL query to duplicate a post:

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
    rawContent @export(as: "rawContent")
    rawExcerpt @export(as: "excerpt")
    featuredImage {
      id @export(as: "featuredImageID")
    }
    tags {
      id @export(as: "tagIDs", type: LIST)
    }
    rawTitle @export(as: "title")
  }
}

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
