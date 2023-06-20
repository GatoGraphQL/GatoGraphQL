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
                                    "id": 361,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/graphql-voyager-public-1024x622.jpg",
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
                                    "id": 362,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/namespaced-interactive-schema-1024x598.png",
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
- `contentSource` retrieves the block's (Gutenberg) HTML source code, including the comment delimiters that contain the attributes. However, this field does not retrieve the exact same data as how it is stored in the DB (see <a href="https://github.com/leoloso/PoP/issues/2346" target="_blank">#2346</a>), so use this field with care.
### Directly retrieving `GeneralBlock` (instead of `BlockUnion`)

As currently there is only one Block type mapping blocks –`GeneralBlock`– it makes sense to have `CustomPost.blocks` (and also `Block.innerBlocks`) retrieve this type directly, instead of the `BlockUnion` type.

We can do this in the Settings page under the Blocks tab, by ticking on option `Use single type instead of union type?`:

![Configuring to directly retrieve `GeneralBlock` instead of `BlockUnion`](../../images/settings-blocks-single-type.png)

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

Refer to the documentation in [`leoloso/PoP`](https://github.com/leoloso/PoP) to learn how to extend the GraphQL schema (currently a work in progress).

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

Field `blocks` has the disadvantage that, in order to retrieve the whole block data contained in the custom post, including the data for the inner blocks, and their own inner blocks, and so on, we must know how many nested block levels there are, and reflect that information in the query.

Or, if we don't know, we must compose the GraphQL query with enough levels as to be sure that all data will be fetched.

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

It is in order to avoid the inconvenience of field `blocks` that there is field `CustomPost.blockDataItems`.

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
                                    "id": 361,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/graphql-voyager-public-1024x622.jpg",
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
                                    "id": 362,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/namespaced-interactive-schema-1024x598.png",
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

There are ocassions, though, when we need to retrieve all blocks of a certain type from the custom post, independently of where these blocks are located within the hierarchy. For instance, we may want to include all blocks of type `core/image`, to retrieve all images included in a blog post.

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
            "id": 361,
            "sizeSlug": "large",
            "linkDestination": "none",
            "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/graphql-voyager-public-1024x622.jpg",
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
            "id": 362,
            "sizeSlug": "large",
            "linkDestination": "none",
            "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/namespaced-interactive-schema-1024x598.png",
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

In case we need to recreate the block hierarchy, the response also includes two additional attributes:

- `parentBlockPosition`: The position of the block's parent block within the returned array, or `null` if it is a top-level block
- `innerBlockPositions`: An array with the positions of the block's inner blocks within the returned array.

### Filtering block flattened data items

Now that the block hierarchy has been flattened, filtering by `core/heading` will produce these blocks always, even if originally nested under a block that has been excluded.

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

## Using Block data in the client

Please check the [Preact example](https://github.com/Automattic/vip-block-data-api/#preact-example) in `Automattic/vip-block-data-api`, to see an example of how to map the block data into client-side JavaScript components.

## Limitations

_(This documentation has been copied, and slightly adapted, from the [Limitations](https://github.com/Automattic/vip-block-data-api/#limitations) section in `Automattic/vip-block-data-api`.)_

### Client-side blocks

The Block Data API relies on [server-side registered blocks][wordpress-block-metadata-php-registration] to source attributes from HTML. Custom blocks that register via [`register_block_type()`][wordpress-register-block-type-php] and `block.json` will automatically be available in the Block Data API. All Gutenberg core blocks are registered server-side.

Modern blocks are likely to be registered server-side and work immediately with the Block Data API. However, some custom blocks may only use [`registerBlockType()`][wordpress-register-block-type-js] in JavaScript and will not provide server-side registration. For these blocks, some attribute data may be missing. To address this issue, we recommend:

- Creating a `block.json` file for each of your site's custom blocks.
- Using [`register_block_type()`][wordpress-register-block-type-php] with the `block.json` file to expose the block information to the server.

For more information on using `block.json` to enhance block capabilities, [read this WordPress core post][wordpress-block-json-recommendation].

#### Client-side example

For legacy block content or third-party blocks that are not registered server-side, some attributes may still be available through the Block Data API. For example, this is a hero block that is registered *only* in JavaScript:

```js
blocks.registerBlockType('wpvip/hero-block', {
  title: __('Hero Block', 'wpvip'),
  icon: 'index-card',
  category: 'text',
  attributes: {
    title: {
      type: 'string',
      source: 'html',
      selector: 'h2',
    },
    mediaURL: {
      type: 'string',
      source: 'attribute',
      selector: 'img',
      attribute: 'src',
    },
    content: {
      type: 'string',
      source: 'html',
      selector: '.hero-text',
    },
    mediaID: {
      type: 'number',
    }
  }
});
```

The block's output markup will render like this:

```html
<!-- wp:wpvip/hero-block {"mediaID":9} -->
<div class="wp-block-wpvip-hero-block">
  <h2>Hero title</h2>
  <div class="hero-image">
    <img src="http://my.site/uploads/hero-image.png" />
  </div>
  <p class="hero-text">Hero summary</p>
</div>
<!-- /wp:wpvip/hero-block -->
```

Because the block is not registered server-side, the server is unaware of the block's sourced attributes like `title` and `mediaURL`. The Block Data API can only return a subset of the block's attributes:

```js
[{
  "name": "wpvip/hero-block",
  "attributes": {
    "mediaID": 9
  }
}]
```

`mediaID` is stored directly in the block's delimiter (`<!-- wp:wpvip/hero-block {"mediaID":9} -->`), and will be available in the Block Data API. Any other sourced attributes will be missing.

#### Registering client-side blocks

The example above shows block attributes missing on a client-side block. To fix this problem, the block can be changed to register with a `block.json` via [`register_block_type()`][wordpress-register-block-type-php]:

*block.json*
```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 2,
  "name": "wpvip/hero-block",
  "title": "Hero block",
  "icon": "index-card",
  "category": "text",
  "attributes": {
    "title": {
      "type": "string",
      "source": "html",
      "selector": "h2"
    },
    "mediaURL": {
      "type": "string",
      "source": "attribute",
      "selector": "img",
      "attribute": "src"
    },
    "content": {
      "type": "string",
      "source": "html",
      "selector": ".hero-text"
    },
    "mediaID": {
      "type": "number"
    }
  }
}
```

The `block.json` file is used to register the block both server-side and client-side:

*In PHP plugin code*:

```php
register_block_type( __DIR__ . '/block.json' );
```

*In JavaScript*:

```js
import metadata from './block.json';

registerBlockType( metadata, {
  edit: Edit,
  // ...other client-side settings
} );
```

After server-side registration, the block's full structure is available via the Block Data API:

```js
[{
  "name": "wpvip/hero-block",
  "attributes": {
    "mediaID": 9,
    "title": "Hero title",
    "mediaURL": "http://my.site/uploads/hero-image.png",
    "content": "Hero summary"
  }
}]
```

### Rich text support

Blocks with [`html`-sourced attributes][wordpress-block-attributes-html] can contain HTML rich-text formatting, but that may not always be apparent. For example, this is an image with a basic plain-text caption:

![Image with plain-text caption][media-example-caption-plain]

The image is saved in WordPress with this markup:

```html
<!-- wp:image {"id":17,"sizeSlug":"large","linkDestination":"media"} -->
<figure class="wp-block-image size-large">
  <a href="https://my.site/wp-content/wpvip.jpg">
    <img src="https://my.site/wp-content/wpvip.jpg" alt="" class="wp-image-17"/>
  </a>

  <figcaption class="wp-element-caption">This is a center-aligned image with a caption</figcaption>
</figure>
<!-- /wp:image -->
```

The Block Data API uses the `caption` property definition from [`core/image`'s `block.json` file][gutenberg-code-image-caption]:

```js
"attributes": {
  "caption": {
    "type": "string",
    "source": "html",
    "selector": "figcaption",
    /* ... */
  },
}
```

The sourced caption is returned in the Block Data API:

```js
{
  "name": "core/image",
  "attributes": {
    /* ... */
    "caption": "This is a center-aligned image with a caption",
  }
}
```

Because the `caption` property in this example is , it seems possible to print the caption to the page safely (e.g. without using `innerHTML` or React's `dangerouslySetInnerHTML`). However, this is not the case and may result in incorrect rendering.

Attributes with the `html` source like the image block caption attribute above can contain plain-text as well as markup.

![Image with rich-text caption][media-example-caption-rich-text]

Retrieving the `caption` through the Block Data API yields this result:

```js
{
  "name": "core/image",
  "attributes": {
    /* ... */
    "caption": "This is a caption with <strong>bold text</strong> <a href=\"https://wpvip.com/\">and a link</a>.",
  }
}
```

`caption` now contains inline HTML. In order to view rich-text formatting in a decoupled component, direct HTML usage with `innerHTML` or `dangerouslySetInnerHTML` is necessary. <!--You could also use the [`vip_block_data_api__sourced_block_result`](#vip_block_data_api__sourced_block_result) filter to remove HTML from attributes.--> Formatting would be removed as well, but the resulting data may be more flexible.

<!-- In the future we are considering providing a rich-text data format so that no direct HTML is required to render blocks correctly. This would improve the flexibility of the Block Data API in non-browser locations such as in native mobile applications. For now, however, some direct HTML is still required to render blocks with rich formatting. -->

### Deprecated blocks

When core or custom editor blocks are updated to a new version, block attributes can change. This can result in the Block Data API returning a different block structure for the same block type depending on when the post containing a block was authored.

For example, the `core/list` block [was updated in 2022][gutenberg-pr-core-list-innerblocks] from storing list items in the `values` attribute to use `innerBlocks` instead. Before this change, a list with two items was structured like this:

```html
<!-- wp:list -->
<ul>
  <li>List item 1</li>
  <li>List item 2</li>
</ul>
<!-- /wp:list -->
```

The resulting attributes for a `core/list` block pulled from the Block Data API would be structured like this:

```json
{
  "name": "core/list",
  "attributes": {
    "ordered": false,
    "values": "<li>List item 1</li><li>List item 2</li>"
  }
}
```

List items are stored as HTML in the `values` attribute, which is not an ideal structure for mapping to custom components. After the [`core/list` block was updated][gutenberg-pr-core-list-innerblocks] in WordPress, the same two-item list block is represented this way in HTML:

```html
<!-- wp:list -->
<ul>
  <!-- wp:list-item -->
  <li>List item 1</li>
  <!-- /wp:list-item -->

  <!-- wp:list-item -->
  <li>List item 2</li>
  <!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
```

The resulting `core/list` item from the Block Data API parses the list items as `core/list-item` children in `innerBlocks`:

```json
{
  "name": "core/list",
  "attributes": {
    "ordered": false,
    "values": ""
  },
  "innerBlocks": [
    {
      "name": "core/list-item",
      "attributes": {
        "content": "List item 1"
      }
    },
    {
      "name": "core/list-item",
      "attributes": {
        "content": "List item 2"
      }
    }
  ]
}
```

Deprecated blocks can be a tricky problem when using the Block Data API to render multiple versions of the same block. A `core/list` block from a post in 2021 has a different data shape than a `core/list` block created in 2023. Consumers of the API need to be aware of legacy block structures in order to implement custom frontend components. This issue applies to custom blocks as well; if a block has legacy markup saved in the database, this can result in legacy block representation in the Block Data API.

<!--We are considering ways to mitigate this problem for consumers of the API, such as [implementing server-side block deprecation rules][wordpress-block-deprecation] or providing type structures to represent legacy block data shapes. For now, -->Please ensure that Block Data API consumers test against older content to ensure that legacy block versions used in content are covered by code.

## Acknowledgements

The logic to retrieve the (Gutenberg) block data is based on <a href="https://github.com/Automattic/vip-block-data-api/" target="_blank">`Automattic/vip-block-data-api`</a>.

Many thanks to the folks who contributed to that project. ❤️

<!-- Links -->
[gutenberg-code-image-caption]: https://github.com/WordPress/gutenberg/blob/3d2a6d7eaa4509c4d89bde674e9b73743868db2c/packages/block-library/src/image/block.json#L30-L35
[gutenberg-pr-core-list-innerblocks]: https://href.li/?https://github.com/WordPress/gutenberg/pull/39487
[media-example-caption-plain]: https://raw.githubusercontent.com/Automattic/vip-block-data-api/media/example-caption-plain.png
[media-example-caption-rich-text]: https://raw.githubusercontent.com/Automattic/vip-block-data-api/media/example-caption-rich-text.png
[media-example-heading-paragraph]: https://raw.githubusercontent.com/Automattic/vip-block-data-api/media/example-header-paragraph.png
[media-example-media-text]: https://raw.githubusercontent.com/Automattic/vip-block-data-api/media/example-media-text.png
[media-example-pullquote]: https://raw.githubusercontent.com/Automattic/vip-block-data-api/media/example-pullquote.png
[media-plugin-activate]: https://raw.githubusercontent.com/Automattic/vip-block-data-api/media/plugin-activate.png
[media-preact-media-text]: https://raw.githubusercontent.com/Automattic/vip-block-data-api/media/preact-media-text.png
[preact]: https://preactjs.com
[repo-core-image-block-addition]: src/parser/block-additions/core-image.php
[repo-issue-create]: https://github.com/Automattic/vip-block-data-api/issues/new/choose
[repo-releases]: https://github.com/Automattic/vip-block-data-api/releases
[vip-go-mu-plugins]: https://github.com/Automattic/vip-go-mu-plugins/
[wordpress-application-passwords]: https://make.wordpress.org/core/2020/11/05/application-passwords-integration-guide/
[wordpress-block-attributes-html]: https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/#html-source
[wordpress-block-deprecation]: https://developer.wordpress.org/block-editor/reference-guides/block-api/block-deprecation/
[wordpress-block-json-recommendation]: https://make.wordpress.org/core/2021/06/23/block-api-enhancements-in-wordpress-5-8/
[wordpress-block-metadata-php-registration]: https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#php-server-side
[wordpress-plugin-classic-editor]: https://wordpress.org/plugins/classic-editor/
[wordpress-register-block-type-js]: https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/#registerblocktype
[wordpress-register-block-type-php]: https://developer.wordpress.org/reference/functions/register_block_type/
[wordpress-release-5-0]: https://wordpress.org/documentation/wordpress-version/version-5-0/
[wordpress-rest-api-posts]: https://developer.wordpress.org/rest-api/reference/posts/
[wp-env]: https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/
[wpvip-page-cache]: https://docs.wpvip.com/technical-references/caching/page-cache/
[wpvip-plugin-activate]: https://docs.wpvip.com/how-tos/activate-plugins-through-code/
[wpvip-plugin-submodules]: https://docs.wpvip.com/technical-references/plugins/installing-plugins-best-practices/#h-submodules
[wpvip-plugin-subtrees]: https://docs.wpvip.com/technical-references/plugins/installing-plugins-best-practices/#h-subtrees