# Bricks

Integration with the <a href="https://bricksbuilder.io/" target="_blank" rel="nofollow">Bricks</a> plugin.

<!-- [Watch “How to use the Bricks extension” on YouTube](https://www.youtube.com/watch?v=@todo) -->

---

Interact with Bricks data from pages and templates.

Running this query:

```graphql
{
  post(by: { id: 1 }) {
    bricksData
  }
}
```

...might produce this response:

```json
{
  "data": {
    "post": {
      "bricksData": [
        {
          "id": "oleqdy",
          "name": "section",
          "parent": 0,
          "children": [
            "uuiyqj"
          ],
          "settings": []
        },
        {
          "id": "uuiyqj",
          "name": "container",
          "parent": "oleqdy",
          "children": [
            "ejfwpo",
            "czivwt",
            "ucuzdk",
            "wzcyug",
            "ipoorm",
            "zvgqxx",
            "yrambp",
            "hywkos",
            "gdoiqo",
            "tyksto",
            "nquple",
            "typize",
            "fjiwqp"
          ],
          "settings": []
        },
        {
          "id": "ejfwpo",
          "name": "post-title",
          "parent": "uuiyqj",
          "children": [],
          "settings": {
            "tag": "h1"
          }
        },
        {
          "id": "czivwt",
          "name": "post-excerpt",
          "parent": "uuiyqj",
          "children": [],
          "settings": []
        },
        {
          "id": "hywkos",
          "name": "post-comments",
          "parent": "uuiyqj",
          "children": [],
          "settings": {
            "title": true,
            "avatar": true,
            "formTitle": true,
            "label": true,
            "submitButtonStyle": "primary"
          }
        },
        {
          "id": "yrambp",
          "name": "post-author",
          "parent": "uuiyqj",
          "children": [],
          "settings": {
            "avatar": true,
            "name": true,
            "website": true,
            "bio": true,
            "postsLink": true,
            "postsStyle": "primary"
          }
        },
        {
          "id": "zvgqxx",
          "name": "related-posts",
          "parent": "uuiyqj",
          "children": [],
          "settings": {
            "taxonomies": [
              "category",
              "post_tag"
            ],
            "content": [
              {
                "dynamicData": "{post_title:link}",
                "tag": "h3",
                "dynamicMargin": {
                  "top": 10
                },
                "id": "a667d0"
              },
              {
                "dynamicData": "{post_date}",
                "id": "5bb1b2"
              },
              {
                "dynamicData": "{post_excerpt:20}",
                "dynamicMargin": {
                  "top": 10
                },
                "id": "80e288"
              }
            ]
          }
        }
      ]
    }
  }
}
```
