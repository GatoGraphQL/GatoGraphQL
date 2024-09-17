# Field Value Iteration and Manipulation

Addition of meta directives to the GraphQL schema, for iterating and manipulating the value elements of array and object fields:

1. `@underArrayItem`
2. `@underJSONObjectProperty`
3. `@underEachArrayItem`
4. `@underEachJSONObjectProperty`
5. `@objectClone`

<!-- ## Description

📣 _Please read the documentation for module "Composable Directives" to understand what meta directives are, and how to use them._

This module introduces these meta-directives into the GraphQL schema:

1. `@underArrayItem`
2. `@underJSONObjectProperty`
3. `@underEachArrayItem`
4. `@underEachJSONObjectProperty`
5. `@objectClone` -->

## `@underArrayItem`

`@underArrayItem` makes the nested directive be applied on a specific item from the array.

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

## `@underJSONObjectProperty`

`@underJSONObjectProperty` makes the nested directive receive an entry from the queried JSON object.

This directive is particularly useful to extract and manipulate a desired piece of data after querying an external API, which will quite likely have a generic `JSONObject` type (as when using function field `_sendJSONObjectItemHTTPRequest` from the **HTTP Client** extension).

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

This will produce:

```json
{
  "data": {
    "postData": {
      "id": 1,
      "date": "2019-08-02T07:53:57",
      "type": "POST",
      "title": {
        "rendered": "Hello world!"
      }
    }
  }
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

This will produce:

```json
{
  "data": {
    "postData": {
      "id": 1,
      "date": "2019-08-02T07:53:57",
      "type": "post",
      "title": {
        "rendered": "HELLO WORLD!"
      }
    }
  }
}
```

## `@underEachArrayItem`

`@underEachArrayItem` iterates over the array items from some field in the queried entity, and executes the nested directive(s) on each of them.

For instance, field `Post.categoryNames` is of type `[String]`. Using `@underEachArrayItem`, we can iterate the category names and apply the `@strTranslate` directive to them.

In this query, the post categories are translated from English to French:

```graphql
query {
  posts {
    id
    title
    categoryNames
      @underEachArrayItem
        @strTranslate(
          from: "en",
          to: "fr"
        )
  }
}
```

...producing:

```json
{
  "data": {
    "posts": [
      {
        "id": 1662,
        "title": "Explaining the privacy policy",
        "categoryNames": [
          "Non classé"
        ]
      },
      {
        "id": 28,
        "title": "HTTP caching improves performance",
        "categoryNames": [
          "Avancé"
        ]
      },
      {
        "id": 25,
        "title": "Public or Private API mode, for extra security",
        "categoryNames": [
          "Revue",
          "Blog",
          "Avancé"
        ]
      }
    ]
  }
}
```

<!-- `@underEachArrayItem` can pass both the index and the value of the iterated-upon element as a dynamic variable to its nested directive(s), via directive args `passIndexOnwardsAs` and `passValueOnwardsAs`.

This query demonstrates the use of dynamic variables `$index` and `$value`:

```graphql
{
  _echo(value: ["first", "second", "third"])
    @underEachArrayItem(
      passIndexOnwardsAs: "index"
      passValueOnwardsAs: "value"
    )
      @applyField(
        name: "_echo"
        arguments: {
          value: {
            index: $index,
            value: $value
          }
        },
        setResultInResponse: true
      )
}
```

The result is:

```json
{
  "data": {
    "_echo": [
      {
        "index": 0,
        "value": "first"
      },
      {
        "index": 1,
        "value": "second"
      },
      {
        "index": 2,
        "value": "third"
      }
    ]
  }
}
```

`@underEachArrayItem` can also limit the positions of the array to iterate upon, via param `filter->by`, which can accept either entry `include` or `exclude`.

This query:

```graphql
{
  including: _echo([
    "first",
    "second",
    "third"
  ])
    @underEachArrayItem(
      filter: {
        by: {
          include: [0, 2]
        }
      }
    )
      @strUpperCase

  excluding: _echo([
    "first",
    "second",
    "third"
  ])
    @underEachArrayItem(
      filter: {
        by: {
          exclude: [0, 2]
        }
      }
    )
      @strUpperCase
}
```

...produces:

```json
{
  "data": {
    "including": [
      "FIRST",
      "second",
      "THIRD"
    ],
    "excluding": [
      "first",
      "SECOND",
      "third"
    ]
  }
}
``` -->

## `@underEachJSONObjectProperty`

`@underEachJSONObjectProperty` is similar to `@underEachArrayItem`, but operating on `JSONObject` elements.

In this query, we iterate all entries in the JSON object and replace any `null` entry with an empty string:

```graphql
{
  _echo(
    value: {
      first: "hello",
      second: "world",
      third: null
    }
  )
    @underEachJSONObjectProperty
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

<!-- `@underEachJSONObjectProperty` can pass the key and the value it is iterating on as a dynamic variable to its nested directive(s), via directive args `passKeyOnwardsAs` and `passValueOnwardsAs`.

This query demonstrates the use of dynamic variables `$key` and `$value`:

```graphql
{
  _echo(value: {
    uno: "first",
    dos: "second",
    tres: "third"
  })
    @underEachJSONObjectProperty(
      passKeyOnwardsAs: "key"
      passValueOnwardsAs: "value"
    )
      @applyField(
        name: "_echo"
        arguments: {
          value: {
            key: $key,
            value: $value
          }
        },
        setResultInResponse: true
      )
}
```

The result is:

```json
{
  "data": {
    "_echo": {
      "uno": {
        "key": "uno",
        "value": "first"
      },
      "dos": {
        "key": "dos",
        "value": "second"
      },
      "tres": {
        "key": "tres",
        "value": "third"
      }
    }
  }
}
```

`@underEachJSONObjectProperty` can also limit the keys from the JSON object to iterate upon, via param `filter->by`, which can accept either entry `includeKeys` or `excludeKeys`.

This query:

```graphql
{
  includingKeys: _echo(value: {
    uno: "first",
    dos: "second",
    tres: "third"
  })
    @underEachJSONObjectProperty(
      filter: {
        by: {
          includeKeys: ["uno", "tres"]
        }
      }
    )
      @strUpperCase

  excludingKeys: _echo(value: {
    uno: "first",
    dos: "second",
    tres: "third"
  })
    @underEachJSONObjectProperty(
      filter: {
        by: {
          excludeKeys: ["uno", "tres"]
        }
      }
    )
      @strUpperCase
}
```

...produces:

```json
{
  "data": {
    "includingKeys": {
      "uno": "FIRST",
      "dos": "second",
      "tres": "THIRD"
    },
    "excludingKeys": {
      "uno": "first",
      "dos": "SECOND",
      "tres": "third"
    }
  }
}
``` -->

## `@objectClone`

JSON objects may be accessed by reference in the field resolvers (and not by copying/duplicating the object). When that is the case, when the JSON object is modified, this modification will be visible to all the fields that retrieve this JSON object.

This is the case with field `Block.attributes`:

```graphql
{
  posts {
    blocks(filterBy: { include: "core/heading" } ) {
      attributes
    }
  }
}
```

...which produces:

```json
{
  "data": {
    "posts": [
      {
        "blocks": [
          {
            "attributes": {
              "content": "Image Block (Full width)",
              "level": 2
            }
          },
          {
            "attributes": {
              "content": "Gallery Block",
              "level": 2
            }
          }
        ]
      }
    ]
  }
}
```

In the query below, while `originalAttributes` is simply retrieving the attributes, `transformedAttributes` will also translate the `content` property to French:

```graphql
{
  posts {
    blocks(filterBy: { include: "core/heading" } ) {
      originalAttributes: attributes
      transformedAttributes: attributes
        @underJSONObjectProperty(by: { key: "content" })
          @strTranslate(to: "fr")
    }
  }
}
```

However, as the queried `Block` entity references the same JSON object on both `originalAttributes` and `transformedAttributes`, the transformations performed by the latter field will also affect the former field (this is irrespective of the order in which they appear in the query).

As a result, both fields are translated to French:

```json
{
  "data": {
    "posts": [
      {
        "blocks": [
          {
            "originalAttributes": {
              "content": "Bloc d'image (pleine largeur)",
              "level": 2
            },
            "transformedAttributes": {
              "content": "Bloc d'image (pleine largeur)",
              "level": 2
            }
          },
          {
            "originalAttributes": {
              "content": "Bloc Galerie",
              "level": 2
            },
            "transformedAttributes": {
              "content": "Bloc Galerie",
              "level": 2
            }
          }
        ]
      }
    ]
  }
}
```

We can avoid this issue by adding directive `@objectClone` on the `transformedAttributes` field, so that the modifications are performed on a cloned JSON object:

```graphql
{
  posts {
    blocks(filterBy: { include: "core/heading" } ) {
      originalAttributes: attributes
      transformedAttributes: attributes
        @objectClone
        @underJSONObjectProperty(by: { key: "content" })
          @strTranslate(to: "fr")
    }
  }
}
```

...producing:

```json
{
  "data": {
    "posts": [
      {
        "blocks": [
          {
            "originalAttributes": {
              "content": "Image Block (Full width)",
              "level": 2
            },
            "transformedAttributes": {
              "content": "Bloc d'image (pleine largeur)",
              "level": 2
            }
          },
          {
            "originalAttributes": {
              "content": "Gallery Block",
              "level": 2
            },
            "transformedAttributes": {
              "content": "Bloc Galerie",
              "level": 2
            }
          }
        ]
      }
    ]
  }
}
```

## Further examples

In this query, `@underEachArrayItem` wraps `@underJSONObjectProperty`, which itself wraps `@strUpperCase`, transforming the `"title.rendered"` property into upper case, for the multiple post entries obtained via the WP REST API:

```graphql
query {
  postListData: _sendJSONObjectCollectionHTTPRequest(
    url: "https://newapi.getpop.org/wp-json/wp/v2/posts/?per_page=3&_fields=id,type,title,date"
  )
    @underEachArrayItem
      @underJSONObjectProperty(by: { path: "title.rendered" })
        @strUpperCase
}
```

...producing:

```json
{
  "data": {
    "postListData": [
      {
        "id": 1692,
        "date": "2022-04-26T10:10:08",
        "type": "post",
        "title": {
          "rendered": "MY BLOGROLL"
        }
      },
      {
        "id": 1657,
        "date": "2020-12-21T08:24:18",
        "type": "post",
        "title": {
          "rendered": "A TALE OF TWO CITIES &#8211; TEASER"
        }
      },
      {
        "id": 1499,
        "date": "2019-08-08T02:49:36",
        "type": "post",
        "title": {
          "rendered": "COPE WITH WORDPRESS: POST DEMO CONTAINING PLENTY OF BLOCKS"
        }
      }
    ]
  }
}
```
<!-- 
## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-feature-bundled-extensions/docs/modules/all-feature-bundled-extensions/en.md)
- [“Caching” Bundle](../../../../../bundle-extensions/caching/docs/modules/caching/en.md)
- [“Custom Endpoints” Bundle](../../../../../bundle-extensions/custom-endpoints/docs/modules/custom-endpoints/en.md)
- [“Deprecation” Bundle](../../../../../bundle-extensions/deprecation/docs/modules/deprecation/en.md)
- [“Multiple Query Execution” Bundle](../../../../../bundle-extensions/multiple-query-execution/docs/modules/multiple-query-execution/en.md)
- [“Persisted Queries” Bundle](../../../../../bundle-extensions/persisted-queries/docs/modules/persisted-queries/en.md)
- [“Polylang Integration” Bundle](../../../../../bundle-extensions/polylang-integration/docs/modules/polylang-integration/en.md)
- [“Query Functions” Bundle](../../../../../bundle-extensions/query-functions/docs/modules/query-functions/en.md)
- [“Security” Bundle](../../../../../bundle-extensions/security/docs/modules/security/en.md)
- [“Versatile WordPress Request API” Bundle](../../../../../bundle-extensions/versatile-wordpress-request-api/docs/modules/versatile-wordpress-request-api/en.md) -->
