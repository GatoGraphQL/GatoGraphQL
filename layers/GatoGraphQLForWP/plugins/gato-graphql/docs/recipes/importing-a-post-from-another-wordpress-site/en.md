# Importing a post from another WordPress site

## When the origin server exposes a Gato GraphQL endpoint

Use Gato GraphQL on other end, then can execute this GraphQL query:

```graphql
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
