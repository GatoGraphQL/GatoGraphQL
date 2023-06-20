# Blocks

Integration of Gutenberg blocks into the GraphQL schema.

## Description

This module adds the following fields to all the `CustomPost` types (such as `Post` and `Page`):

- `blocks`
- `blockData`
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

## `blockData`

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

## `blockFlattenedData`



## Acknowledgments

The logic to retrieve the (Gutenberg) block data is a fork of [`Automattic/vip-block-data-api`](https://github.com/Automattic/vip-block-data-api/).

Many thanks to the folks who contributed to that project. ❤️
