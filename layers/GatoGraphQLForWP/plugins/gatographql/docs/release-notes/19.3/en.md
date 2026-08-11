# Release Notes: 19.3

## Added

### FluentCart extension

The new **FluentCart** extension adds fields to the GraphQL schema to fetch data from a [FluentCart](https://wordpress.org/plugins/fluent-cart/) store: products and their variations, orders and payments, customers, subscriptions, coupons, carts, and the shipping and tax configuration.

Every monetary field is available both as a decimal and as the exact integer FluentCart stores, since it keeps amounts in the currency's minor units:

```graphql
{
  fluentCartOrders {
    currency
    total          # 24.99
    totalInCents   # 2499
  }
}
```
