# HTTP Client

Addition of fields to the GraphQL schema to execute HTTP requests against a webserver and fetch their response:

- `_sendJSONObjectItemHTTPRequest`
- `_sendJSONObjectItemHTTPRequests`
- `_sendJSONObjectCollectionHTTPRequest`
- `_sendJSONObjectCollectionHTTPRequests`
- `_sendHTTPRequest`
- `_sendHTTPRequests`
- `_sendGraphQLHTTPRequest`
- `_sendGraphQLHTTPRequests`

Due to security reasons, the URLs that can be connected to must be explicitly configured.

## List of fields

The following fields are added to the schema.

### `_sendJSONObjectItemHTTPRequest`

It retrieves the (REST) response for a single JSON object.

**Signature:** `_sendJSONObjectItemHTTPRequest(input: HTTPRequestInput!): JSONObject`.

### `_sendJSONObjectItemHTTPRequests`

It retrieves the (REST) response for a single JSON object from multiple endpoints, executed asynchronously (in parallel) or synchronously (one after the other).

**Signature:** `_sendJSONObjectItemHTTPRequests(async: Boolean = true, inputs: [HTTPRequestInput!]!): [JSONObject]`.

### `_sendJSONObjectCollectionHTTPRequest`

It retrieves the (REST) response for a collection of JSON objects.

**Signature:** `_sendJSONObjectCollectionHTTPRequest(input: HTTPRequestInput!): [JSONObject]`.

### `_sendJSONObjectCollectionHTTPRequests`

It retrieves the (REST) response for a collection of JSON objects from multiple endpoints, executed asynchronously (in parallel) or synchronously (one after the other).

**Signature:** `_sendJSONObjectCollectionHTTPRequests(async: Boolean = true, inputs: [HTTPRequestInput!]!): [[JSONObject]]`.

### `_sendHTTPRequest`

It connects to the specified URL and retrieves an `HTTPResponse` object, which contains the following fields:

- `statusCode: Int!`
- `contentType: String!`
- `body: String!`
- `headers: JSONObject!`
- `header(name: String!): String`
- `hasHeader(name: String!): Boolean!`

**Signature:** `_sendHTTPRequest(input: HTTPRequestInput!): HTTPResponse`.

### `_sendHTTPRequests`

Similar to `_sendHTTPRequest` but it receives multiple URLs, and allows to connect to them asynchronously (in parallel).

**Signature:** `_sendHTTPRequests(async: Boolean = true, inputs: [HTTPRequestInput!]!): [HTTPResponse]`.

### `_sendGraphQLHTTPRequest`

Execute a GraphQL query against the provided endpoint, and retrieve the response as a JSON object.

The input to this field accepts the data expected for GraphQL: the endpoint, GraphQL query, variables and operation name, and already sets the default method (`POST`) and content type (`application/json`).

**Signature:** `_sendGraphQLHTTPRequest(input: GraphQLRequestInput!): JSONObject`.

### `_sendGraphQLHTTPRequests`

Similar to `_sendGraphQLHTTPRequests` but it executes multiple GraphQL queries concurrently, whether asynchronously (in parallel) or synchronously (one after the other).

**Signature:** `_sendGraphQLHTTPRequests(async: Boolean = true, inputs: [GraphQLRequestInput!]!): JSONObject`.

## Configuring the allowed URLs

We must configure the list of URLs that we can connect to.

Each entry can either be:

- A regex (regular expression), if it's surrounded by `/` or `#`, or
- The complete URL, otherwise

For instance, any of these entries match URL `"https://gatographql.com/features/"`:

- `https://gatographql.com/features/`
- `#https://gatographql.com/features/?#`
- `#https://gatographql.com/.*#`
- `/https:\\/\\/gatographql.com\\/(\S+)/`

There are 2 places where this configuration can take place, in order of priority:

1. Custom: In the corresponding Schema Configuration
2. General: In the Settings page

