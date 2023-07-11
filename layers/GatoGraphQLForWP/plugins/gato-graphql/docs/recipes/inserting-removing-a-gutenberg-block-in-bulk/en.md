# Inserting/Removing a (Gutenberg) block in bulk

We can update posts by adapting their (Gutenberg) block's HTML content.

## Inserting a block in bulk

Making use of the delimiter comment in a Gutenberg block, after 3 paragraph blocks

Mention it's very crude, and it breaks with nested blocks

inject-block-after-several-paragraph-blocks.gql:

```graphql
mutation InjectBlock(
  $postIDs: [ID!]!
) {
  posts(filter: { ids: $postIDs } ) {
    id
    contentSource
    adaptedContentSource: _strRegexReplace(
      in: $__contentSource,
      searchRegex: "#(<!-- /wp:paragraph -->[\\s\\S]+<!-- /wp:paragraph -->[\\s\\S]+<!-- /wp:paragraph -->)#U",
      replaceWith: "$1<!-- wp:video -->\n<figure class=\"wp-block-video\"><video controls src=\"https://archive.org/download/SlowMotionFlame/slomoflame_512kb.mp4\"></video></figure>\n<!-- /wp:video -->",
      limit: 1
    )
    update(input: {
      contentAs: { html: $__adaptedContentSource },
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
```

vars:

```json
{
  "postIDs": [664]
}
```

detailed:

```graphql
query CreateRegex(
  $searchBlockType: String! = "wp:paragraph"
  $injectAfterBlockCount: Int!
  $injectBlockMarkup: String!
) {
  endingBlockMarkup: _sprintf(
    string: "<!-- /%s -->",
    values: [$searchBlockType]
  )
  endingBlockMarkupArray: _arrayPad(
    array: [],
    length: $injectAfterBlockCount,
    value: $__endingBlockMarkup
  )
  regexString: _arrayJoin(
    array: $__endingBlockMarkupArray,
    separator: "[\\s\\S]+"
  )
  regex: _sprintf(
    string: "#(%s)#U",
    values: [$__regexString]
  ) @export(as: "regex")
  replaceWith: _sprintf(
    string: "$1%s",
    values: [$injectBlockMarkup]
  ) @export(as: "replaceWith")
}

mutation InjectBlock(
  $postIDs: [ID!]!
  $times: Int! = 1
)
  @depends(on: "CreateRegex")
{
  posts(filter: { ids: $postIDs } ) {
    id
    contentSource
    adaptedContentSource: _strRegexReplace(
      in: $__contentSource,
      searchRegex: $regex,
      replaceWith: $replaceWith,
      limit: $times
    )
    update(input: {
      contentAs: { html: $__adaptedContentSource },
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

mutation RestorePosts(
  $postIDs: [ID!]!
  $injectBlockMarkup: String!
) {
  restorePosts: posts(filter: { ids: $postIDs } ) {
    id
    contentSource
    restoredContentSource: _strReplace(
      in: $__contentSource,
      search: $injectBlockMarkup,
      replaceWith: ""
    )
    update(input: {
      contentAs: { html: $__restoredContentSource },
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

query InjectBlockAndThenRestorePosts
  @depends(on: ["InjectBlock", "RestorePosts"])
{
  id
}
```

vars:

```json
{
  "postIDs": [664],
  "injectBlockMarkup": "<!-- wp:video -->\n<figure class=\"wp-block-video\"><video controls src=\"https://archive.org/download/SlowMotionFlame/slomoflame_512kb.mp4\"></video></figure>\n<!-- /wp:video -->",
  "injectAfterBlockCount": 3
}
```

# Removing a block in bulk

remove-block-by-type.gql

```graphql
query CreateVars {
  foundPosts: posts(filter: { search: "\"<!-- /wp:columns -->\"" } ) {
    id @export(as: "postIDs", type: LIST)
    contentSource
    originalInputs: _echo(value: {
      id: $__id,
      contentAs: { html: $__contentSource }
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
      searchRegex: "#(<!-- wp:columns -->[\\s\\S]+<!-- /wp:columns -->)#",
      replaceWith: ""
    )
    update(input: {
      contentAs: { html: $__adaptedContentSource },
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
    @underEachArrayItem(passValueOnwardsAs: "input")
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

detailed:

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
    id @export(as: "postIDs", type: LIST)
    contentSource
    originalInputs: _echo(value: {
      id: $__id,
      contentAs: { html: $__contentSource }
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
      searchRegex: $regex,
      replaceWith: ""
    )
    update(input: {
      contentAs: { html: $__adaptedContentSource },
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
    @underEachArrayItem(passValueOnwardsAs: "input")
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

vars:

```json
{
  "removeBlockType": "wp:columns"
}
```
