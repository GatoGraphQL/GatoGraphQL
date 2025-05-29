# Bricks

<!-- @todo: Complete Bricks docs -->

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
          "id": "164e55c4",
          "elType": "container",
          "settings": {
            "layout": "full_width",
            "flex_gap": {
              "size": 0,
              "unit": "px",
              "column": "0",
              "row": "0",
              "isLinked": true
            },
            "min_height": {
              "unit": "vh",
              "size": 100,
              "sizes": []
            },
            "flex_align_items": "stretch",
            "content_position": "middle",
            "structure": "20",
            "margin": {
              "unit": "%",
              "top": "",
              "right": 0,
              "bottom": "",
              "left": 0,
              "isLinked": true
            },
            "padding": {
              "unit": "%",
              "top": "0",
              "right": "6",
              "bottom": "0",
              "left": "6",
              "isLinked": false
            },
            "margin_tablet": {
              "unit": "%",
              "top": "12",
              "right": 0,
              "bottom": "0",
              "left": 0,
              "isLinked": false
            },
            "margin_mobile": {
              "unit": "%",
              "top": "20",
              "right": 0,
              "bottom": "0",
              "left": 0,
              "isLinked": false
            },
            "padding_tablet": {
              "unit": "%",
              "top": "",
              "right": "",
              "bottom": "",
              "left": "",
              "isLinked": true
            },
            "z_index": 1,
            "_title": "Hero",
            "flex_direction": "row",
            "content_width": "full",
            "flex_direction_tablet": "column"
          },
          "elements": [
            {
              "id": "600c1786",
              "elType": "container",
              "settings": {
                "_column_size": 50,
                "width": {
                  "size": 50,
                  "unit": "%"
                },
                "padding": {
                  "unit": "%",
                  "top": "0",
                  "right": "12",
                  "bottom": "0",
                  "left": "0",
                  "isLinked": false
                },
                "width_tablet": {
                  "size": 100,
                  "unit": "%"
                },
                "align_tablet": "center",
                "flex_gap": {
                  "size": 20,
                  "unit": "px",
                  "column": "20",
                  "row": "20",
                  "isLinked": true
                },
                "padding_tablet": {
                  "unit": "%",
                  "top": "0",
                  "right": "15",
                  "bottom": "0",
                  "left": "15",
                  "isLinked": false
                },
                "padding_mobile": {
                  "unit": "px",
                  "top": "0",
                  "right": "0",
                  "bottom": "0",
                  "left": "0",
                  "isLinked": false
                },
                "content_width": "full",
                "flex_direction": "column",
                "flex_justify_content": "center",
                "flex_align_items": "flex-start",
                "flex_align_items_tablet": "center"
              },
              "elements": [
                {
                  "id": "db84e33",
                  "elType": "widget",
                  "settings": {
                    "title": "Your health, <br><b>on your time<\/b>",
                    "header_size": "h1",
                    "title_color": "#0D3276",
                    "typography_typography": "custom",
                    "typography_font_family": "Poppins",
                    "typography_font_size": {
                      "unit": "px",
                      "size": 76,
                      "sizes": []
                    },
                    "typography_font_weight": "400",
                    "typography_text_transform": "capitalize",
                    "typography_font_style": "normal",
                    "typography_text_decoration": "none",
                    "typography_line_height": {
                      "unit": "em",
                      "size": 1,
                      "sizes": []
                    },
                    "typography_letter_spacing": {
                      "unit": "px",
                      "size": 0,
                      "sizes": []
                    },
                    "_z_index": 1,
                    "align_tablet": "center",
                    "typography_font_size_tablet": {
                      "unit": "px",
                      "size": 55,
                      "sizes": []
                    },
                    "typography_font_size_mobile": {
                      "unit": "px",
                      "size": 40,
                      "sizes": []
                    }
                  },
                  "elements": [],
                  "widgetType": "heading",
                  "htmlCache": "\t\t<div class=\"bricks-widget-container\">\n\t\t\t\t\t<h1 class=\"bricks-heading-title bricks-size-default\">Your health, <br><b>on your time<\/b><\/h1>\t\t\t\t<\/div>\n\t\t"
                }
              ]
            }
          ],
          "isInner": false
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