In the Schema Configuration applied to the endpoint, select option `"Use custom configuration"` and then input the desired entries:

![Defining the entries for the Schema Configuration](../../images/http-requests-schema-configuration-entries.png "Defining the entries for the Schema Configuration")

Otherwise, the entries defined in the "Send HTTP Request Fields" tab from the Settings will be used:

<div class="img-width-1024" markdown=1>

![Defining the entries for the Settings](../../images/http-requests-settings-entries.png "Defining the entries for the Settings")

</div>

There are 2 behaviors, "Allow access" and "Deny access":

- **Allow access:** only the configured entries can be accessed, and no other can
- **Deny access:** the configured entries cannot be accessed, all other entries can

<div class="img-width-1024" markdown=1>

![Defining the access behavior](../../images/http-requests-settings-behavior.png "Defining the access behavior")

</div>

## When to use each field

All fields are similar but different.

### `_sendJSONObjectItemHTTPRequest`

This field retrieves a JSON object item, which is useful when querying a single item from a REST endpoint, as from the WP REST API endpoint `/wp-json/wp/v2/posts/1/`.

This query:

```graphql
{
  postData: _sendJSONObjectItemHTTPRequest(input: { url: "https://newapi.getpop.org/wp-json/wp/v2/posts/1/" } )
}
```

...retrieves this response:

```json
{
  "data": {
    "postData": {
      "id": 1,
      "date": "2019-08-02T07:53:57",
      "date_gmt": "2019-08-02T07:53:57",
      "guid": {
        "rendered": "https:\/\/newapi.getpop.org\/?p=1"
      },
      "modified": "2021-01-14T13:18:39",
      "modified_gmt": "2021-01-14T13:18:39",
      "slug": "hello-world",
      "status": "publish",
      "type": "post",
      "link": "https:\/\/newapi.getpop.org\/uncategorized\/hello-world\/",
      "title": {
        "rendered": "Hello world!"
      },
      "content": {
        "rendered": "\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!<\/p>\n\n\n\n<p>I&#8217;m demonstrating a Youtube video:<\/p>\n\n\n\n<figure class=\"wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio\"><div class=\"wp-block-embed__wrapper\">\n<iframe loading=\"lazy\" title=\"Introduction to the Component-based API by Leonardo Losoviz | JSConf.Asia 2019\" width=\"750\" height=\"422\" src=\"https:\/\/www.youtube.com\/embed\/9pT-q0SSYow?feature=oembed\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen><\/iframe>\n<\/div><figcaption>This is my presentation in JSConf Asia 2019<\/figcaption><\/figure>\n",
        "protected": false
      },
      "excerpt": {
        "rendered": "<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing! I&#8217;m demonstrating a Youtube video:<\/p>\n",
        "protected": false
      },
      "author": 1,
      "featured_media": 0,
      "comment_status": "closed",
      "ping_status": "open",
      "sticky": false,
      "template": "",
      "format": "standard",
      "meta": [],
      "categories": [
        1
      ],
      "tags": [
        193,
        173
      ]
    }
  }
}
```

### `_sendJSONObjectCollectionHTTPRequest`

This field is similar to `_sendJSONObjectItemHTTPRequest`, but it retrieves a collection of JSON objects, as from the WP REST API endpoint `/wp-json/wp/v2/posts/`.

This query:

```graphql
{
  postData: _sendJSONObjectItemHTTPRequest(input: { url: "https://newapi.getpop.org/wp-json/wp/v2/posts/?per_page=3&_fields=id,type,title,date" } )
}
```

...retrieves this response:

