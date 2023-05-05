# Reverting mutations in case of error

Say can also do:

"Testing mutations and restoring the original value"

Remove block by type:

```graphql
query CreateVars {
  foundPosts: posts(filter: { search: "\"<!-- /wp:columns -->\"" } ) {
    id @export(as: "postIDs")
    contentSource
    originalInputs: _echo(value: {
      id: $__id,
      content: $__contentSource
    }) @export(as: "originalInputs")
  }
}

mutation RemoveBlock
  @depends(on: "CreateVars")
{
  posts(filter: { ids: $postIDs } ) {
    id
    contentSource
    adaptedContentSource: _strRegexReplace(
      in: $__contentSource,
      regex: "#(<!-- wp:columns -->[\\s\\S]+<!-- /wp:columns -->)#",
      replaceWith: ""
    )
    update(input: {
      content: $__adaptedContentSource,
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        id
        contentSource
      }
    }
  }
}

mutation RestorePosts
  @depends(on: "CreateVars")
{
  restorePosts: _echo(value: $originalInputs)
    @forEach(passValueOnwardsAs: "input")
      @applyField(
        name: "updatePost"
        arguments: { input: $input }
      )
}

query CheckRestoredPosts
  @depends(on: "CreateVars")
{
  restoredPosts: posts(filter: { ids: $postIDs } ) {
    id
    contentSource
  }
}

query RemoveBlockAndThenRestorePosts
  @depends(on: ["RemoveBlock", "RestorePosts", "CheckRestoredPosts"])
{
  id
}
```

Detailed:

```graphql
query CreateVars(
  $removeBlockType: String!
) {
  regex: _sprintf(
    string: "#(<!-- %1$s -->[\\s\\S]+<!-- /%1$s -->)#",
    values: [$removeBlockType]
  ) @export(as: "regex")

  search: _sprintf(
    string: "\"<!-- /%1$s -->\"",
    values: [$removeBlockType]
  )
  
  foundPosts: posts(filter: { search: $__search } ) {
    id @export(as: "postIDs")
    contentSource
    originalInputs: _echo(value: {
      id: $__id,
      content: $__contentSource
    }) @export(as: "originalInputs")
  }
}

mutation RemoveBlock
  @depends(on: "CreateVars")
{
  posts(filter: { ids: $postIDs } ) {
    id
    contentSource
    adaptedContentSource: _strRegexReplace(
      in: $__contentSource,
      regex: $regex,
      replaceWith: ""
    )
    update(input: {
      content: $__adaptedContentSource,
    }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        id
        contentSource
      }
    }
  }
}

mutation RestorePosts
  @depends(on: "CreateVars")
{
  restorePosts: _echo(value: $originalInputs)
    @forEach(passValueOnwardsAs: "input")
      @applyField(
        name: "updatePost"
        arguments: { input: $input }
      )
}

query CheckRestoredPosts
  @depends(on: "CreateVars")
{
  restoredPosts: posts(filter: { ids: $postIDs } ) {
    id
    contentSource
  }
}

query RemoveBlockAndThenRestorePosts
  @depends(on: ["RemoveBlock", "RestorePosts", "CheckRestoredPosts"])
{
  id
}
```

and vars:

```json
{
  "removeBlockType": "wp:columns"
}
```
