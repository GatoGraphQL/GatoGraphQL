# Release Notes: 19.1

## Improvements

- Updated the WooCommerce docs with the mutations to manage the store's data (products, orders, customers, coupons, and more).

## Fixes

- Made the ordering of taxonomy terms (such as categories and tags) deterministic by adding a stable secondary sort by term ID, so that terms sharing the same primary sort value (such as a duplicate name) are always returned in a consistent order when sorting and paginating.
