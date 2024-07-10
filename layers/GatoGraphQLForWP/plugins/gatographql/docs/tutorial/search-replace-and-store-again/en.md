# Lesson 6: Search, replace, and store again

This tutorial lesson provides examples of content adaptations involving search and replace, and then storing the resource back to the DB. It requires the [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

The [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension provides the following "search and replace" fields:

- `_strReplace`: Replace a string with another string
- `_strReplaceMultiple`: Replace a list of strings with another list of strings
- `_strRegexReplace`: Search for the string to replace using a regular expression
- `_strRegexReplaceMultiple`: Search for the strings to replace using a list of regular expressions

</div>

## Search and replace a string

This GraphQL query retrieves a post, replaces all occurrences of some string with another one in the post's content and title, and stores the post again:

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

    rawContent
    adaptedRawContent: _strReplace(
      search: $replaceFrom
      replaceWith: $replaceTo
      in: $__rawContent
    )
      @export(as: "adaptedRawContent")
  }
}

mutation UpdatePost($postId: ID!)
  @depends(on: "GetPostData")
{
  updatePost(input: {
    id: $postId,
    title: $adaptedPostTitle,
    contentAs: { html: $adaptedRawContent },
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
      rawContent
    }
  }
}
```

To execute the query, we provide the dictionary of `variables` with the strings to search and replace:

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

    rawContent
    adaptedRawContent: _strReplaceMultiple(
      search: $replaceFrom
      replaceWith: $replaceTo
      in: $__rawContent
    )
      @export(as: "adaptedRawContent")
  }
}

mutation UpdatePost($postId: ID!)
  @depends(on: "GetPostData")
{
  updatePost(input: {
    id: $postId,
    title: $adaptedPostTitle,
    contentAs: { html: $adaptedRawContent },
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
      rawContent
    }
  }
}
```

The dictionary of `variables` now receives a list of strings to search and replace:

```json
{
  "postId": 1,
  "replaceFrom": ["Old string 2", "Old string 2"],
  "replaceTo": ["New string1", "New string 2"]
}
```

## Adding missing links

This GraphQL query does a regex search and replace to add missing links in the post's HTML content:

```graphql
query GetPostData($postId: ID!) {
  post(by: { id: $postId }) {
    id
    rawContent
    adaptedRawContent: _strRegexReplace(
      searchRegex: "#\\s+((https?)://(\\S*?\\.\\S*?))([\\s)\\[\\]{},;\"\\':<]|\\.\\s|$)#i"
      replaceWith: "<a href=\"$1\" target=\"_blank\">$3</a>$4"
      in: $__rawContent
    )
      @export(as: "adaptedRawContent")
  }
}

mutation UpdatePost($postId: ID!)
  @depends(on: "GetPostData")
{
  updatePost(input: {
    id: $postId,
    contentAs: { html: $adaptedRawContent },
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
      rawContent
    }
  }
}
```

All URLs which are not surrounded by an anchor tag, such as:

```html
<p>Visit my website: https://mysite.com.</p>
```

...are added the corresponding `<a>` tag around them (while also removing the domain from the text, and adding a `target` to open in a new window), becoming:

```html
<p>Visit my website: <a href="https://mysite.com" target="_blank">mysite.com</a>.</p>
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- The `"\"` character must be escaped as `"\\"` inside the regex pattern. For instance, `"/^https?:\/\//"` is written as `"/^https?:\\/\\//"`
- The documentation for [PHP function `preg_replace`](https://www.php.net/manual/en/function.preg-replace.php) explains how to use [replacement references](https://www.php.net/manual/en/function.preg-replace.php#refsect1-function.preg-replace-parameters) (eg: `$1`) and [PRCE modifiers](https://www.php.net/manual/en/reference.pcre.pattern.modifiers.php).

</div>

## Replacing HTTP with HTTPS

This GraphQL query replaces all `http` URLs with `https` in HTML image sources:

```graphql
query GetPostData($postId: ID!) {
  post(by: {id: $postId}) {
    id
    rawContent
    adaptedRawContent: _strRegexReplace(
      searchRegex: "/<img(\\s+)?([^>]*?\\s+?)?src=([\"'])http:\\/\\/(.*?)/"
      replaceWith: "<img$1$2src=$3https://$4$3"
      in: $__rawContent
    )
      @export(as: "adaptedRawContent")
  }
}

mutation UpdatePost($postId: ID!)
  @depends(on: "GetPostData")
{
  updatePost(input: {
    id: $postId,
    contentAs: { html: $adaptedRawContent },
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
      rawContent
    }
  }
}
```
