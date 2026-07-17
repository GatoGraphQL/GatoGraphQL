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
| Customers | `woocommerceCreateCustomer`, `woocommerceUpdateCustomer`, `woocommerceDeleteCustomer` |
| Product reviews | `woocommerceCreateReview`, `woocommerceUpdateReview`, `woocommerceDeleteReview` |

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

### Error handling

Mutations are "payloadable": instead of failing at the top level, they return a `status` field (either `SUCCESS` or `FAILURE`) and an `errors` list of typed error payloads (such as "not logged-in", "no permission", "the entity does not exist", or a referenced entity not existing), which can be inspected via the `ErrorPayload` interface:

```graphql
mutation {
  woocommerceUpdateProduct(input: {
    id: 1730
    regularPrice: "120.00"
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

### Permissions

Every mutation requires the user to be logged-in, and to have the WooCommerce capability for the operation (the same capabilities that the WooCommerce REST API enforces):

- Managing products and coupons requires `manage_woocommerce`; editing or deleting a specific product or coupon additionally requires the corresponding per-object capability.
- Managing orders and customers requires `manage_woocommerce`; editing or deleting a specific customer additionally goes through the `edit_user`/`delete_user` capabilities, so that (for instance) a shop manager cannot target an administrator.
- Managing the taxonomies (categories, tags, brands, shipping classes, and attributes) requires `manage_product_terms`.
- Creating and moderating reviews requires `moderate_comments`.

When the user is not logged-in, or lacks the required capability, the mutation returns an error payload instead of performing the operation.

### Bulk mutations

Every mutation is also available in bulk form, operating on a list of entities within a single request: `woocommerceCreateProducts`, `woocommerceUpdateProducts`, `woocommerceDeleteProducts`, and likewise for every other entity.

Bulk mutations receive an `inputs` list (containing one input per item), and return one payload per item, so the result of each of them can be checked independently:

```graphql
mutation {
  woocommerceCreateProducts(inputs: [
    { name: "T-Shirt", type: simple, regularPrice: "19.99" }
    { name: "Hoodie", type: simple, regularPrice: "39.99" }
  ]) {
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
    }
  }
}
```

They also receive input `stopExecutingMutationItemsOnFirstError`, to stop processing the remaining items as soon as one of them fails:

```graphql
mutation {
  woocommerceUpdateProducts(
    inputs: [
      { id: 123, regularPrice: "24.99" }
      { id: 456, regularPrice: "44.99" }
    ]
    stopExecutingMutationItemsOnFirstError: true
  ) {
    status
    product {
      id
      regularPrice
    }
  }
}
```

### Nested mutations

The WooCommerce entity types also provide `update` and `delete` fields, to operate on the object retrieved by the query:

```graphql
mutation {
  woocommerceProduct(by: { id: 123 }) {
    update(input: { regularPrice: "29.99" }) {
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
      }
    }
  }
}
```

Type `WooCommerceOrder` additionally provides field `updateStatus`, to transition the order's status:

```graphql
mutation {
  woocommerceOrder(by: { id: 789 }) {
    updateStatus(input: { status: "completed" }) {
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
      }
    }
  }
}
```

Please notice that the nested `delete` field sends the entity to the trash (matching the default behavior of the WooCommerce REST API). To delete the entity permanently, use the root `delete*` mutation, passing `force: true`.
