# Composable Directives

Allow directives to nest and modify the behavior of other directives. This is the base functionality over which module "Meta Directives" is based.

## Description

This module allows directives to execute complex functionalities, by composing other directives inside, calling them before/after preparing the field value accordingly. Directives with this capability are called "meta directives".

A use case is to convert the type of the field value to the type expected by the nested directive. For instance, each element from an array can be provided to a directive that expects a single value. In this query, field `capabilities` returns `[String]` (an array of strings), and directive `@strUpperCase` receives `String`. Hence, executing the following query:

```graphql
query {
  user(by: {id: 1}) {
    capabilities @strUpperCase
  }
}
```

...returns an error due to the type mismatch:

```json
{
  "errors": [
    {
      "message": "Directive 'strUpperCase' from field 'capabilities' cannot be applied on object with ID '1' because it is not a string"
    }
  ],
  "data": {
    "user": {
      "capabilities": null
    }
  }
}
```

The meta directive `@forEach` (provided via module "Meta Directives") can solve this problem, as it iterates over an array of elements and applies its nested directive on each of them, setting the stage before `@strUpperCase` is executed and making it receive a single element (of type `String`) instead of an array.

The query from above can be satisfied like this:

```graphql
query {
  user(by: {id: 1}) {
    capabilities
      @forEach
        @strUpperCase
  }
}
```

...producing the intended response:

```json
{
  "data": {
    "user": {
      "capabilities": [
        "READ",
        "LEVEL_0"
      ]
    }
  }
}
```

## Using meta directives

Every meta directive can affect (or "nest") multiple directives at once. Which directives are affected is indicated via argument `affectDirectivesUnderPos`, which receives an array of positive integers, each of them defining the affected directive's relative position.

By default, argument `affectDirectivesUnderPos` has default value `[1]`, meaning that it will affect the directive right next to it.

In the example below, we have:

- `@forEach` is the meta directive
- `@strTranslate` is nested under `@forEach` (implicit default value `affectDirectivesUnderPos: [1]`)

```graphql
{
  someField
    @forEach
      @strTranslate
}
```

In the example below, we instead have:

- `@strTranslate` and `@strUpperCase` are nested under `@forEach` (as indicated by relative positions `[1, 2]` in argument `affectDirectivesUnderPos`)

```graphql
{
  someField
    @forEach(affectDirectivesUnderPos: [1, 2])
      @strTranslate
      @strUpperCase
}
```

Meta directives can also be nested within meta directives.

In the example below, we have:

- `@forEach` is the topmost meta directive
- `@underJSONObjectProperty` is nested under `@forEach`
- `@strUpperCase` is nested under `@underJSONObjectProperty`

```graphql
query UppercaseEntriesInsideObject {
  entries: _echo(value: [
    {
      text: "Hello my friends"
    },
    {
      text: "How do you like this software so far?"
    }
  ])
   @forEach
      @underJSONObjectProperty(by: { key: "text" })
        @strUpperCase
  }
```

The response is:

```json
{
  "data": {
    "entries": [
      {
        "text": "HELLO MY FRIENDS"
      },
      {
        "text": "HOW DO YOU LIKE THIS SOFTWARE SO FAR?"
      }
    ]
  }
}
```

### Exporting dynamic variables

A meta directive can pass the value it contains as a "dynamic variable" to its nested directives, via directive argument `passOnwardsAs`.

In the query below, the array `["Hello everyone", "How are you?"]` is iterated upon using `@forEach`, and by defining argument `passOnwardsAs: "text"` each value in the array is made available to the nested directive `@applyField` under the dynamic variable `$text`:

```graphql
query {
  _echo(value: ["Hello everyone", "How are you?"])
    @forEach(passOnwardsAs: "text")
      @applyField(
        name: "_strReplace"
        arguments: {
            replace: " "
            with: "-"
            in: $text
        },
        setResultInResponse: true
      )
}
```

This will produce:

```json
"data": {
    "echo": [
      "Hello-everyone",
      "How-are-you?"
    ]
  }
```

## Examples

Translating the post categories from English to French:

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

Transform a single post's `"title.rendered"` property, obtained through the WP REST API endpoint, into title case:

```graphql
query {
  postData: _requestJSONObjectItem(
    url: "https://newapi.getpop.org/wp-json/wp/v2/posts/1/?_fields=id,type,title,date"
  )
    @underJSONObjectProperty(by: { path: "title.rendered" })
      @strTitleCase
}
```

Transform multiple posts' `"title.rendered"` property into upper case:

```graphql
query {
  postListData: _requestJSONObjectCollection(
    url: "https://newapi.getpop.org/wp-json/wp/v2/posts/?per_page=3&_fields=id,type,title,date"
  )
    @forEach
      @underJSONObjectProperty(by: { path: "title.rendered" })
        @strUpperCase
}
```
