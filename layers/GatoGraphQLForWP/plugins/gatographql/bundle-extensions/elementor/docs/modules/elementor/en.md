# Elementor

Integration with the [Elementor](https://wordpress.org/plugins/elementor/) plugin.

<!-- [Watch “How to use the Elementor extension” on YouTube](https://www.youtube.com/watch?v=@todo) -->

---

Interact with Elementor data from pages and templates.

Running this query:

```graphql
{
  post(by: { id: 1 }) {
    elementorData
  }
}
```

...might produce this response:

```json
{
  "data": {
    "post": {
      "elementorData": [
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
            "padding_tablet": {
              "unit": "%",
              "top": "",
              "right": "",
              "bottom": "",
              "left": "",
              "isLinked": true
            },
            "z_index": 1
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
                    "title_color": "#0D3276"
                  },
                  "elements": [],
                  "widgetType": "heading",
                  "htmlCache": "\t\t<div class=\"elementor-widget-container\">\n\t\t\t\t\t<h1 class=\"elementor-heading-title elementor-size-default\">Your health, <br><b>on your time<\/b><\/h1>\t\t\t\t<\/div>\n\t\t"
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