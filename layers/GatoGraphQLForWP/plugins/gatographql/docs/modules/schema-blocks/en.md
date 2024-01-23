# Blocks

Integration of Gutenberg blocks into the GraphQL schema.

## Description

This module adds `Block` types to the GraphQL schema, retrieved via the following fields added to all `CustomPost` types (such as `Post` and `Page`):

- `blocks`
- `blockDataItems`
- `blockFlattenedDataItems`

This module is disabled if the [Classic Editor](https://wordpress.org/plugins/classic-editor/) plugin is active.

## `blocks`

Field `CustomPost.blocks: [BlockUnion!]` retrieves the list of all the blocks contained in the custom post.

`blocks` returns a List of the Block types that have been mapped to the GraphQL schema. These Block types are all part of the `BlockUnion` type, and implement the `Block` interface.

The plugin implements one Block type, `GenericBlock`, which is already sufficient to retrieve the data for any block (via field `attributes: JSONObject`).

This query:

```graphql
{
  post(by: { id: 1 }) {
    blocks {
      ...on Block {
        name
        attributes
        innerBlocks {
          ...on Block {
              name
              attributes
              innerBlocks {
                ...on Block {
                  name
                  attributes
                }
              }
            }
          }
        }
      }
    }
  }
}
```

...will produce this response:

```json
{
  "data": {
    "post": {
      "blocks": [
        {
          "name": "core/gallery",
          "attributes": {
            "linkTo": "none",
            "className": "alignnone",
            "images": [
              {
                "url": "https://d.pr/i/zd7Ehu+",
                "alt": "",
                "id": "1706"
              },
              {
                "url": "https://d.pr/i/jXLtzZ+",
                "alt": "",
                "id": "1705"
              }
            ],
            "ids": [],
            "shortCodeTransforms": [],
            "imageCrop": true,
            "fixedHeight": true,
            "sizeSlug": "large",
            "allowResize": false
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/list",
          "attributes": {
            "ordered": false,
            "values": "<li>List item 1</li><li>List item 2</li><li>List item 3</li><li>List item 4</li>"
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlocks": [
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/image",
                  "attributes": {
                    "id": 1701,
                    "className": "layout-column-1",
                    "url": "https://d.pr/i/fW6V3V+",
                    "alt": ""
                  },
                  "innerBlocks": null
                }
              ]
            },
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/paragraph",
                  "attributes": {
                    "className": "layout-column-2",
                    "content": "Phosfluorescently morph intuitive relationships rather than customer directed human capital.",
                    "dropCap": false
                  },
                  "innerBlocks": null
                }
              ]
            }
          ]
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlocks": [
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/image",
                  "attributes": {
                    "id": 1701,
                    "className": "layout-column-1",
                    "url": "https://d.pr/i/fW6V3V+",
                    "alt": ""
                  },
                  "innerBlocks": null
                },
                {
                  "name": "core/columns",
                  "attributes": {
                    "isStackedOnMobile": true
                  },
                  "innerBlocks": [
                    {
                      "name": "core/column",
                      "attributes": {
                        "width": "33.33%"
                      },
                      "innerBlocks": [
                        {
                          "name": "core/heading",
                          "attributes": {
                            "fontSize": "large",
                            "content": "Life is so rich",
                            "level": 2
                          },
                          "innerBlocks": null
                        },
                        {
                          "name": "core/heading",
                          "attributes": {
                            "level": 3,
                            "content": "Life is so dynamic"
                          },
                          "innerBlocks": null
                        }
                      ]
                    },
                    {
                      "name": "core/column",
                      "attributes": {
                        "width": "66.66%"
                      },
                      "innerBlocks": [
                        {
                          "name": "core/paragraph",
                          "attributes": {
                            "content": "This rhyming poem is the spark that can reignite the fires within you. It challenges you to go out and live your life in the present moment as a \u201chero\u201d and leave your mark on this world.",
                            "dropCap": false
                          },
                          "innerBlocks": null
                        },
                        {
                          "name": "core/columns",
                          "attributes": {
                            "isStackedOnMobile": true
                          },
                          "innerBlocks": [
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": [
                                {
                                  "name": "core/image",
                                  "attributes": {
                                    "id": 1361,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-1024x622.jpg",
                                    "alt": ""
                                  },
                                  "innerBlocks": null
                                }
                              ]
                            },
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": null
                            },
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": [
                                {
                                  "name": "core/image",
                                  "attributes": {
                                    "id": 1362,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-suki-1024x598.png",
                                    "alt": ""
                                  },
                                  "innerBlocks": null
                                }
                              ]
                            }
                          ]
                        }
                      ]
                    }
                  ]
                }
              ]
            }
          ]
        }
      ]
    }
  }
}
```

The GraphQL schema for the Block types looks like this:

```graphql
interface Block {
  name: String!
  attributes: JSONObject
  innerBlocks: [BlockUnion!]
  contentSource: HTML!
}

type GenericBlock implements Block {
  name: String!
  attributes: JSONObject
  innerBlocks: [BlockUnion!]
  contentSource: HTML!
}

union BlockUnion = GenericBlock
```

### `Block` fields

The `Block` interface (and, as such, the `GeneralBlock` type) contains the following fields:

- `name` retrieves the name of the block: `"core/paragraph"`, `"core/heading"` `"core/image"`, etc.
- `attributes` retrieves a JSON object containing all the attributes from the block.
- `innerBlocks` retrieves `[BlockUnion!]`, hence we can query it to navigate the hierarchy of blocks containing inner blocks, and fetching the data for all of them, for as many levels down as we have in our content.
- `contentSource` retrieves the block's (Gutenberg) HTML source code, including the comment delimiters that contain the attributes. However, this field does not retrieve the exact same data as how it is stored in the DB (see [#2346](https://github.com/GatoGraphQL/GatoGraphQL/issues/2346)), so use this field with care.

### Directly retrieving `GeneralBlock` (instead of `BlockUnion`)

As currently there is only one Block type mapping blocks –`GeneralBlock`– it makes sense to have `CustomPost.blocks` (and also `Block.innerBlocks`) retrieve this type directly, instead of the `BlockUnion` type.

We can do this in the Settings page under the Blocks tab, by ticking on option `Use single type instead of union type?`:

<div class="img-width-1024" markdown=1>

![Configuring to directly retrieve `GeneralBlock` instead of `BlockUnion`](../../images/settings-blocks-single-type.png)

</div>

Then, the GraphQL query is simplified:

```graphql
{
  post(by: { id: 1 }) {
    blocks {
      name
      attributes
      innerBlocks {
        name
        attributes
      }
    }
  }
}
```

Please notice that keeping the response type as `BlockUnion` is good for forward compatibility: If we ever decide to add block-specific types to the schema (see section below), then there will be no breaking changes.

### Mapping block-specific types

The `JSONObject` type (as retrieved by `Block.attributes`) is not strictly typed: Its properties can have any type and cardinality (`String`, `Int`, `[Boolean!]`, etc), so we need to know this information for every block and deal with each case in the client.

If we need strict typing, we must extend the GraphQL schema via PHP code, adding block-specific types that map a block's specific attributes as fields, and make them part of `BlockUnion`.

For instance, we can add type `CoreParagraphBlock` that maps the `core/paragraph` block, with field `content` of type `String`.

Refer to the documentation in [`GatoGraphQL/GatoGraphQL`](https://github.com/GatoGraphQL/GatoGraphQL) to learn how to extend the GraphQL schema (currently a work in progress).

### Filtering blocks

Field `CustomPost.blocks` contains argument `filterBy` with two properties: `include` and `exclude`. We can use these to filter what blocks are retrieved, by the block name:

```graphql
{
  post(by: { id: 1 }) {
    id
    blocks(
      filterBy: {
        include: [
          "core/heading",
          "core/gallery"
        ]
      }
    ) {
      name
      attributes
    }
  }
}
```

This will produce:

```json
{
  "data": {
    "post": {
      "blocks": [
        {
          "name": "core/gallery",
          "attributes": {
            "linkTo": "none",
            "className": "alignnone",
            "images": [
              {
                "url": "https://d.pr/i/zd7Ehu+",
                "alt": "",
                "id": "1706"
              },
              {
                "url": "https://d.pr/i/jXLtzZ+",
                "alt": "",
                "id": "1705"
              }
            ],
            "ids": [],
            "shortCodeTransforms": [],
            "imageCrop": true,
            "fixedHeight": true,
            "sizeSlug": "large",
            "allowResize": false
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          },
          "innerBlocks": null
        }
      ]
    }
  }
}
```

Please notice that not all blocks of type `core/heading` have been included: Those which are nested under `core/column` have been excluded, as there is no way to reach them (since blocks `core/columns` and `core/column` are themselves excluded).

### Inconveniences of field `blocks`

Field `blocks` produces the nuissance that, in order to retrieve the whole block data contained in the custom post, including the data for the inner blocks, and their own inner blocks, and so on, we must know how many nested block levels there are in the content, and reflect this information in the GrapQL query.

Or, if we don't know, we must compose the query with enough levels as to be sure that all data will be fetched.

For instance, this query retrieves up to 7 levels of inner block nesting:

```graphql
{
  post(by: { id: 1 }) {
    blocks {
      ...BlockData
    }
  }
}

fragment BlockData on Block {
  name
  attributes
  innerBlocks {
    ...on Block {
      name
      attributes
      innerBlocks {
        ...on Block {
          name
          attributes
          innerBlocks {
            ...on Block {
              name
              attributes
              innerBlocks {
                ...on Block {
                  name
                  attributes
                  innerBlocks {
                    ...on Block {
                      name
                      attributes
                      innerBlocks {
                        ...on Block {
                          name
                          attributes
                          innerBlocks {
                            ...on Block {
                              name
                              attributes
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
```

## `blockDataItems`

It is in order to avoid the inconvenience of how field `blocks` retrieves all data (including for its inner blocks, and their inner blocks, and so on), that there is field `CustomPost.blockDataItems`.

This field, instead of returning `[BlockUnion]`, returns `[JSONObject!]`:

```graphql
type CustomPost {
  blockDataItems: [JSONObject!]
}
```

In other words, instead of following the typical GraphQL way of having entities relate to entities and navigate across them, every Block entity at the top level already produces the whole block data for itself and all of its children, within a single `JSONObject` result.

The JSON object contains the properties for the block (under entries `name` and `attributes`) and for its inner blocks (under entry `innerBlocks`), recursively.

For instance, the following query:

```graphql
{
  post(by: { id: 1 }) {
    blockDataItems
  }
}
```

...will produce:

```json
{
  "data": {
    "post": {
      "blockDataItems": [
        {
          "name": "core/gallery",
          "attributes": {
            "linkTo": "none",
            "className": "alignnone",
            "images": [
              {
                "url": "https://d.pr/i/zd7Ehu+",
                "alt": "",
                "id": "1706"
              },
              {
                "url": "https://d.pr/i/jXLtzZ+",
                "alt": "",
                "id": "1705"
              }
            ],
            "ids": [],
            "shortCodeTransforms": [],
            "imageCrop": true,
            "fixedHeight": true,
            "sizeSlug": "large",
            "allowResize": false
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          }
        },
        {
          "name": "core/list",
          "attributes": {
            "ordered": false,
            "values": "<li>List item 1</li><li>List item 2</li><li>List item 3</li><li>List item 4</li>"
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          }
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlocks": [
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/image",
                  "attributes": {
                    "id": 1701,
                    "className": "layout-column-1",
                    "url": "https://d.pr/i/fW6V3V+",
                    "alt": ""
                  }
                }
              ]
            },
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/paragraph",
                  "attributes": {
                    "className": "layout-column-2",
                    "content": "Phosfluorescently morph intuitive relationships rather than customer directed human capital.",
                    "dropCap": false
                  }
                }
              ]
            }
          ]
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          }
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlocks": [
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/image",
                  "attributes": {
                    "id": 1701,
                    "className": "layout-column-1",
                    "url": "https://d.pr/i/fW6V3V+",
                    "alt": ""
                  }
                },
                {
                  "name": "core/columns",
                  "attributes": {
                    "isStackedOnMobile": true
                  },
                  "innerBlocks": [
                    {
                      "name": "core/column",
                      "attributes": {
                        "width": "33.33%"
                      },
                      "innerBlocks": [
                        {
                          "name": "core/heading",
                          "attributes": {
                            "fontSize": "large",
                            "content": "Life is so rich",
                            "level": 2
                          }
                        },
                        {
                          "name": "core/heading",
                          "attributes": {
                            "level": 3,
                            "content": "Life is so dynamic"
                          }
                        }
                      ]
                    },
                    {
                      "name": "core/column",
                      "attributes": {
                        "width": "66.66%"
                      },
                      "innerBlocks": [
                        {
                          "name": "core/paragraph",
                          "attributes": {
                            "content": "This rhyming poem is the spark that can reignite the fires within you. It challenges you to go out and live your life in the present moment as a \u201chero\u201d and leave your mark on this world.",
                            "dropCap": false
                          }
                        },
                        {
                          "name": "core/columns",
                          "attributes": {
                            "isStackedOnMobile": true
                          },
                          "innerBlocks": [
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": [
                                {
                                  "name": "core/image",
                                  "attributes": {
                                    "id": 1361,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-1024x622.jpg",
                                    "alt": ""
                                  }
                                }
                              ]
                            },
                            {
                              "name": "core/column",
                              "attributes": {}
                            },
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": [
                                {
                                  "name": "core/image",
                                  "attributes": {
                                    "id": 1362,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-suki-1024x598.png",
                                    "alt": ""
                                  }
                                }
                              ]
                            }
                          ]
                        }
                      ]
                    }
                  ]
                }
              ]
            }
          ]
        }
      ]
    }
  }
}
```

### Filtering block data items

Similar to `blocks`, `blockDataItems` also allows to filter what blocks are retrieved, via the `filterBy` argument.

This query:

```graphql
{
  post(by: { id: 1 }) {
    id
    blockDataItems(
      filterBy: {
        include: [
          "core/heading"
        ]
      }
    )
  }
}
```

...will produce:

```json
{
  "data": {
    "post": {
      "blockDataItems": [
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          },
          "innerBlocks": null
        }
      ]
    }
  }
}
```

Please notice that, similar to `blocks`, not all blocks of type `core/heading` have been included: Those which are nested under `core/column` have been excluded, as there is no way to reach them (since blocks `core/columns` and `core/column` are themselves excluded).

## `blockFlattenedDataItems`

Both fields `blocks` and `blockDataItems` allow to filter what blocks are retrieved (via the `filterBy` argument). In both cases, if a block satisfies the inclusion condition, but is nested within a block that does not, then it will be excluded.

There are occasions, though, when we need to retrieve all blocks of a certain type from the custom post, independently of where these blocks are located within the hierarchy. For instance, we may want to include all blocks of type `core/image`, to retrieve all images included in a blog post.

It is to satisfy this need that there is field `CustomPost.blockFlattenedDataItems`. Unlike fields `blocks` and `blockDataItems`, it flattens the block hierarchy into a single level.

This query:

```graphql
{
  post(by: { id: 1 }) {
    blockFlattenedDataItems
  }
}
```

...will produce:

```json
{
  "data": {
    "post": {
      "blockFlattenedDataItems": [
        {
          "name": "core/gallery",
          "attributes": {
            "linkTo": "none",
            "className": "alignnone",
            "images": [
              {
                "url": "https://d.pr/i/zd7Ehu+",
                "alt": "",
                "id": "1706"
              },
              {
                "url": "https://d.pr/i/jXLtzZ+",
                "alt": "",
                "id": "1705"
              }
            ],
            "ids": [],
            "shortCodeTransforms": [],
            "imageCrop": true,
            "fixedHeight": true,
            "sizeSlug": "large",
            "allowResize": false
          },
          "innerBlockPositions": null,
          "parentBlockPosition": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          },
          "innerBlockPositions": null,
          "parentBlockPosition": null
        },
        {
          "name": "core/list",
          "attributes": {
            "ordered": false,
            "values": "<li>List item 1</li><li>List item 2</li><li>List item 3</li><li>List item 4</li>"
          },
          "innerBlockPositions": null,
          "parentBlockPosition": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          },
          "innerBlockPositions": null,
          "parentBlockPosition": null
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlockPositions": [
            5,
            7
          ],
          "parentBlockPosition": null
        },
        {
          "name": "core/column",
          "attributes": {},
          "parentBlockPosition": 4,
          "innerBlockPositions": [
            6
          ]
        },
        {
          "name": "core/image",
          "attributes": {
            "id": 1701,
            "className": "layout-column-1",
            "url": "https://d.pr/i/fW6V3V+",
            "alt": ""
          },
          "parentBlockPosition": 5,
          "innerBlockPositions": null
        },
        {
          "name": "core/column",
          "attributes": {},
          "parentBlockPosition": 4,
          "innerBlockPositions": [
            8
          ]
        },
        {
          "name": "core/paragraph",
          "attributes": {
            "className": "layout-column-2",
            "content": "Phosfluorescently morph intuitive relationships rather than customer directed human capital.",
            "dropCap": false
          },
          "parentBlockPosition": 7,
          "innerBlockPositions": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          },
          "innerBlockPositions": null,
          "parentBlockPosition": null
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlockPositions": [
            11
          ],
          "parentBlockPosition": null
        },
        {
          "name": "core/column",
          "attributes": {},
          "parentBlockPosition": 10,
          "innerBlockPositions": [
            12,
            13
          ]
        },
        {
          "name": "core/image",
          "attributes": {
            "id": 1701,
            "className": "layout-column-1",
            "url": "https://d.pr/i/fW6V3V+",
            "alt": ""
          },
          "parentBlockPosition": 11,
          "innerBlockPositions": null
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "parentBlockPosition": 11,
          "innerBlockPositions": [
            14,
            17
          ]
        },
        {
          "name": "core/column",
          "attributes": {
            "width": "33.33%"
          },
          "parentBlockPosition": 13,
          "innerBlockPositions": [
            15,
            16
          ]
        },
        {
          "name": "core/heading",
          "attributes": {
            "fontSize": "large",
            "content": "Life is so rich",
            "level": 2
          },
          "parentBlockPosition": 14,
          "innerBlockPositions": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "level": 3,
            "content": "Life is so dynamic"
          },
          "parentBlockPosition": 14,
          "innerBlockPositions": null
        },
        {
          "name": "core/column",
          "attributes": {
            "width": "66.66%"
          },
          "parentBlockPosition": 13,
          "innerBlockPositions": [
            18,
            19
          ]
        },
        {
          "name": "core/paragraph",
          "attributes": {
            "content": "This rhyming poem is the spark that can reignite the fires within you. It challenges you to go out and live your life in the present moment as a \u201chero\u201d and leave your mark on this world.",
            "dropCap": false
          },
          "parentBlockPosition": 17,
          "innerBlockPositions": null
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "parentBlockPosition": 17,
          "innerBlockPositions": [
            20,
            22,
            23
          ]
        },
        {
          "name": "core/column",
          "attributes": {},
          "parentBlockPosition": 19,
          "innerBlockPositions": [
            21
          ]
        },
        {
          "name": "core/image",
          "attributes": {
            "id": 1361,
            "sizeSlug": "large",
            "linkDestination": "none",
            "url": "https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-1024x622.jpg",
            "alt": ""
          },
          "parentBlockPosition": 20,
          "innerBlockPositions": null
        },
        {
          "name": "core/column",
          "attributes": {},
          "parentBlockPosition": 19,
          "innerBlockPositions": null
        },
        {
          "name": "core/column",
          "attributes": {},
          "parentBlockPosition": 19,
          "innerBlockPositions": [
            24
          ]
        },
        {
          "name": "core/image",
          "attributes": {
            "id": 1362,
            "sizeSlug": "large",
            "linkDestination": "none",
            "url": "https://gatographql.lndo.site/wp-content/uploads/2022/05/GatoGraphQL-logo-suki-1024x598.png",
            "alt": ""
          },
          "parentBlockPosition": 23,
          "innerBlockPositions": null
        }
      ]
    }
  }
}
```

Please notice how attribute `innerBlocks` has disappeared, as the blocks are not nested anymore. In its place, the response includes two other attributes (which allow us to recreate the block hierarchy):

- `parentBlockPosition`: The position of the block's parent block within the returned array, or `null` if it is a top-level block
- `innerBlockPositions`: An array with the positions of the block's inner blocks within the returned array

### Filtering the flattened block data items

Now that the block hierarchy has been flattened, filtering by `core/heading` will produce all of these blocks (even if any of them is originally nested under a block that has been excluded).

This query:

```graphql
{
  post(by: { id: 1 }) {
    id
    blockFlattenedDataItems(
      filterBy: {
        include: [
          "core/heading"
        ]
      }
    )
  }
}
```

...will produce:

```json
{
  "data": {
    "post": {
      "blockFlattenedDataItems": [
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "fontSize": "large",
            "content": "Life is so rich",
            "level": 2
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "level": 3,
            "content": "Life is so dynamic"
          }
        }
      ]
    }
  }
}
```

Please notice that the two additional attributes, `parentBlockPosition` and `innerBlockPositions`, are removed when filtering, as they don't make sense anymore.

## Preact example

Please check the [Preact example](https://github.com/Automattic/vip-block-data-api/tree/1.0.0/#preact-example) in `Automattic/vip-block-data-api`, to see an example of how to map the block data into customizedclient-side JavaScript components.

## Limitations

Please check the [Limitations](https://github.com/Automattic/vip-block-data-api/tree/1.0.0/#limitations) section in `Automattic/vip-block-data-api`, to learn about the limitations from retrieving block data.

## Acknowledgements

The logic to retrieve the (Gutenberg) block data is based on [`Automattic/vip-block-data-api`](https://github.com/Automattic/vip-block-data-api/).

Many thanks to the folks who contributed to that project. ❤️
