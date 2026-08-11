# WooCommerce

Integration with <a href="https://wordpress.org/plugins/woocommerce/" target="_blank" rel="nofollow">WooCommerce</a>.

<!-- [Watch “How to use the WooCommerce extension” on YouTube](https://www.youtube.com/watch?v=@todo) -->

---

Fetch product data from your WooCommerce store.

```graphql
{
  woocommerceProducts {
    __typename
    id
    name
    slug
    url
    urlPath
    sku
    ...on WooCommercePriceableProductOrProductVariation {
      price
      priceFormatted
      regularPrice
      regularPriceFormatted
      salePrice
      salePriceFormatted
      onSale
    }
    status
    type
    featured
    totalSales
    averageRating
    ratingCount
    image {
      id
      src
      altText
      title
      caption
    }
    categories {
      id
      name
      slug
    }
    tags {
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

Beyond products, the extension reaches the store's orders (with their notes, line items, and tax, shipping, fee and coupon lines), refunds, coupons, customers (with the files they may download), reviews, shipping zones, tax rates, payment gateways, webhooks, and the store's own configuration.

It also provides `create`, `update` and `delete` mutations for those entities, writing through WooCommerce's own data layer, so they are compatible with High-Performance Order Storage (HPOS).
