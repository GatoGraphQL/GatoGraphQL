# Fixing content issues

http => https
URL without link => with link

1-transform-post-data.gql:

```graphql
query GetPostData(
  $postId: ID!
) {
  post(by: {id: $postId}) {
    id
    title @export(as: "postTitle")
    contentSource @export(as: "postContent")
  }
}

query AdaptPostData(
  $replaceFrom: String!,
  $replaceTo: String!
) @depends(on: "GetPostData") {
  adaptedPostTitle: _echo(value: $postTitle)
  	@passOnwards(as: "titleInput")
    @applyField(
      name: "_strReplace"
      arguments: {
        search: $replaceFrom
        replaceWith: $replaceTo
        in: $titleInput
      },
      setResultInResponse: true
    )
    @export(as: "adaptedPostTitle")

  adaptedPostContent: _echo(value: $postContent)
  	@passOnwards(as: "contentInput")
    @applyField(
      name: "_strReplace"
      arguments: {
        search: $replaceFrom
        replaceWith: $replaceTo
        in: $contentInput
      },
      setResultInResponse: true
    )
    @export(as: "adaptedPostContent")
}

query PreparePostDataAsInput(
  $postId: ID!
) @depends(on: "AdaptPostData") {
  adaptedPostData: _echo(value: {
    id: $postId,
    title: $adaptedPostTitle,
    contentAs: { html: $adaptedPostContent },
  })
    @export(as: "adaptedPostData")
}

mutation StoreAdaptedPostData @depends(on: "PreparePostDataAsInput") {
  updatePost(input: $adaptedPostData) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
      id
      title
      contentSource
    }
  }
}
```

var

```json
{
  "postId": 1,
  "replaceFrom": " ",
  "replaceTo": "|||"
}
```


regex-replace-url-in-post-content.gql:

```graphql
query GetPostData(
  $postId: ID!
) {
  post(by: {id: $postId}) {
    id
    contentSource
    contentSourceWithLinks: _strRegexReplace(
      # @see https://stackoverflow.com/a/206087
      searchRegex: "#((https?)://(\\S*?\\.\\S*?))([\\s)\\[\\]{},;\"\\':<]|\\.\\s|$)#i"
      replaceWith: "<a href=\"$1\" target=\"_blank\">$3</a>$4"
      in: $__contentSource
    )
  }
}
```

var

```json
{
  "postId": 662
}
```

This one is repeated from Site Migrations:

nested/1-replace-url-in-post-content.gql:

```graphql
query ExportSiteURL
{
  siteURL: optionValue(name: "siteurl")
    # Hack for this test to work in both "Integration Tests" and "PROD Integration Tests"
    @strReplace(
      search: "-for-prod.lndo.site"
      replaceWith: ".lndo.site"
    )
    @export(as: "siteURL")
}

query ExportData(
  $oldPageSlug: String!
  $newPageSlug: String!
)
  @depends(on: "ExportSiteURL")
{
  oldPageURL: _strAppend(
    after: $siteURL,
    append: $oldPageSlug
  ) @export(as: "oldPageURL")

  newPageURL: _strAppend(
    after: $siteURL,
    append: $newPageSlug
  ) @export(as: "newPageURL")
}

mutation ReplaceOldWithNewURLInPosts
  @depends(on: "ExportData")
{
  posts(filter: { search: $oldPageURL }, sort: {by: ID, order: ASC}) {
    id
    contentSource
    adaptedContentSource: _strReplace(
      search: $oldPageURL
      replaceWith: $newPageURL
      in: $__contentSource
    )
    update(input: {
      contentAs: { html: $__adaptedContentSource }
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

var

```json
{
  "oldPageSlug": "/privacy/",
  "newPageSlug": "/user-privacy/"
}
```

nested/3-transform-post-properties.gql

```graphql
query ExportAndTransformData(
  $replaceFrom: String!
  $replaceTo: String!
) {
  # Exclude ID 28 because its blocks render the domain, so it doesn't work for "PROD Integration Tests"
  posts: posts(pagination: { limit: 3 }, sort: { by: ID, order: ASC }, filter: { excludeIDs: 28 }) {
    id @export(as: "postIDs")
    title @strReverse
    excerpt
      @strReplace(
        search: $replaceFrom
        replaceWith: $replaceTo
      )
      @deferredExport(
        as: "postProps"
        affectAdditionalFieldsUnderPos: 1
      )
  }
}
mutation TransformPostData @depends(on: "ExportAndTransformData") {
  adaptedPosts: posts(pagination: { limit: 3 }, sort: { by: ID, order: ASC }, filter: { excludeIDs: 28 }) {
    id
    positionInArray: _arraySearch(array: $postIDs, element: $__id)
    postData: _arrayItem(array: $postProps, position: $__positionInArray)
    update(input: $__postData) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      post {
        title
        contentSource
      }
    }
  }
}
```

var

```json
{
  "replaceFrom": " ",
  "replaceTo": "|||"
}
```
