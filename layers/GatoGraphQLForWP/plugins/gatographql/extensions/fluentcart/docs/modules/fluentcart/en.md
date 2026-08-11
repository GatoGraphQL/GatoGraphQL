# FluentCart

Integration with <a href="https://wordpress.org/plugins/fluent-cart/" target="_blank" rel="nofollow">FluentCart</a>.

The GraphQL schema is provided with fields to fetch FluentCart data: products and their variations, orders and payments, customers, subscriptions, coupons, carts, and the store's shipping and tax configuration.

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

Every entity that can be written is available as a mutation, in three forms: the root mutation, its bulk counterpart taking a list of the same inputs, and &mdash; on an endpoint with nested mutations enabled &mdash; a field on the entity's own type.

| Entity | Mutations |
| --- | --- |
| Products, variations, categories, brands | create, update, delete |
| Attribute groups and terms | create, update, delete |
| Orders, line items, addresses | create, update, delete |
| Customers and their addresses | create, update, delete |
| Coupons, labels | create, update, delete |
| Shipping zones, methods, classes | create, update, delete |
| Tax classes and rates | create, update, delete |
| Subscriptions | update, pause, resume, cancel |

```graphql
mutation {
  fluentCartCreateCoupon(input: {
    code: "SUMMER"
    title: "Summer sale"
    type: PERCENTAGE
    amount: 15
    status: ACTIVE
    conditions: { min_purchase_amount: 5000 }
  }) {
    status
    errors {
      __typename
      message
    }
    coupon {
      id
      code
    }
  }
}
```

A failure is returned as payload data rather than as a top-level GraphQL error, with the `__typename` distinguishing whether the caller was not logged in, lacked the permission, named an entity that does not exist, supplied a value that is already taken, or asked for something FluentCart refuses.

Statuses and types are enums throughout, and orders, customers, subscriptions, products and variations accept a `meta` input.

## Access control

Product, category, brand and attribute data is public, matching what the store shows its visitors. Orders, customers, subscriptions, carts, coupons, the activity log and the store configuration require the corresponding FluentCart permission, which WordPress administrators hold implicitly.
