# WooCommerce

Integration with <a href="https://wordpress.org/plugins/woocommerce/" target="_blank" rel="nofollow">WooCommerce</a>.

The GraphQL schema is provided with fields to fetch WooCommerce data.

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
