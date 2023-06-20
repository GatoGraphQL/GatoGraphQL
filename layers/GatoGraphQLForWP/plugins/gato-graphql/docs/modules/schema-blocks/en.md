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
  posts {
    blocks {
      # ...
    }
  }
}
```

The result is a `BlockUnion` type, which contains all the possible Block types that have been mapped to the schema, all of them implementing the `Block` interface.

Currently, there is only one Block type mapped: `GenericBlock`:

```graphql
interface Block {
  name: String!
  attributes: JSONObject
  innerBlocks: [BlockUnion!]
}

type GenericBlock implements Block {
  name: String!
  attributes: JSONObject
  innerBlocks: [BlockUnion!]
}

union BlockUnion = GenericBlock
```

`GenericBlock` offers field `attributes: JSONObject`, which returns a JSON object with all the attributes in the block. As such, this block is sufficient to represent any Block type.

Only GeneralBlock only, but can add CoreParagraphBlock, CoreImageBlock, etc with specific (typed) fields

### Retrieving `BlockUnion` or `GeneralBlock`

### Filtering blocks

## `blockData`



## `blockFlattenedData`



## Acknowledgments

The logic to retrieve the (Gutenberg) block data is a fork of [`Automattic/vip-block-data-api`](https://github.com/Automattic/vip-block-data-api/).

Many thanks to the folks who contributed to that project. ❤️
