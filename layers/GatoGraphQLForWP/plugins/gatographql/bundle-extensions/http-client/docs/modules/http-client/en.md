# HTTP Client

Connect to and interact with external services via their APIs.

---

The GraphQL schema is provided with global fields to execute HTTP requests against a webserver and fetch their response.

This query:

```graphql
query {
  postData: _sendJSONObjectItemHTTPRequest(input: {
    url: "https://some-wp-rest-api.com/wp-json/wp/v2/posts/1/"
  })
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
