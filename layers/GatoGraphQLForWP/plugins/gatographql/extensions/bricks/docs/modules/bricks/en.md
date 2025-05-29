# Bricks

Integration with the <a href="https://bricksbuilder.io/" target="_blank" rel="nofollow">Bricks</a> plugin.

The GraphQL schema is provided with fields and mutations to fetch and update Bricks data from pages and templates.

## Fields

Query the Bricks data from a custom post, retrieved via the following fields added to all `CustomPost` types (such as `Post`, `Page` and `GenericCustomPost`):

- `bricksData`

Field `CustomPost.bricksData: [JSONObject!]` retrieves the Bricks data for the custom post, as stored by Bricks.

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
          "id": "fjiwqp",
          "name": "post-toc",
          "parent": "uuiyqj",
          "children": [],
          "settings": []
        },
        {
          "id": "typize",
          "name": "post-reading-progress-bar",
          "parent": "uuiyqj",
          "children": [],
          "settings": []
        },
        {
          "id": "nquple",
          "name": "post-reading-time",
          "parent": "uuiyqj",
          "children": [],
          "settings": {
            "prefix": "Reading time: ",
            "suffix": " minutes"
          }
        },
        {
          "id": "tyksto",
          "name": "post-navigation",
          "parent": "uuiyqj",
          "children": [],
          "settings": {
            "label": true,
            "title": true,
            "prevArrow": {
              "library": "ionicons",
              "icon": "ion-ios-arrow-back"
            },
            "nextArrow": {
              "library": "ionicons",
              "icon": "ion-ios-arrow-forward"
            },
            "image": true
          }
        },
        {
          "id": "gdoiqo",
          "name": "post-taxonomy",
          "parent": "uuiyqj",
          "children": [],
          "settings": {
            "taxonomy": "post_tag",
            "style": "dark"
          }
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
        },
        {
          "id": "ipoorm",
          "name": "post-sharing",
          "parent": "uuiyqj",
          "children": [],
          "settings": {
            "items": [
              {
                "service": "facebook"
              },
              {
                "service": "twitter"
              },
              {
                "service": "linkedin"
              },
              {
                "service": "whatsapp"
              },
              {
                "service": "pinterest"
              },
              {
                "service": "telegram"
              },
              {
                "service": "vkontakte"
              },
              {
                "service": "bluesky"
              },
              {
                "service": "email"
              }
            ],
            "brandColors": true
          }
        },
        {
          "id": "wzcyug",
          "name": "post-content",
          "parent": "uuiyqj",
          "children": [],
          "settings": []
        },
        {
          "id": "ucuzdk",
          "name": "post-meta",
          "parent": "uuiyqj",
          "children": [],
          "settings": {
            "meta": [
              {
                "dynamicData": "{author_name}",
                "id": "e94a49"
              },
              {
                "dynamicData": "{post_date}",
                "id": "77550d"
              },
              {
                "dynamicData": "{post_comments}",
                "id": "4ef114"
              }
            ]
          }
        }
      ]
    }
  }
}
```

## Mutations

Update the Bricks data for a custom post via these mutations:

- `bricksSetCustomPostElementData`: sets the Bricks data for the custom post, expecting a JSON with the same format by Bricks
- `bricksMergeCustomPostElementDataItem`: override the value of specific elements from a custom post's Bricks data
