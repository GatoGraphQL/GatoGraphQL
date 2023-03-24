# Inserting a Gutenberg block in all posts

after 3 paragraph blocks

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
      regex: "#(<!-- /wp:paragraph -->[\\s\\S]+<!-- /wp:paragraph -->[\\s\\S]+<!-- /wp:paragraph -->)#U",
      replaceWith: "$1<!-- wp:video -->\n<figure class=\"wp-block-video\"><video controls src=\"https://archive.org/download/SlowMotionFlame/slomoflame_512kb.mp4\"></video></figure>\n<!-- /wp:video -->",
      limit: 1
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

mutation RestorePosts(
  $postIDs: [ID!]!
) {
  restorePosts: posts(filter: { ids: $postIDs } ) {
    id
    contentSource
    restoredContentSource: _strReplace(
      in: $__contentSource,
      replace: "<!-- wp:video -->\n<figure class=\"wp-block-video\"><video controls src=\"https://archive.org/download/SlowMotionFlame/slomoflame_512kb.mp4\"></video></figure>\n<!-- /wp:video -->",
      with: ""
    )
    update(input: {
      content: $__restoredContentSource,
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
      regex: $regex,
      replaceWith: $replaceWith,
      limit: $times
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

mutation RestorePosts(
  $postIDs: [ID!]!
  $injectBlockMarkup: String!
) {
  restorePosts: posts(filter: { ids: $postIDs } ) {
    id
    contentSource
    restoredContentSource: _strReplace(
      in: $__contentSource,
      replace: $injectBlockMarkup,
      with: ""
    )
    update(input: {
      content: $__restoredContentSource,
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
