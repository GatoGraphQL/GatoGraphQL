# Release Notes: 19.3

## Improvements

### Fewer options loaded on every request

WordPress loads the options marked as "autoload" on every single request, front-end ones included. Three of the plugin's own were marked that way and had no business being: the cached list of models retrieved from the AI services, the log entry counts, and the internal flags kept between one request and the next ([#3387](https://github.com/GatoGraphQL/GatoGraphQL/pull/3387)).

They are read in the wp-admin, in the WP-CLI commands and while a translation is running — the log counts also on any request that logs — and the AI model data in particular can run to hundreds of kilobytes on a site with several AI services configured. They are now read where they are used instead of on every request.

The same goes for the record of which version of the plugin and of each extension is installed, which is only consulted in the wp-admin to notice that one of them has just been activated or updated ([#3388](https://github.com/GatoGraphQL/GatoGraphQL/pull/3388)).

Nothing needs doing on an existing site: the options are migrated when the plugin next updates.

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