```json
{
  "data": {
    "postData": [
      {
        "id": 1692,
        "date": "2022-04-26T10:10:08",
        "type": "post",
        "title": {
          "rendered": "My Blogroll"
        }
      },
      {
        "id": 1657,
        "date": "2020-12-21T08:24:18",
        "type": "post",
        "title": {
          "rendered": "A tale of two cities &#8211; teaser"
        }
      },
      {
        "id": 1499,
        "date": "2019-08-08T02:49:36",
        "type": "post",
        "title": {
          "rendered": "COPE with WordPress: Post demo containing plenty of blocks"
        }
      }
    ]
  }
}
```

### `_sendHTTPRequest`

This field retrieves an `HTTPResponse` object with all properties from the response, so we can independently query the body (which is of type `String`, i.e. it is not cast as JSON), the status code, content type and headers.

For instance, the following query:

```graphql
{
  _sendHTTPRequest(
    input: {
      url: "https://newapi.getpop.org/wp-json/wp/v2/comments/11/?_fields=id,date,content"
    }
  ) {
    statusCode
    contentType
    headers
    body
    contentLengthHeader: header(name: "Content-Length")
    cacheControlHeader: header(name: "Cache-Control")
  }
}
```

...brings this response:

```json
{
  "data": {
    "_sendHTTPRequest": {
      "statusCode": 200,
      "contentType": "application\/json; charset=UTF-8",
      "headers": {
        "Access-Control-Allow-Headers": "Authorization, X-WP-Nonce, Content-Disposition, Content-MD5, Content-Type",
        "Access-Control-Expose-Headers": "X-WP-Total, X-WP-TotalPages, Link",
        "Allow": "GET",
        "Cache-Control": "max-age=300,no-store",
        "Content-Length": "508"
      },
      "body": "{\"id\":11,\"date\":\"2020-12-12T04:09:36\",\"content\":{\"rendered\":\"<p>Wow, this sounds awesome!<\\\/p>\\n\"},\"_links\":{\"self\":[{\"href\":\"https:\\\/\\\/newapi.getpop.org\\\/wp-json\\\/wp\\\/v2\\\/comments\\\/11\"}],\"collection\":[{\"href\":\"https:\\\/\\\/newapi.getpop.org\\\/wp-json\\\/wp\\\/v2\\\/comments\"}],\"author\":[{\"embeddable\":true,\"href\":\"https:\\\/\\\/newapi.getpop.org\\\/wp-json\\\/wp\\\/v2\\\/users\\\/3\"}],\"up\":[{\"embeddable\":true,\"post_type\":\"post\",\"href\":\"https:\\\/\\\/newapi.getpop.org\\\/wp-json\\\/wp\\\/v2\\\/posts\\\/28\"}]}}",
      "contentLengthHeader": "508",
      "cacheControlHeader": "max-age=300,no-store"
    }
  }
}
```

### `_sendGraphQLHTTPRequest`

Executing the following query:

```graphql
{
  graphQLRequest: _sendGraphQLHTTPRequest(
    input: {
      endpoint: "https://newapi.getpop.org/api/graphql/"
      query: """
        query GetPosts($postIDs: [ID]!) {
          posts(filter: { ids: $postIDs }) {
            id
            title
          }
        }
      """
      variables: [
        {
          name: "postIDs",
          value: [1, 1499]
        }
      ]
    }
  )
}
```

...brings the following response:

```json
{
  "data": {
    "graphQLRequest": {
      "data": {
        "posts": [
          {
            "id": 1499,
            "title": "COPE with WordPress: Post demo containing plenty of blocks"
          },
          {
            "id": 1,
            "title": "Hello world!"
          }
        ]
      }
    }
  }
}
```

### Multiple-request fields: `_sendJSONObjectItemHTTPRequests`, `_sendJSONObjectCollectionHTTPRequests`, `_sendGraphQLHTTPRequests` and `_sendHTTPRequests`

These fields work similar to their corresponding non-multiple fields, but they retrieve data from several endpoints at once, either asynchronously (in parallel) or synchronously (one after the other). The responses are placed in a list, in the same order in which the URLs were defined in the `inputs` argument.

For instance, the following query:

```graphql
{
  weatherForecasts: _sendJSONObjectItemHTTPRequests(inputs: [
    {
      url: "https://api.weather.gov/gridpoints/TOP/31,80/forecast"
    },
    {
      url: "https://api.weather.gov/gridpoints/TOP/41,55/forecast"
    }
  ])
}
```

...produces this response:

```json
{
  "data": {
    "weatherForecasts": [
      {
        "type": "Feature",
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -97.1089731,
                39.766826299999998
              ],
              [
                -97.108526900000001,
                39.744778799999999
              ]
            ]
          ]
        },
        "properties": {
          "updated": "2022-03-04T09:39:46+00:00",
          "units": "us",
          "forecastGenerator": "BaselineForecastGenerator",
          "generatedAt": "2022-03-04T10:31:47+00:00",
          "updateTime": "2022-03-04T09:39:46+00:00",
          "validTimes": "2022-03-04T03:00:00+00:00/P7DT22H",
          "elevation": {
            "unitCode": "wmoUnit:m",
            "value": 441.95999999999998
          }
        }
      },
      {
        "type": "Feature",
        "geometry": {
          "type": "Polygon",
          "coordinates": [
            [
              [
                -96.812529900000001,
                39.218048000000003
              ],
              [
                -96.812148500000006,
                39.195940300000004
              ]
            ]
          ]
        },
        "properties": {
          "updated": "2022-03-04T09:39:46+00:00",
          "units": "us",
          "forecastGenerator": "BaselineForecastGenerator",
          "generatedAt": "2022-03-04T10:42:26+00:00",
          "updateTime": "2022-03-04T09:39:46+00:00",
          "validTimes": "2022-03-04T03:00:00+00:00/P7DT22H",
          "elevation": {
            "unitCode": "wmoUnit:m",
            "value": 409.04160000000002
          }
        }
      }
    ]
  }
}
```

## Synchronous vs Asynchronous execution

These fields allow us to execute multiple requests:

- `_sendHTTPRequests`
- `_sendJSONObjectItemHTTPRequests`
- `_sendJSONObjectCollectionHTTPRequests`
- `_sendGraphQLHTTPRequests`

These fields receive input `$async`, to define if the requests must be executed synchronously (`$async => false`) or asynchronously.

### Synchronous execution

The HTTP requests are executed in order, with each one executed right after the previous one has been resolved.

When all HTTP requests are successful, the field will print an array with their responses, in the same order as they appear in the input list.

If any HTTP request fails, then the execution stops right there, i.e. the subsequent HTTP requests in the input list are not executed.

Some possible causes of failing HTTP requests are:

- The server to connect to is offline
- The status code of the response is not 200: a 500 internal error, a 404 not found, a 403 forbidden, etc.
- The content type of the response is not `application/json`

(The latter two are treated as an error by `_sendJSONObjectItemHTTPRequests`, `_sendJSONObjectCollectionHTTPRequests` and `_sendGraphQLHTTPRequests`, which expect to handle `JSON` types only, but not by `_sendHTTPRequests`, which is not opinionated.)

In case of error, the field returns `null` (i.e. the response for any previous successful HTTP request will not be printed), and the error entry will contain extension `httpRequestInputArrayPosition` to indicate which is the item from the input list that failed (starting from 0):

```json
{
  "errors": [
    {
      "message": "Server error: `GET https:\/\/mysite.com\/page-triggering-some-500-error` resulted in a `500 Internal Server Error` response",
      "extensions": {
        "httpRequestInputArrayPosition": 0,
        "field": "_sendJSONObjectItemHTTPRequests(async: false, inputs: [{url: \"https:\/\/mysite.com\/page-triggering-some-500-error\"}, {url: \"https:\/\/mysite.com\/wp-json\/wp\/v2\/posts\/1\/\"}, {url: \"https:\/\/mysite.com\/wp-json\/wp\/v2\/users\/1\/\"}])"
      }
    }
  ],
  "data": {
    "_sendJSONObjectItemHTTPRequests": null
  }
}
```

