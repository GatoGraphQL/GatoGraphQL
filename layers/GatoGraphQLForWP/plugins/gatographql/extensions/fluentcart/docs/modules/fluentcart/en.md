# FluentCart

Integration with <a href="https://wordpress.org/plugins/fluent-cart/" target="_blank" rel="nofollow">FluentCart</a>.

The GraphQL schema is provided with fields to fetch FluentCart data &mdash; products and their variations, orders and payments, customers, subscriptions, coupons, carts, and the store's shipping and tax configuration &mdash; and with mutations to manage the store's data.

## Fetching data

```graphql
{
  fluentCartProducts(
    filter: { status: [ PUBLISH ] }
    sort: { by: TITLE, order: ASC }
  ) {
    id
    title
    slug
    url
    fulfillmentType
    variationType
    inStock
    hasSubscription
    minPrice
    maxPrice
    variations {
      id
      title
      sku
      price
      comparePrice
      stockStatus
      availableStock
    }
    downloads {
      title
      fileName
    }
  }
}
```

## Orders, customers and subscriptions

```graphql
{
  fluentCartOrders(sort: { by: DATE, order: DESC }) {
    id
    status
    paymentStatus
    total
    items {
      title
      quantity
      lineTotal
    }
    charges {
      total
      paymentMethod
    }
    refunds {
      total
      date
    }
  }

  fluentCartCustomers {
    email
    fullName
    orderCount
    lifetimeValue
  }

  fluentCartSubscriptions {
    itemName
    status
    billingInterval
    recurringTotal
    nextBillingDate
  }
}
```

## Amounts in two forms

FluentCart stores every monetary amount in the currency's minor units &mdash; `2499` for $24.99 &mdash; and some currencies have no minor unit at all. Each monetary field is therefore available twice: as the decimal figure to display, and as the exact integer to compute with.

```graphql
{
  fluentCartOrders {
    currency
    total          # 24.99
    totalInCents   # 2499
  }
}
```

## Mutations

The GraphQL schema is provided with `create`, `update` and `delete` mutations for the FluentCart entities, so you can manage your store's data through the GraphQL API.

These mutations are provided by the `FluentCart Mutations` module, which depends on both the `FluentCart` module (to fetch the entities back) and the `User State Mutations` module (as every mutation requires the user to be logged-in). Disabling either of those also disables the mutations.

Mutations write through FluentCart's own models and services, so a write the store itself would refuse is refused here too, rather than pushed through and left inconsistent with the payment gateway.

Every mutation also has a bulk counterpart, taking a list of the same inputs and answering with one payload per item. And on an endpoint with nested mutations enabled, every entity carries the mutations that act on it as fields of its own &mdash; `update` and `delete`, and whatever else that entity does: a subscription's `pause`, an order's `refund`, a license's `activateSite`.

The following entities are supported:

| Entity | Mutations |
| --- | --- |
| Products | `fluentCartCreateProduct`, `fluentCartUpdateProduct`, `fluentCartDeleteProduct` |
| Product variations | `fluentCartCreateProductVariation`, `fluentCartUpdateProductVariation`, `fluentCartDeleteProductVariation` |
| Product downloads | `fluentCartCreateProductDownload`, `fluentCartUpdateProductDownload`, `fluentCartDeleteProductDownload` |
| Product categories | `fluentCartCreateProductCategory`, `fluentCartUpdateProductCategory`, `fluentCartDeleteProductCategory` |
| Product brands | `fluentCartCreateProductBrand`, `fluentCartUpdateProductBrand`, `fluentCartDeleteProductBrand` |
| Attribute groups | `fluentCartCreateAttributeGroup`, `fluentCartUpdateAttributeGroup`, `fluentCartDeleteAttributeGroup` |
| Attribute terms | `fluentCartCreateAttributeTerm`, `fluentCartUpdateAttributeTerm`, `fluentCartDeleteAttributeTerm` |
| Orders | `fluentCartCreateOrder`, `fluentCartUpdateOrder`, `fluentCartDeleteOrder` |
| Order line items | `fluentCartCreateOrderItem`, `fluentCartUpdateOrderItem`, `fluentCartDeleteOrderItem` |
| Order addresses | `fluentCartCreateOrderAddress`, `fluentCartUpdateOrderAddress`, `fluentCartDeleteOrderAddress` |
| Customers | `fluentCartCreateCustomer`, `fluentCartUpdateCustomer`, `fluentCartDeleteCustomer` |
| Customer addresses | `fluentCartCreateCustomerAddress`, `fluentCartUpdateCustomerAddress`, `fluentCartDeleteCustomerAddress` |
| Coupons | `fluentCartCreateCoupon`, `fluentCartUpdateCoupon`, `fluentCartDeleteCoupon` |
| Labels | `fluentCartCreateLabel`, `fluentCartUpdateLabel`, `fluentCartDeleteLabel` |
| Shipping zones | `fluentCartCreateShippingZone`, `fluentCartUpdateShippingZone`, `fluentCartDeleteShippingZone` |
| Shipping methods | `fluentCartCreateShippingMethod`, `fluentCartUpdateShippingMethod`, `fluentCartDeleteShippingMethod` |
| Shipping classes | `fluentCartCreateShippingClass`, `fluentCartUpdateShippingClass`, `fluentCartDeleteShippingClass` |
| Tax classes | `fluentCartCreateTaxClass`, `fluentCartUpdateTaxClass`, `fluentCartDeleteTaxClass` |
| Tax rates | `fluentCartCreateTaxRate`, `fluentCartUpdateTaxRate`, `fluentCartDeleteTaxRate` |
| Subscriptions | `fluentCartUpdateSubscription`, `fluentCartPauseSubscription`, `fluentCartResumeSubscription`, `fluentCartCancelSubscription` |
| Activity log | `fluentCartMarkActivityAsRead`, `fluentCartDeleteActivity` |

