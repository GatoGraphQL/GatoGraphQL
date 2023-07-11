# Inserting/Removing a (Gutenberg) block in bulk

We can update posts by modifying their (Gutenberg) block's HTML content.

Among other use cases, this is useful for promoting campaigns (such as when offering a discount during Black Friday):

- Before the campaign, we create a custom block with our message, and execute a bulk operation to add it to all posts in the website
- After the campaign, we execute a bulk operation to remove the block from all posts

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For the GraphQL queries in this recipe to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have  [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

## Inserting a block in bulk

This GraphQL query identifies the 3rd paragraph block in a post (by searching for `<!-- /wp:paragraph -->`) and places the custom block's HTML content right after it (in this case, it is the HTML code for a `core/video` block).

```graphql
mutation InjectBlock(
  $limit: Int! = 5,
  $offset: Int! = 0
) {
  posts: posts(
    pagination: { limit: $limit, offset: $offset }
    sort: { by: ID, order: ASC }
  ) {
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

## Inserting a block in bulk (again)

This GraphQL query is similar to the previos one, but it also allows to choose the block type to search for, and after what occurrence number to place our block:

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
  $limit: Int! = 5,
  $offset: Int! = 0
  $times: Int! = 1
)
  @depends(on: "CreateRegex")
{
  posts: posts(
    pagination: { limit: $limit, offset: $offset }
    sort: { by: ID, order: ASC }
  ) {
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
