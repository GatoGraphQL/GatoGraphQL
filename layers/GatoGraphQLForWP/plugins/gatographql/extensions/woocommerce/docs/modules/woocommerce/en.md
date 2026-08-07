# WooCommerce

Integration with <a href="https://wordpress.org/plugins/woocommerce/" target="_blank" rel="nofollow">WooCommerce</a>.

The GraphQL schema is provided with fields to fetch WooCommerce data, and with mutations to manage the store's data.

## Fetching data

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

## Mutations

The GraphQL schema is provided with `create`, `update` and `delete` mutations for the WooCommerce entities, so you can manage your store's data through the GraphQL API.

These mutations are provided by the `WooCommerce Mutations` module, which depends on both the `WooCommerce` module (to fetch the entities back) and the `User State Mutations` module (as every mutation requires the user to be logged-in). Disabling either of those also disables the mutations.

Mutations write through WooCommerce's own data layer (`WC_Product`, `WC_Order`, `WC_Customer`, and so on), so they are compatible with High-Performance Order Storage (HPOS), and with the comment API for reviews.

The following entities are supported:

| Entity | Mutations |
| --- | --- |
| Products | `woocommerceCreateProduct`, `woocommerceUpdateProduct`, `woocommerceDeleteProduct` |
| Product variations | `woocommerceCreateProductVariation`, `woocommerceUpdateProductVariation`, `woocommerceDeleteProductVariation` |
| Product categories | `woocommerceCreateProductCategory`, `woocommerceUpdateProductCategory`, `woocommerceDeleteProductCategory` |
| Product tags | `woocommerceCreateProductTag`, `woocommerceUpdateProductTag`, `woocommerceDeleteProductTag` |
| Product brands | `woocommerceCreateProductBrand`, `woocommerceUpdateProductBrand`, `woocommerceDeleteProductBrand` |
| Product attribute taxonomies | `woocommerceCreateAttributeTaxonomy`, `woocommerceUpdateAttributeTaxonomy`, `woocommerceDeleteAttributeTaxonomy` |
| Product attributes (the taxonomy terms) | `woocommerceCreateAttribute`, `woocommerceUpdateAttribute`, `woocommerceDeleteAttribute` |
| Shipping classes | `woocommerceCreateShippingClass`, `woocommerceUpdateShippingClass`, `woocommerceDeleteShippingClass` |
| Coupons | `woocommerceCreateCoupon`, `woocommerceUpdateCoupon`, `woocommerceDeleteCoupon` |
| Orders | `woocommerceCreateOrder`, `woocommerceUpdateOrder`, `woocommerceDeleteOrder`, `woocommerceUpdateOrderStatus` |
| Order refunds | `woocommerceCreateOrderRefund`, `woocommerceDeleteOrderRefund` |
| Order notes | `woocommerceCreateOrderNote`, `woocommerceDeleteOrderNote` |
| Customers | `woocommerceCreateCustomer`, `woocommerceUpdateCustomer`, `woocommerceDeleteCustomer` |
| Product reviews | `woocommerceCreateReview`, `woocommerceUpdateReview`, `woocommerceDeleteReview` |
| Store settings | `woocommerceUpdateStoreSettings` |

For instance, create a product:

```graphql
mutation {
  woocommerceCreateProduct(input: {
    type: simple
    name: "Gato Product"
    sku: "GATO-PRODUCT"
    regularPrice: "100.00"
    status: publish
    categoryIDs: [84, 85]
    tagIDs: [93]
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
      name
      sku
      regularPrice
    }
  }
}
```

Update a product (only the provided fields are modified):

```graphql
mutation {
  woocommerceUpdateProduct(input: {
    id: 1730
    regularPrice: "120.00"
    salePrice: "99.00"
    stockQuantity: 50
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
      regularPrice
      salePrice
      stockQuantity
    }
  }
}
```

Create an order with a line item and a billing address:

```graphql
mutation {
  woocommerceCreateOrder(input: {
    status: "pending"
    customerID: 0
    currency: "USD"
    paymentMethod: "cod"
    billingAddress: {
      firstName: "Test"
      lastName: "Buyer"
      address1: "123 Test St"
      city: "Barcelona"
      postcode: "08001"
      country: "ES"
      email: "buyer@example.com"
    }
    lineItems: [
      {
        productID: 1730
        quantity: 1
      }
    ]
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    order {
      id
      status
      total
      items {
        name
        quantity
      }
    }
  }
}
```

Delete a coupon, referencing it by code, and permanently (instead of sending it to the trash):

```graphql
mutation {
  woocommerceDeleteCoupon(input: {
    code: "gato-summer"
    force: true
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
}
```

## Store configuration

Field `woocommerceStoreSettings` reads the store's configuration, and mutation `woocommerceUpdateStoreSettings` writes it back. The mutation writes only the settings it is given, leaving the rest of the configuration alone.

Both require the capability to manage WooCommerce, on the reasoning that this is configuration rather than shop data: whoever may change the store's settings may read them.

```graphql
{
  woocommerceStoreSettings {
    city
    countryCode
    stateCode
    currencyCode
    currencySymbol
    numberOfDecimals
    weightUnit
    taxesEnabled
    guestCheckoutEnabled
    shopPageID
  }
}
```

```graphql
mutation {
  woocommerceUpdateStoreSettings(input: {
    city: "Berlin"
    countryCode: "DE"
    stateCode: ""
    currencyCode: "EUR"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    storeSettings {
      city
      countryCode
      currencyCode
    }
  }
}
```

## Webhooks

Fields `woocommerceWebhook`, `woocommerceWebhooks` and `woocommerceWebhooksCount` read the store's webhooks. They require the capability to manage WooCommerce.

```graphql
{
  woocommerceWebhooksCount
  woocommerceWebhooks {
    id
    name
    status
    topic
    deliveryURL
    failureCount
    pendingDelivery
  }
}
```

## Customer downloads

Fields `downloads` and `downloadsCount` on a customer read the files they may download, granted by the orders they have placed. A download is the pairing of a downloadable file with the order which granted access to it, so the same file appears once per order it was bought in.

They are accessible to the customer themselves, and to whoever may manage WooCommerce.

```graphql
{
  woocommerceCustomer(by: { id: 9 }) {
    downloadsCount
    downloads {
      name
      url
      productName
      orderID
      downloadsRemaining
      accessExpires
    }
  }
}
```