For instance, create a product:

```graphql
mutation {
  fluentCartCreateProduct(input: {
    title: "Gato Cap"
    slug: "gato-cap"
    status: PUBLISH
    fulfillmentType: PHYSICAL
    variationType: SIMPLE
    categoryIDs: [3]
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    product {
      id
      title
      slug
    }
  }
}
```

Update a product (only the provided fields are modified):

```graphql
mutation {
  fluentCartUpdateProduct(input: {
    id: 12
    title: "Gato Cap (Limited)"
    status: DRAFT
    manageStock: true
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    product {
      id
      title
      status
    }
  }
}
```

An order is assembled in steps: `fluentCartCreateOrder` creates the order row, and its line items and addresses are their own mutations.

```graphql
mutation {
  fluentCartCreateOrderItem(input: {
    orderID: 1
    productID: 12
    variationID: 34
    title: "Gato Cap (S)"
    quantity: 2
    unitPriceInCents: 1000
    lineTotalInCents: 2000
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    orderItem {
      id
      lineTotal
    }
  }
}
```

Disable a coupon, reaching it by its code through the nested form:

```graphql
mutation {
  fluentCartCoupon(by: { code: "SUMMER" }) {
    update(input: { status: DISABLED }) {
      status
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
      coupon {
        code
        status
      }
    }
  }
}
```

A failure is returned as payload data rather than as a top-level GraphQL error, with the `__typename` distinguishing whether the caller was not logged in, lacked the permission, named an entity that does not exist, supplied a value that is already taken, or asked for something FluentCart refuses.

Statuses and types are enums throughout, and orders, customers, subscriptions, products and variations accept a `meta` input.

## Operations beyond create, update and delete

Some things a store does to an order are not an edit to a column. Each is its own mutation, and each defers to FluentCart's own guard.

| Mutation | What it does |
| --- | --- |
| `fluentCartRefundOrder` | Refunds an amount against one of the order's transactions |
| `fluentCartMarkOrderAsPaid` | Records payment for the amount still due |
| `fluentCartChangeOrderCustomer` | Moves the order, its addresses and its subscriptions to another customer |
| `fluentCartSyncOrderStatuses` | Re-derives the order's statuses from its latest transaction |
| `fluentCartReactivateSubscription` | Brings a cancelled or expired subscription back |
| `fluentCartChargeSubscriptionNow` | Charges the open renewal order immediately |
| `fluentCartCreateSubscriptionRenewal` | Creates the next renewal order ahead of its date |
| `fluentCartSkipSubscriptionRenewal` | Advances the billing date by one period without charging |

Order transactions themselves are not creatable: a charge belongs to the payment gateway, and writing one directly would record money movement that never happened. Carts are written by the storefront, and are exposed read-only.

## FluentCart Pro

When FluentCart Pro is installed, its own entities join the schema: licenses with their activations and the sites they are activated on, the stock adjustment log, and the checkout order bumps.

Licenses are issued by a purchase rather than created, and are managed with `fluentCartRegenerateLicenseKey`, `fluentCartExtendLicenseValidity`, `fluentCartUpdateLicenseStatus`, `fluentCartUpdateLicenseLimit`, `fluentCartActivateLicenseSite` and `fluentCartDeactivateLicenseSite`. Stock is set with `fluentCartAdjustProductVariationStock`, which records why, so the adjustment log stays a complete history. Order bumps have full create, update and delete.

Licensing and order bumps are each a FluentCart module the store can switch off, which is what creates their tables: with the module off they read as empty and refuse to write, rather than erroring on a table that is not there.

## Store configuration

The store's own configuration is queried from the root, covering its identity, address, and the formatting a client needs to render a price the way the store does:

```graphql
{
  fluentCartStoreName
  fluentCartStoreLogo
  fluentCartStoreCountry
  fluentCartStoreCurrency
  fluentCartStoreCurrencyPosition
  fluentCartStoreDecimalSeparator
  fluentCartStoreWeightUnit
  fluentCartStoreDimensionUnit
  fluentCartStoreOrderMode
}
```

`fluentCartStoreOrderMode` is `TEST` on a store that has not gone live, and an unset setting reads as `null` rather than as an empty string. These settings are read-only; the parts of the configuration that are written through the schema are the shipping zones, methods and classes, and the tax classes and rates, each with the mutations listed above.

## Access control

Product, category, brand and attribute data is public, matching what the store shows its visitors. Everything that exposes commercial or personal data &mdash; orders, customers, subscriptions, carts, coupons, the activity log and the store configuration &mdash; requires the corresponding FluentCart permission, which WordPress administrators hold implicitly. The downloadable files are the exception to the public catalogue: a product is public, but the files it delivers are held at the same permission FluentCart holds its own routes to them.

A shopper holds no such permission and still owns their orders, so the `fluentCartMy…` fields &mdash; `fluentCartMyOrders`, `fluentCartMySubscriptions`, `fluentCartMyDownloads`, `fluentCartMyPurchasedProducts`, `fluentCartMyLicenses` &mdash; are scoped to whoever is asking rather than gated, and take the same `filter`, `sort` and `pagination` arguments as the store-wide field each mirrors.
