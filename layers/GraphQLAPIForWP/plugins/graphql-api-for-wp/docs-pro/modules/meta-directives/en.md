# Meta Directives

Addition of meta directives `@if`, `@unless`, `@forEach`, `@underArrayItem` and `@underJSONObjectProperty` (which modify the behavior of nested directives) to the GraphQL schema

## Description

📣 _Please read the documentation for module "Composable Directives" to understand what meta directives are, and how to use them._

This module introduces these meta-directives into the GraphQL schema:

1. `@if`
2. `@unless`
3. `@forEach`
4. `@underArrayItem`
5. `@underJSONObjectProperty`

## @if

`@if` executes its nested directives only if a condition has value `true`.

In this query, only certain users get their name converted to upper case:

```graphql
query {
  users {
    name
      @passOnwards(as: "userName")
      @applyField(
        name: "_inArray"
        arguments: {
          value: $userName
          array: ["Leo", "John", "Peter"]
        }
        passOnwardsAs: "isSpecialUser"
      )
      @if(
        condition: $isSpecialUser
      )
        @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "users": [
      {
        "name": "LEO"
      },
      {
        "name": "Martin"
      },
      {
        "name": "PETER"
      }
    ]
  }
}
```

## @unless

Similar to `@if`, but it executes the nested directives when the condition is `false`.

## @forEach

`@forEach` iterates over a list of elements from the queried entity, and passes a reference to the iterated element to the next directive.

For instance, field `Post.categoryNames` is of type `[String]`. Using `@forEach`, we can iterate each of the category names, each of type `String`, and apply an operation to it via some nested directive.

In this query, the post categories are translated from English to French:

```graphql
query {
  posts {
    id
    title
    categoryNames
      @forEach
        @strTranslate(
          from: "en",
          to: "fr"
        )
  }
}
```

`@forEach` can also operate on `JSONObject` elements. In this query, we iterate all entries in the JSON object and replace any `null` entry with an empty string:

```graphql
{
  _echo(
    value: {
      first: "hello",
      second: "world",
      third: null
    }
  )
    @forEach
      @default(value: "")
}
```

...producing:

```json
{
  "data": {
    "_echo": {
      "first": "hello",
      "second": "world",
      "third": ""
    }
  }
}
```

## @underArrayItem

`@underArrayItem` makes the next directive be applied on a specific item from the array.

In the query below, only the first item in the array with the category names is transformed to uppercase:

```graphql
query {
  posts {
    categoryNames
      @underArrayItem(index: 0)
        @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "posts": {
      "categoryNames": [
        "NEWS",
        "sports"
      ]
    }
  }
}
```

## @underJSONObjectProperty

`@underJSONObjectProperty` makes the next directive receive an entry from the queried JSON object. This directive is particularly useful to extract and manipulate a desired piece of data after querying an external API, which will quite likely have a generic `JSONObject` type (as when using function field `_sendJSONObjectItemHTTPRequest`).

In the query below, we obtain a JSON object coming from the WP REST API, and we use `@underJSONObjectProperty` to manipulate the response's `type` property, transforming it to upper case:

```graphql
query {
  postData: _sendJSONObjectItemHTTPRequest(
    url: "https://newapi.getpop.org/wp-json/wp/v2/posts/1/?_fields=id,type,title,date"
  )
    @underJSONObjectProperty(by: { key: "type" })
      @strUpperCase
}
```

In addition to receiving a `"key"` to point to a property that lives on the first level of the JSON object, this directive can also receive a `"path"` to navigate within the inner structure of the object, using `.` as a separator across levels.

In the query below, the WP REST API endpoint for a post provides property `"title.rendered"`. We can navigate to that actual subelement, and transform it to title case:

```graphql
query {
  postData: _sendJSONObjectItemHTTPRequest(
    url: "https://newapi.getpop.org/wp-json/wp/v2/posts/1/?_fields=id,type,title,date"
  )
    @underJSONObjectProperty(by: { path: "title.rendered" })
      @strTitleCase
}
```

## Further examples

In this query, `@forEach` wraps `@underJSONObjectProperty`, which itself wraps `@strUpperCase`, transforming the `"title.rendered"` property into upper case, for the multiple post entries obtained via the WP REST API:

```graphql
query {
  postListData: _sendJSONObjectCollectionHTTPRequest(
    url: "https://newapi.getpop.org/wp-json/wp/v2/posts/?per_page=3&_fields=id,type,title,date"
  )
    @forEach
      @underJSONObjectProperty(by: { path: "title.rendered" })
        @strUpperCase
}
```
