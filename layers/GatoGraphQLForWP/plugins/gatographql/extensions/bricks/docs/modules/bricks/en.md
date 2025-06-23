# Bricks

Integration with the <a href="https://bricksbuilder.io/" target="_blank" rel="nofollow">Bricks</a> plugin.

The GraphQL schema is provided with fields and mutations to fetch and update Bricks data from pages and templates.

## Fields

Query the Bricks data from a custom post, retrieved via the following fields added to all `CustomPost` types (such as `Post`, `Page` and `GenericCustomPost`):

- `bricksData`

In addition, query the Bricks components via root field `bricksComponents`.

### `bricksData`

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

We can also filter elements by name via param `filterBy`, which accepts `include` and `exclude`.

Running this query:

```graphql
{
  post(by: { id: 1 }) {
    bricksData(filterBy: {include: ["post-comments", "post-author"]})
  }
}
```

...will produce this response:

```json
{
  "data": {
    "post": {
      "bricksData": [
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
        }
      ]
    }
  }
}
```

### `bricksComponents`

Field `Root.bricksComponents: [JSONObject!]!` retrieves the Bricks data for the global components, as stored by Bricks under option `bricks_components`.

Running this query:

```graphql
{
  bricksComponents
}
```

...might produce this response:

```json
[
  {
    "id": "fufxfs",
    "category": "",
    "desc": "",
    "elements": [
      {
        "id": "fufxfs",
        "name": "container",
        "settings": {
          "_width": "50%",
          "_alignItems": "flex-start",
          "_padding": {
            "left": "",
            "right": 50
          },
          "_width:mobile_portrait": "100%",
          "_padding:tablet_portrait": {
            "right": "25"
          },
          "_padding:mobile_portrait": {
            "right": "0"
          },
          "_alignSelf": "center",
          "_order:mobile_portrait": "2",
          "_margin:mobile_portrait": {
            "top": "60"
          },
          "_flexShrink": "0"
        },
        "children": [
          "e8fab6",
          "52c06c"
        ],
        "parent": 0,
        "label": "Icons"
      },
      {
        "id": "e8fab6",
        "name": "icon-box",
        "settings": {
          "icon": {
            "library": "fontawesomeSolid",
            "icon": "fas fa-check-square"
          },
          "content": "<h4>Business Professionals<\/h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit aliquam.<\/p>",
          "textAlign": "left",
          "iconPosition": "left",
          "verticalAlign": "flex-start",
          "_padding": {
            "top": "20",
            "right": "20",
            "bottom": "20",
            "left": 20
          },
          "_background": {
            "color": {
              "hex": "#ffffff"
            }
          },
          "_border": {
            "width": {
              "top": "1",
              "right": "1",
              "bottom": "1",
              "left": "1"
            },
            "style": "solid",
            "radius": {
              "top": "10"
            },
            "color": {
              "hex": "#f2f2f2"
            }
          },
          "typographyHeading": {
            "font-size": "16px",
            "font-family": "Open Sans",
            "font-weight": "600",
            "color": {
              "hex": "#30354a"
            }
          },
          "iconColor": {
            "hex": "#ff57a2"
          },
          "iconSize": "20px",
          "contentMargin": {
            "top": 8
          },
          "_boxShadow": {
            "values": {
              "offsetX": "2",
              "offsetY": "2",
              "blur": "25"
            },
            "color": {
              "hex": "#000000",
              "rgb": "rgba(0, 0, 0, 0.11)",
              "hsl": "hsla(0, 0%, 0%, 0.11)"
            }
          },
          "typographyBody": {
            "color": {
              "hex": "#919191"
            },
            "font-size": "13px",
            "font-family": "Open Sans"
          },
          "iconPadding": {
            "top": "15",
            "right": "15",
            "bottom": "15",
            "left": "15"
          },
          "iconBackgroundColor": {
            "hex": "#ff57a2",
            "rgb": "rgba(255, 87, 162, 0.2)",
            "hsl": "hsla(333, 100%, 67%, 0.2)"
          },
          "iconBorder": {
            "radius": {
              "top": "100",
              "right": "100",
              "bottom": "100",
              "left": "100"
            }
          },
          "_boxShadow:hover": {
            "values": {
              "offsetX": "2",
              "offsetY": "2",
              "blur": "15"
            },
            "color": {
              "hex": "#000000",
              "rgb": "rgba(0, 0, 0, 0.2)",
              "hsl": "hsla(0, 0%, 0%, 0.2)"
            }
          }
        },
        "children": [],
        "parent": "fufxfs"
      },
      {
        "id": "52c06c",
        "name": "icon-box",
        "settings": {
          "icon": {
            "library": "fontawesomeSolid",
            "icon": "fas fa-check-square"
          },
          "content": "<h4>Cloud Services<\/h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit aliquam.<\/p>",
          "textAlign": "left",
          "iconPosition": "left",
          "verticalAlign": "flex-start",
          "_padding": {
            "top": "20",
            "right": "20",
            "bottom": "20",
            "left": 20
          },
          "_background": {
            "color": {
              "hex": "#ffffff"
            }
          },
          "_border": {
            "width": {
              "top": "1",
              "right": "1",
              "bottom": "1",
              "left": "1"
            },
            "style": "solid",
            "radius": {
              "top": "10"
            },
            "color": {
              "hex": "#f2f2f2"
            }
          },
          "typographyHeading": {
            "font-size": "16px",
            "font-family": "Open Sans",
            "font-weight": "600",
            "color": {
              "hex": "#30354a"
            },
            "line-height": "1none"
          },
          "iconColor": {
            "hex": "#3d30ba"
          },
          "iconSize": "20px",
          "contentMargin": {
            "top": 8
          },
          "_boxShadow": {
            "values": {
              "offsetX": "2",
              "offsetY": "2",
              "blur": "25"
            },
            "color": {
              "hex": "#000000",
              "rgb": "rgba(0, 0, 0, 0.11)",
              "hsl": "hsla(0, 0%, 0%, 0.11)"
            }
          },
          "typographyBody": {
            "color": {
              "hex": "#919191"
            },
            "font-size": "13px",
            "font-family": "Open Sans"
          },
          "iconPadding": {
            "top": "15",
            "right": "15",
            "bottom": "15",
            "left": "15"
          },
          "iconBackgroundColor": {
            "hex": "#3e30bb",
            "rgb": "rgba(62, 48, 187, 0.2)",
            "hsl": "hsla(246, 59%, 46%, 0.2)"
          },
          "iconBorder": {
            "radius": {
              "top": "100",
              "right": "100",
              "bottom": "100",
              "left": "100"
            }
          },
          "_margin": {
            "top": 30
          },
          "_boxShadow:hover": {
            "values": {
              "offsetX": "2",
              "offsetY": "2",
              "blur": "15"
            },
            "color": {
              "hex": "#000000",
              "rgb": "rgba(0, 0, 0, 0.2)",
              "hsl": "hsla(0, 0%, 0%, 0.2)"
            }
          }
        },
        "children": [],
        "parent": "fufxfs"
      }
    ],
    "properties": [],
    "_created": 1750156913,
    "_user_id": 1,
    "_version": "2.0-beta"
  }
]
```

## Mutations

Update the Bricks data for a custom post via these mutations:

- `bricksSetCustomPostElementData`: sets the Bricks data for the custom post, expecting a JSON with the same format by Bricks
- `bricksMergeCustomPostElementDataItem`: override the value of specific elements from a custom post's Bricks data
- `bricksRegenerateCustomPostElementIDSet`: regenerates the element IDs on a custom post's Bricks data
