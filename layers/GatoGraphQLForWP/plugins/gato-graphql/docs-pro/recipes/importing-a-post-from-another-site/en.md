# Importing a post from another site

from FB, Twitter, Instagram, Medium or other.

Modify this query! (it's for multiple entries)

```graphql
query CreatePostInputs {
  githubPullRequestEntries: _sendJSONObjectCollectionHTTPRequest(
    input: {
      url: "https://api.github.com/repos/leoloso/PoP/pulls?state=closed&per_page=2&page=75"
    }
  ) @remove

  postInputs: _echo(value: $__githubPullRequestEntries)
    # For each entry: Extract the title and body
    @forEach(
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
            content: $itemBody,
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
    @forEach(
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
    contentSource
    status
  }
}
```
