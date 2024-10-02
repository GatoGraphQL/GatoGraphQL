# Schema Functions

The GraphQL schema is provided with fields and directives which expose functionalities from the PHP programming language.

---

With **HTTP Client**, the GraphQL schema is provided with global fields to execute HTTP requests against a webserver and fetch their response.

This query:

```graphql
query {
  postData: _sendJSONObjectItemHTTPRequest(input: {
    url: "https://some-wp-rest-api.com/wp-json/wp/v2/posts/1/"
  } )
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

---

With **Function Fields**, the GraphQL schema is provided with "function" fields, which are useful for manipulating the data once it has been retrieved, allowing us to transform a field value in whatever way it is required, and granting us powerful data import/export capabilities.

Function fields are global fields, hence they are added to every single type in the GraphQL schema: in `QueryRoot`, but also in `Post`, `User`, etc.

For instance, while we have a `Post.hasComments` fields, we may need the opposite value. Instead of creating a new field `Post.notHasComments` (for which we'd need to edit PHP code), we can use the Field to Input feature to input the value from `hasComments` into a `not` field, thus calculating the new value always within the GraphQL query:

```graphql
query {
  posts {
    id
    hasComments
    notHasComments: _not(value: $__hasComments)
  }
}
```

---

With **Function Directives**, the GraphQL schema is provided with directives useful for manipulating the data once it has been retrieved, allowing us to transform a field value in whatever way it is required.

For instance, this query:

```graphql
query {
  posts {
    title @strUpperCase
  }
}
```

...will produce this response:

```json
{
  "data": {
    "posts": [
      {
        "title": "HELLO WORLD!"
      },
      {
        "title": "LOVELY WEATHER"
      }
    ]
  }
}
```

---

With **Helper Fields**, the GraphQL schema is added fields which provide commonly-used helper functionality.

Helper fields are global fields, hence they are added to every single type in the GraphQL schema: in `QueryRoot`, but also in `Post`, `User`, etc.

In this query, we retrieve the IDs of the users in the website and execute a new GraphQL query passing their ID as parameter:

```graphql
query {
  users {
    userID: id
    url: _urlAddParams(
      url: "https://somewebsite/endpoint/user-data",
      params: {
        userID: $__userID
      }
    )
    headers: _httpRequestHeaders
    headerNameValueEntryList: _objectConvertToNameValueEntryList(
      object: $__headers
    )
    _sendHTTPRequest(input: {
      url: $__url
      options: {
        headers: $__headerNameValueEntryList
      }
    }) {
      statusCode
      contentType
      body
    }
  }
}
```

---

With **Environment Fields**, the GraphQL schema is provided with global field `_env`, which allows to obtain a value from an environment variable, or from a PHP constant (most commonly defined in `wp-config.php`, but can also be defined elsewhere).

This query retrieves the environment constant `GITHUB_ACCESS_TOKEN` which we might set-up to access a private repository in GitHub:

```graphql
query {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
}
```

<!-- ## List of bundled extensions

- [Email Sender](../../../../../extensions/email-sender/docs/modules/email-sender/en.md)
- [Helper Function Collection](../../../../../extensions/helper-function-collection/docs/modules/helper-function-collection/en.md)
- [HTTP Client](../../../../../extensions/http-client/docs/modules/http-client/en.md)
- [HTTP Request via Schema](../../../../../extensions/http-request-via-schema/docs/modules/http-request-via-schema/en.md)
- [PHP Constants and Environment via Schema](../../../../../extensions/php-constants-and-environment-variables-via-schema/docs/modules/php-constants-and-environment-variables-via-schema/en.md)
- [PHP Functions via Schema](../../../../../extensions/php-functions-via-schema/docs/modules/php-functions-via-schema/en.md) -->
