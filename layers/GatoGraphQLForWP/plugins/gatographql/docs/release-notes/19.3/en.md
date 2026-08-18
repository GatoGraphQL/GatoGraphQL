# Release Notes: 19.3

## Added

### FluentCart extension

The plugin docs now cover the new **FluentCart integration** ([#3379](https://github.com/GatoGraphQL/GatoGraphQL/pull/3379)).

The FluentCart extension adds fields to the GraphQL schema to fetch data from a [FluentCart](https://wordpress.org/plugins/fluent-cart/) store: products and their variations, orders and payments, customers, subscriptions, coupons, carts, and the shipping and tax configuration.

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

The extension also provides mutations, to create, update and delete store data:

```graphql
mutation CreateProduct {
  fluentCartCreateProduct(input: {
    title: "Gato Cap"
    slug: "gato-cap"
    status: "publish"
    fulfillmentType: "physical"
  }) {
    status
    errors {
      __typename
      message
    }
    product {
      id
      title
    }
  }
}
```

Subscriptions can be paused, resumed, cancelled and edited, for those FluentCart bills through its own invoice engine; one backed by a payment gateway is refused rather than silently diverging from the gateway.

When **FluentCart Pro** is installed, its licensing and inventory data joins the schema too: licences, the sites they are activated on, and the stock adjustment log.

```graphql
{
  fluentCartLicenses(filter: { status: "active" }) {
    licenseKey
    remainingActivations
    isExpired
    product { title }
    customer { fullName }
    activations {
      status
      site { siteURL }
    }
  }
}
```
