# Search, replace, and store again

This recipe (which requires the [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension) provides examples of content adaptations involving search and replace, and then storing the resource back to the DB.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

The [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension provides the following "search and replace" fields:

- `_strReplace`: Replace a string with another string
- `_strReplaceMultiple`: Replace a list of strings with another list of strings
- `_strRegexReplace`: Search for the string to replace using a regular expression
- `_strRegexReplaceMultiple`: Search for the strings to replace using a list of regular expressions

</div>

## Search and replace a string

This GraphQL query retrieves a post, replaces all occurrences in its content and title of some string with another one, and stores the post again:

```graphql
query GetPostData(
  $postId: ID!
  $replaceFrom: String!,
  $replaceTo: String!
) {
  post(by: { id: $postId }) {
    title
    adaptedPostTitle: _strReplace(
      search: $replaceFrom
      replaceWith: $replaceTo
      in: $__title
    )
      @export(as: "adaptedPostTitle")

    contentSource
    adaptedContentSource: _strReplace(
      search: $replaceFrom
      replaceWith: $replaceTo
      in: $__contentSource
    )
      @export(as: "adaptedContentSource")
  }
}

mutation UpdatePost($postId: ID!)
  @depends(on: "GetPostData")
{
  updatePost(input: {
    id: $postId,
    title: $adaptedPostTitle,
    contentAs: { html: $adaptedContentSource },
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
      title
      contentSource
    }
  }
}
```

To execute the query, we provide the dictionary of `variables`:

```json
{
  "postId": 1,
  "replaceFrom": "Old string",
  "replaceTo": "New string"
}
```

## Search and replace multiple strings

This is the same query as above, but by using `_strReplaceMultiple` we can replace a list of strings with another list of strings:

```graphql
query GetPostData(
  $postId: ID!
  $replaceFrom: [String!]!,
  $replaceTo: [String!]!
) {
  post(by: { id: $postId }) {
    title
    adaptedPostTitle: _strReplaceMultiple(
      search: $replaceFrom
      replaceWith: $replaceTo
      in: $__title
    )
      @export(as: "adaptedPostTitle")

    contentSource
    adaptedContentSource: _strReplaceMultiple(
      search: $replaceFrom
      replaceWith: $replaceTo
      in: $__contentSource
    )
      @export(as: "adaptedContentSource")
  }
}

mutation UpdatePost($postId: ID!)
  @depends(on: "GetPostData")
{
  updatePost(input: {
    id: $postId,
    title: $adaptedPostTitle,
    contentAs: { html: $adaptedContentSource },
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
      title
      contentSource
    }
  }
}
```

To execute the query, we provide the dictionary of `variables`:

```json
{
  "postId": 1,
  "replaceFrom": ["Old string 2", "Old string 2"],
  "replaceTo": ["New string1", "New string 2"]
}
```

## Replace HTTP with HTTPS

This GraphQL query identifies all HTML anchors in the post's content, extracts their URLs, and replaces those starting with `"http://"` with `"https://"`:

```graphql
query GetPostData($postId: ID!) {
  post(by: {id: $postId}) {
    id
    contentSource
    contentSourceWithLinks: _strRegexReplace(
      searchRegex: "#((https?)://(\\S*?\\.\\S*?))([\\s)\\[\\]{},;\"\\':<]|\\.\\s|$)#i"
      replaceWith: "<a href=\"$1\" target=\"_blank\">$3</a>$4"
      in: $__contentSource
    )
  }
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





<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Please notice that the 

Talk about $1 in docs:
  _strRegexReplaceMultiple(searchRegex: ["/^https?:\\/\\//", "/([a-z]*)/"], replaceWith: ["", "$1$1"], in: "https://gatographql.com")
  regexWithHashMultiple: _strRegexReplaceMultiple(searchRegex: ["#^https?://#", "/([a-z]*)/"], replaceWith: ["", "$1$1"], in: "https://gatographql.com")
  regexWithVarsMultiple: _strRegexReplaceMultiple(searchRegex: ["/<!\\[CDATA\\[([a-zA-Z !?]*)\\]\\]>/", "/([a-z]*)/"], replaceWith: ["<Inside: $1>", "$1$1"], in: "<![CDATA[Hello world!]]><![CDATA[Everything OK?]]>")
  regexWithVarsAndLimitMultiple: _strRegexReplaceMultiple(searchRegex: ["/<!\\[CDATA\\[([a-zA-Z !?]*)\\]\\]>/", "/([a-z]*)/"], replaceWith: ["<Inside: $1>", "$1$1"], in: "<![CDATA[Hello world!]]><![CDATA[Everything OK?]]>", limit: 1)

</div>

Also talk about \" and \"" or what