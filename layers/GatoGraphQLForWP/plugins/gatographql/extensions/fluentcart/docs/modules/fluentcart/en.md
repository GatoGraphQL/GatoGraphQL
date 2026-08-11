# FluentCart

Integration with <a href="https://wordpress.org/plugins/fluent-cart/" target="_blank" rel="nofollow">FluentCart</a>.

The GraphQL schema is provided with fields to fetch FluentCart data: products and their variations, orders and payments, customers, subscriptions, coupons, carts, and the store's shipping and tax configuration.

## Fetching data

```graphql
{
  fluentCartProducts(
    filter: { status: [ "publish" ] }
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

## Access control

Product, category, brand and attribute data is public, matching what the store shows its visitors. Orders, customers, subscriptions, carts, coupons, the activity log and the store configuration require the corresponding FluentCart permission, which WordPress administrators hold implicitly.
