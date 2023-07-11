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

## Adding missing links

This GraphQL query adds all missing links in the post's HTML content: For all URLs which are not surrounded by an anchor tag, such as:

```html
<p>Visit my website: https://mysite.com.</p>
```

...it adds the correspondig `<a>` tag around them (while removing the domain from the text, and adding a `target` to open in a new window):

```html
<p>Visit my website: <a href="https://mysite.com" target="_blank">mysite.com</a>.</p>
```

```graphql
query GetPostData($postId: ID!) {
  post(by: { id: $postId }) {
    id
    contentSource
    adaptedContentSource: _strRegexReplace(
      searchRegex: "#((https?)://(\\S*?\\.\\S*?))([\\s)\\[\\]{},;\"\\':<]|\\.\\s|$)#i"
      replaceWith: "<a href=\"$1\" target=\"_blank\">$3</a>$4"
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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

The `"\"` character must be escaped inside the regex patterns:

- `"#\.#"` is written as `"#\\.#"`
- `"/^https?:\/\//"` is written as `"/^https?:\\/\\//"`

</div>

## Replacing HTTP with HTTPS

This GraphQL query replaces all `http` URLs with `https` in HTML image sources:

```graphql
query GetPostData($postId: ID!) {
  post(by: {id: $postId}) {
    id
    contentSource
    adaptedContentSource: _strRegexReplace(
      searchRegex: "/<img(\\s+)?([^>]*?\\s+?)?src=([\"'])http:\\/\\/(.*?)/"
      replaceWith: "<img$1$2src=$3https://$4$3"
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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Talk about $1 in docs:

_strRegexReplaceMultiple(searchRegex: ["/^https?:\\/\\//", "/([a-z]*)/"], replaceWith: ["", "$1$1"], in: "https://gatographql.com")
regexWithHashMultiple: _strRegexReplaceMultiple(searchRegex: ["#^https?://#", "/([a-z]*)/"], replaceWith: ["", "$1$1"], in: "https://gatographql.com")
regexWithVarsMultiple: _strRegexReplaceMultiple(searchRegex: ["/<!\\[CDATA\\[([a-zA-Z !?]*)\\]\\]>/", "/([a-z]*)/"], replaceWith: ["<Inside: $1>", "$1$1"], in: "<![CDATA[Hello world!]]><![CDATA[Everything OK?]]>")
regexWithVarsAndLimitMultiple: _strRegexReplaceMultiple(searchRegex: ["/<!\\[CDATA\\[([a-zA-Z !?]*)\\]\\]>/", "/([a-z]*)/"], replaceWith: ["<Inside: $1>", "$1$1"], in: "<![CDATA[Hello world!]]><![CDATA[Everything OK?]]>", limit: 1)

</div>

Also talk about \" and \"" or what