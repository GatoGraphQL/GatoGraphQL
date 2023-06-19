# Blocks

Integration of Gutenberg blocks into the GraphQL schema.

## Description

This module adds the following fields to the `Root` type:

- `blocks`
- `blockData`
- `blockFlattenedData`

This module is disabled if the [Classic Editor](https://wordpress.org/plugins/classic-editor/) plugin is active.

## `blocks`

Only GeneralBlock only, but can add CoreParagraphBlock, CoreImageBlock, etc
		with specific (typed) fields

## `blockData`



## `blockFlattenedData`



## Acknowledgments

The logic to retrieve the (Gutenberg) block data is a fork of [`Automattic/vip-block-data-api`](https://github.com/Automattic/vip-block-data-api/).

Many thanks to the folks who contributed to that project. ❤️
