# FluentCart

Integration with <a href="https://wordpress.org/plugins/fluent-cart/" target="_blank" rel="nofollow">FluentCart</a>.

<!-- [Watch “How to use the FluentCart extension” on YouTube](https://www.youtube.com/watch?v=@todo) -->

---

Fetch product data from your FluentCart store.

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
    categories {
      id
      name
      slug
    }
    brands {
      id
      name
      slug
    }
  }
}
```

---

Beyond products, the extension reaches the store's orders (with their line items, addresses, transactions, refunds and applied coupons), customers, subscriptions, carts, coupons, labels, the activity log, and the store's own shipping and tax configuration. With FluentCart Pro installed it also reaches licenses, their activations and the sites they run on, the stock adjustment log, and the checkout order bumps.

Every monetary amount comes in both forms FluentCart holds it in: the decimal figure to display, and the exact integer in the currency's minor units to compute with.

It also provides `create`, `update` and `delete` mutations for those entities, plus the operations that are not an edit to a column &mdash; refunding an order, marking it paid, pausing and resuming a subscription &mdash; writing through FluentCart's own models and services, so a write the store itself would refuse is refused here too.