### Asynchronous execution

All HTTP requests are executed concurrently (i.e. in parallel), and it is not known in what order will the HTTP requests be resolved.

When all HTTP requests are successful, the field will print an array with their responses, in the same order as they appear in the input list.

Whenever any one HTTP request fails, the execution stops immediately, however by then all other HTTP requests may have been executed too.

In addition, the server will not indicate which is the item in the list that failed (notice that there is not `httpRequestInputArrayPosition` extension in the response below):

```json
{
  "errors": [
    {
      "message": "Server error: `GET https:\/\/mysite.com\/page-triggering-some-500-error` resulted in a `500 Internal Server Error` response",
      "extensions": {
        "field": "_sendJSONObjectItemHTTPRequests(async: true, inputs: [{url: \"https:\/\/mysite.com\/page-triggering-some-500-error\"}, {url: \"https:\/\/mysite.com\/wp-json\/wp\/v2\/posts\/1\/\"}, {url: \"https:\/\/mysite.com\/wp-json\/wp\/v2\/users\/1\/\"}])"
      }
    }
  ],
  "data": {
    "_sendJSONObjectItemHTTPRequests": null
  }
}
```

## Global Fields

All these fields are **Global Fields**, so they are added to every single type in the GraphQL schema: in `QueryRoot`, but also in `Post`, `User`, `Comment`, etc.

This allows us to connect to some external API endpoint generated on runtime in the same GraphQL query, based on the data stored on some entity.

For instance, we can iterate a list of users in our database and, for each, connect to an external system (such as a CRM) to retrieve further data about them.

In this query, we generate the API endpoint using the **Field to Input** feature, and the `_arrayJoin` function field:

```graphql
{
  users(
    pagination: { limit: 2 },
    sort: { order: ASC, by: ID }
  ) {
    id
    endpoint: _arrayJoin(values: [
      "https://newapi.getpop.org/wp-json/wp/v2/users/",
      $__id,
      "?_fields=name"
    ])
    _sendJSONObjectItemHTTPRequest(input: { url: $__endpoint } )
  }
}
```

...producing:

```json
{
  "data": {
    "users": [
      {
        "id": 1,
        "endpoint": "https://newapi.getpop.org/wp-json/wp/v2/users/1?_fields=name",
        "_sendJSONObjectItemHTTPRequest": {
          "name": "leo",
          "_links": {
            "self": [
              {
                "href": "https://newapi.getpop.org/wp-json/wp/v2/users/1"
              }
            ],
            "collection": [
              {
                "href": "https://newapi.getpop.org/wp-json/wp/v2/users"
              }
            ]
          }
        }
      },
      {
        "id": 2,
        "endpoint": "https://newapi.getpop.org/wp-json/wp/v2/users/2?_fields=name",
        "_sendJSONObjectItemHTTPRequest": {
          "name": "themedemos",
          "_links": {
            "self": [
              {
                "href": "https://newapi.getpop.org/wp-json/wp/v2/users/2"
              }
            ],
            "collection": [
              {
                "href": "https://newapi.getpop.org/wp-json/wp/v2/users"
              }
            ]
          }
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
- [“Private GraphQL Server for WordPress” Bundle](../../../../../bundle-extensions/private-graphql-server-for-wordpress/docs/modules/private-graphql-server-for-wordpress/en.md)
- [“Selective Content Import, Export & Sync for WordPress” Bundle](../../../../../bundle-extensions/selective-content-import-export-and-sync-for-wordpress/docs/modules/selective-content-import-export-and-sync-for-wordpress/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md)
- [“Versatile WordPress Request API” Bundle](../../../../../bundle-extensions/versatile-wordpress-request-api/docs/modules/versatile-wordpress-request-api/en.md) -->
