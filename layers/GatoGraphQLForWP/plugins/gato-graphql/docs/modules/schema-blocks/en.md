# Blocks

Integration of Gutenberg blocks into the GraphQL schema.

## Description

This module adds the following fields to all the `CustomPost` types (such as `Post` and `Page`):

- `blocks`
- `blockDataItems`
- `blockFlattenedData`

This module is disabled if the [Classic Editor](https://wordpress.org/plugins/classic-editor/) plugin is active.

## `blocks`

Field `CustomPost.blocks: [BlockUnion!]` retrieves the list of all the blocks contained in the custom post:

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
          }
        }
      }
    }
  }
}
```

The result is a `BlockUnion` type, which contains all the possible Block types that have been mapped to the schema, all of them implementing the `Block` interface. Currently, there is only one Block type mapped: `GenericBlock`:

```graphql
interface Block {
  name: String!
  attributes: JSONObject
  innerBlocks: [BlockUnion!]
  contentSource: String!
}

type GenericBlock implements Block {
  name: String!
  attributes: JSONObject
  innerBlocks: [BlockUnion!]
  contentSource: String!
}

union BlockUnion = GenericBlock
```

`GenericBlock` contains field `attributes: JSONObject`, which returns a JSON object with all the attributes in the block. As such, this block is sufficient to represent any Block type.

Please notice that field `Block.innerBlocks` also retrieves `[BlockUnion!]`, hence we can query it to navigate the hierarchy of blocks containing inner blocks, and fetching the data for all of them, for as many levels down as we have in our content:

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
```

### Directly retrieving `GeneralBlock` (instead of `BlockUnion`)

As currently only the `GeneralBlock` type representing blocks, it makes sense to have `CustomPost.blocks` (and also `Block.innerBlocks`) retrieve this type directly, instead of the `BlockUnion` union type.

We can do this in the Settings page under the Blocks tab, by ticking on option `"Use single type instead of union type?"`:

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

### Mapping block-specific types

The `JSONObject` type is not strictly typed: its properties can have any type and cardinality (`String`, `Int`, `[Boolean!]`, etc), so we need to know this information for every block and deal with each case in the client.

If we need strict typing, we must extend the GraphQL schema via PHP code, adding block-specific types that map a block's specific attributes as fields, and make them part of the `BlockUnion`.

For instance, we can add type `CoreParagraphBlock` that maps the `core/paragraph` block, with field `content` of type `String`.

### Filtering blocks

Field `CustomPost.blocks` contains argument `filter` with 2 properties: `include` and `exclude`. We can use these to filter what blocks are retrieved, by the block name:

```graphql
{
  post(by: { id: 1 }) {
    id
    blocks(
      filterBy: {
        include: [
          "core/heading",
          "core/columns",
          "core/column"
        ]
      }
    ) {
      name
      attributes
    }
  }
}
```

## `blockDataItems`

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
  contentSource
  innerBlocks {
    name
    attributes
    contentSource
    innerBlocks {
      name
      attributes
      contentSource
      innerBlocks {
        name
        attributes
        contentSource
        innerBlocks {
          name
          attributes
          contentSource
          innerBlocks {
            name
            attributes
            contentSource
            innerBlocks {
              name
              attributes
              contentSource
              innerBlocks {
                name
                attributes
                contentSource
              }
            }
          }
        }
      }
    }
  }
}
```

It is in order to avoid this that there is field `CustomPost.blockDataItems`. This field, instead of returning `[BlockUnion]`, returns `[JSONObject!]`:

```graphql
type CustomPost {
  blockDataItems: [JSONObject!]
}
```

Every JSON object contains the data for the block (under entries `name` and `attributes`) and for its inner blocks (under entry `innerBlocks`), recursively.

For instance, the following query:

```graphql
{
  post(by: { id: 1 }) {
    blockDataItems
  }
}
```

...might produce:

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

## `blockFlattenedData`



## Acknowledgments

The logic to retrieve the (Gutenberg) block data is a fork of [`Automattic/vip-block-data-api`](https://github.com/Automattic/vip-block-data-api/).

Many thanks to the folks who contributed to that project. ❤️
