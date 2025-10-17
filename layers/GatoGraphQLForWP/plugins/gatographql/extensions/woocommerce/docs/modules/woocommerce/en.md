# WooCommerce

Integration with <a href="https://woocommerce.com" target="_blank" rel="nofollow">WooCommerce</a>.

The GraphQL schema is provided with fields to fetch WooCommerce data.

This query retrieves WooCommerce data for products, customers, orders, refunds, etc:

```graphql
query FetchWooCommerceData
{
  # Products
  woocommerceProduct(by: { id: 43 }) {
    __typename
    ...WooCommerceSimpleProductFields
    ...WooCommerceGroupProductFields
    ...WooCommerceExternalProductFields
    ...WooCommerceVariableProductFields
  }

  productBySlug: woocommerceProduct(by: { slug: "iphone-15-pro" }) {
    __typename
    id
    name
    slug
    url
    urlPath
    sku
  }

  productBySku: woocommerceProduct(by: { sku: "IPHONE15PRO" }) {
    __typename
    id
    name
    slug
    url
    urlPath
    sku
  }

  woocommerceProducts {
    __typename
    ...WooCommerceSimpleProductFields
    ...WooCommerceGroupProductFields
    ...WooCommerceExternalProductFields
    ...WooCommerceVariableProductFields
  }
  woocommerceProductsCount

  # Product Categories
  woocommerceProductCategory(by: { id: 43 }) {
    __typename
    ...ProductCategoryFields
  }

  woocommerceProductCategories {
    __typename
    ...ProductCategoryFields
  }
  woocommerceProductCategoriesCount

  # Product Tags
  woocommerceProductTag(by: { id: 55 }) {
    __typename
    ...ProductTagFields
  }

  woocommerceProductTags {
    __typename
    ...ProductTagFields
  }
  woocommerceProductTagsCount

  # Product Brands
  woocommerceProductBrand(by: { id: 73 }) {
    __typename
    ...ProductBrandFields
  }

  woocommerceProductBrands {
    __typename
    ...ProductBrandFields
  }
  woocommerceProductBrandsCount

  # Product Attribute Taxonomies
  woocommerceAttributeTaxonomy(by: { id: 7 }) {
    __typename
    ...AttributeTaxonomyFields
  }

  attributeTaxonomyByName: woocommerceAttributeTaxonomy(by: { taxonomyName: "pa_clothing_size" }) {
    __typename
    ...AttributeTaxonomyFields
  }

  woocommerceAttributeTaxonomies {
    __typename
    ...AttributeTaxonomyFields
  }

  # Product Attributes
  woocommerceAttribute(by: { id: 8 }) {
    __typename
    ...AttributeFields
  }

  attributeBySlug: woocommerceAttribute(by: { slug: "cotton" }, taxonomy: "pa_clothing_size") {
    __typename
    ...AttributeFields
  }

  woocommerceAttributes {
    __typename
    ...AttributeFields
  }
  woocommerceAttributesCount

  # Products by type
  # Simple Products
  woocommerceSimpleProduct(by: { id: 665 }) {
    __typename
    ...WooCommerceSimpleProductFields
  }

  woocommerceSimpleProducts {
    __typename
    ...WooCommerceSimpleProductFields
  }
  woocommerceSimpleProductsCount

  # External Products
  woocommerceExternalProduct(by: { id: 678 }) {
    __typename
    ...WooCommerceExternalProductFields
  }
  woocommerceExternalProductsCount

  woocommerceExternalProducts {
    __typename
    ...WooCommerceExternalProductFields
  }
  
  # Group Products
  woocommerceGroupProduct(by: { id: 332 }) {
    __typename
    ...WooCommerceGroupProductFields
  }
  woocommerceGroupProductsCount

  woocommerceGroupProducts {
    __typename
    ...WooCommerceGroupProductFields
  }

  # Variable Products
  woocommerceVariableProduct(by: { id: 650 }) {
    __typename
    ...WooCommerceVariableProductFields
  }

  woocommerceVariableProducts {
    __typename
    ...WooCommerceVariableProductFields
  }
  woocommerceVariableProductsCount

  # Product Variations
  woocommerceProductVariation(by: { id: 795 }) {
    __typename
    ...WooCommerceProductVariationFields
  }

  woocommerceProductVariations {
    __typename
    ...WooCommerceProductVariationFields
  }
  woocommerceProductVariationsCount

  # Customers
  woocommerceCustomer(by: { id: 32 }) {
    __typename
    ...CustomerFields
  }

  woocommerceCustomers {
    __typename
    ...CustomerFields
  }
  woocommerceCustomersCount

  # Orders
  woocommerceOrder(by: { id: 221 }) {
    __typename
    ...OrderFields
  }

  orderByKey: woocommerceOrder(by: { orderKey: "wc_order_uN0Gr9ntmiXjn" }) {
    id
    orderKey
    orderNumber
    total
    totalFormatted
  }

  woocommerceOrders {
    __typename
    ...OrderFields
  }
  woocommerceOrdersCount

  # Refunds
  woocommerceRefund(by: { id: 2254 }) {
    __typename
    ...RefundFields
  }

  woocommerceRefunds {
    __typename
    ...RefundFields
  }
  woocommerceRefundsCount

  # Product Reviews
  woocommerceReview(by: { id: 44 }) {
    __typename
    ...ReviewFields
  }

  woocommerceReviews {
    __typename
    ...ReviewFields
  }
  woocommerceReviewsCount

  # Coupons
  couponByCode: woocommerceCoupon(by: { code: "save20" }) {
    __typename
    ...CouponFields
  }
  couponByID: woocommerceCoupon(by: { id: 2234 }) {
    id
    code
    amount
  }
  woocommerceCoupons {
    __typename
    ...CouponFields
  }
  woocommerceCouponsCount

  # Shipping Classes
  woocommerceShippingClassByID: woocommerceShippingClass(by: { id: 3 }) {
    __typename
    ...ShippingClassFields
  }

  woocommerceShippingClassBySlug: woocommerceShippingClass(by: { slug: "express" }) {
    __typename
    ...ShippingClassFields
  }

  woocommerceShippingClasses {
    __typename
    ...ShippingClassFields
  }
  woocommerceShippingClassesCount

  # Tax Rates
  woocommerceTaxRate(by: { id: 1 }) {
    __typename
    ...TaxRateFields
  }

  woocommerceTaxRates {
    __typename
    ...TaxRateFields
  }

  # Payment Gateways
  woocommercePaymentGateways {
    __typename
    ...PaymentGatewayFields
  }
}

# ----------------------------------------------------------------------
# Fragments
# ----------------------------------------------------------------------
fragment CouponFields on WooCommerceCoupon {
  id
  code
  amount
  amountFormatted
  dateExpires
  dateExpiresStr
  formattedDateExpiresStr: dateExpiresStr(format: "d/m/Y H:i:s")
  discountType
  description
  date
  modifiedDate
  dateStr
  formattedDateStr: dateStr(format: "d/m/Y H:i:s")
  modifiedDateStr
  formattedModifiedDateStr: modifiedDateStr(format: "d/m/Y H:i:s")
  usageCount
  individualUse
  productIDs
  products {
    id
    name
    slug
  }
  productsCount
  excludedProductIDs
  excludedProducts {
    id
    name
    slug
  }
  excludedProductsCount
  usageLimit
  usageLimitPerUser
  limitUsageToXItems
  freeShipping
  productCategoryIDs
  productCategories {
    id
    name
    slug
  }
  productCategoriesCount
  excludedProductCategoryIDs
  excludedProductCategories {
    id
    name
    slug
  }
  excludedProductCategoriesCount
  productBrandIDs
  productBrands {
    id
    name
    slug
  }
  productBrandsCount
  excludedProductBrandIDs
  excludedProductBrands {
    id
    name
    slug
  }
  excludedProductBrandsCount
  excludeSaleItems
  minimumAmount
  minimumAmountFormatted
  maximumAmount
  maximumAmountFormatted
  emailRestrictions
  usedByCustomerIDs
  usedByCustomers {
    ...CustomerFields
  }
  usedByCustomersCount
}

fragment CustomerFields on WooCommerceCustomer {
  id
  username
  email
  firstName
  lastName
  displayName
  description
  createdDate
  createdDateStr
  formattedCreatedDateStr: createdDateStr(format: "d/m/Y H:i:s")
  modifiedDate
  modifiedDateStr
  formattedModifiedDateStr: modifiedDateStr(format: "d/m/Y H:i:s")
  role
  billing
  shipping
  isPayingCustomer
  avatarURL
  totalSpent
  totalSpentFormatted
  orderCount
  lastOrderID
  lastOrder {
    id
    orderNumber
    status
    total
    date
  }
  orders {
    id
    orderNumber
    status
    total
    date
  }
  ordersCount
}

fragment OrderFields on WooCommerceOrder {
  id
  orderNumber
  orderKey
  date
  modifiedDate
  dateStr
  formattedDateStr: dateStr(format: "d/m/Y H:i:s")
  modifiedDateStr
  formattedModifiedStr: modifiedDateStr(format: "d/m/Y H:i:s")
  status
  isPaid
  currency
  total
  totalFormatted
  subtotal
  subtotalFormatted
  totalTax
  totalTaxFormatted
  totalShipping
  totalShippingFormatted
  totalDiscount
  totalDiscountFormatted
  totalDiscountTax
  totalDiscountTaxFormatted
  shippingTotal
  shippingTotalFormatted
  shippingTax
  shippingTaxFormatted
  cartTax
  cartTaxFormatted
  totalFee
  totalFeeFormatted
  customerID
  customer {
    ...CustomerFields
  }
  customerNote
  billingAddress
  shippingAddress
  hasCompletedStatus: hasStatus(status: "completed")
  hasPendingStatus: hasStatus(status: "pending")
  hasProcessingStatus: hasStatus(status: "processing")
  hasOnHoldStatus: hasStatus(status: "on-hold")
  hasCancelledStatus: hasStatus(status: "cancelled")
  hasRefundedStatus: hasStatus(status: "refunded")
  hasFailedStatus: hasStatus(status: "failed")
  hasAnyCompletedOrProcessingStatus: hasAnyStatus(statuses: ["completed", "processing"])
  hasAnyPendingOrOnHoldStatus: hasAnyStatus(statuses: ["pending", "on-hold"])
  paymentMethod
  paymentMethodTitle
  transactionID
  datePaid
  datePaidStr
  formattedDatePaidStr: datePaidStr(format: "d/m/Y H:i:s")
  dateCompleted
  dateCompletedStr
  formattedDateCompletedStr: dateCompletedStr(format: "d/m/Y H:i:s")
  cartHash
  newOrderEmailSent
  orderCurrency
  orderVersion
  pricesIncludeTax
  discountTotal
  discountTax
  refunded
  remainingRefundAmount
  itemCount
  items {
    ...OrderItemFields
  }
  refunds {
    ...RefundFields
  }
  downloadPermissionsGranted
  needsPayment
  needsProcessing
  isDownloadPermitted
  hasDownloadableItem
}

fragment OrderItemFields on WooCommerceOrderItem {
  id
  name
  quantity
  subtotal
  subtotalFormatted
  total
  totalFormatted
  totalTax
  totalTaxFormatted
  productID
  variationID
  reducedStockNumber
  product {
    id
    name
    slug
    sku
  }
  variation {
    id
    name
    slug
    sku
  }
}

fragment ProductCategoryFields on WooCommerceProductCategory {
  id
  url
  urlPath
  slug
  name
  description
  count
  slugPath
  parentID
  parent {
    id
    name
    slug
  }
  thumbnail {
    id
    src
    altText
    title
    caption
  }
  displayType
  menuOrder
  ancestors {
    id
    name
    slug
  }
  children {
    id
    name
    slug
    parent {
      id
      name
      slug
    }
  }
  childrenCount
  descendants {
    id
    name
    slug
    ancestors {
      id
      name
      slug
    }
  }
  descendantsCount
  termGroup
  termTaxonomyID
  taxonomy
}

fragment ProductTagFields on WooCommerceProductTag {
  id
  name
  slug
  url
  urlPath
  description
  count
  thumbnail {
    id
    src
    altText
    title
    caption
  }
  menuOrder
  termGroup
  termTaxonomyID
  taxonomy
}

fragment RefundFields on WooCommerceRefund {
  id
  orderID
  order {
    id
    orderNumber
    status
  }
  amount
  amountFormatted
  reason
  refundedBy {
    id
    name
    email
  }
  isPaymentRefundedViaAPI
  refundType
  date
  modifiedDate
  dateStr
  formattedDateStr: dateStr(format: "d/m/Y H:i:s")
  modifiedDateStr
  formattedModifiedDateStr: modifiedDateStr(format: "d/m/Y H:i:s")
  status
}

fragment ReviewFields on WooCommerceReview {
  id
  content
  author
  authorEmail
  rating
  verified
  approved
  date
  dateStr
  productID
  product {
    id
    name
  }
}

fragment ShippingClassFields on WooCommerceShippingClass {
  id
  name
  slug
  description
  count
}

fragment TaxRateFields on WooCommerceTaxRate {
  id
  country
  state
  postcode
  city
  rate
  name
  priority
  compound
  shipping
  order
  class
}

fragment PaymentGatewayFields on WooCommercePaymentGateway {
  id
  title
  description
  enabled
  methodID
  methodTitle
  methodDescription
  icon
  isAvailable
  isActive
}

fragment ProductBrandFields on WooCommerceProductBrand {
  id
  url
  urlPath
  slug
  slugPath
  name
  description
  count
  parentID
  parent {
    id
    name
    slug
  }
  thumbnail {
    id
    src
    altText
    title
    caption
  }
  menuOrder
  ancestors {
    id
    name
    slug
  }
  children {
    id
    name
    slug
    parent {
      id
      name
      slug
    }
  }
  childrenCount
  descendants {
    id
    name
    slug
    ancestors {
      id
      name
      slug
    }
  }
  descendantsCount
  termGroup
  termTaxonomyID
  taxonomy
}

fragment AttributeFields on WooCommerceAttribute {
  id
  url
  urlPath
  slug
  name
  description
  count
  menuOrder
  termGroup
  termTaxonomyID
  taxonomy
  taxonomyObject {
    id
    name
    slug
    type
    orderBy
    taxonomy
    hasArchives
    public
  }
}

fragment AttributeTaxonomyFields on WooCommerceAttributeTaxonomy {
  id
  name
  slug
  type
  orderBy
  taxonomy
  hasArchives
  public
}

# ----------------------------------------------------------------------
# Product fields

fragment WooCommerceSimpleProductFields on WooCommerceSimpleProduct {
  # Specific fields for this type

  # Common fields
  ...WooCommerceProductOrProductVariationInterfaceFields
  ...WooCommerceIdentifiableObjectInterfaceFields
  ...WooCommerceProductInterfaceFields
  ...WooCommerceCrossSellableProductInterfaceFields
  ...WooCommerceDownloadableProductOrProductVariationInterfaceFields
  ...WooCommercePriceableProductOrProductVariationInterfaceFields
  ...WooCommerceShippableProductOrProductVariationInterfaceFields
  ...WooCommerceTaxableProductInterfaceFields
  ...WooCommerceWithStockManagementProductOrProductVariationInterfaceFields
}

fragment WooCommerceGroupProductFields on WooCommerceGroupProduct {
  # Specific fields for this type
  hasChildren
  childrenCount
  minPrice
  maxPrice
  minPriceFormatted
  maxPriceFormatted
  children {
    id
    name
    slug
    sku
  }

  # Common fields
  ...WooCommerceProductOrProductVariationInterfaceFields
  ...WooCommerceIdentifiableObjectInterfaceFields
  ...WooCommerceProductInterfaceFields
}

fragment WooCommerceExternalProductFields on WooCommerceExternalProduct {
  # Specific fields for this type
  externalURL
  buttonText

  # Common fields
  ...WooCommerceProductOrProductVariationInterfaceFields
  ...WooCommerceIdentifiableObjectInterfaceFields
  ...WooCommerceProductInterfaceFields
  ...WooCommercePriceableProductOrProductVariationInterfaceFields
  ...WooCommerceTaxableProductInterfaceFields
}

fragment WooCommerceVariableProductFields on WooCommerceVariableProduct {
  # Specific fields for this type
  hasVariations
  variationsCount
  minPrice
  maxPrice
  minRegularPrice
  maxRegularPrice
  minSalePrice
  maxSalePrice
  priceRange
  variations {
    id
    name
    slug
    sku
  }
  defaultAttributes {
    taxonomy
    termSlug
    termObject {
      id
      name
      slug
    }
  }

  # Common fields
  ...WooCommerceProductOrProductVariationInterfaceFields
  ...WooCommerceIdentifiableObjectInterfaceFields
  ...WooCommerceProductInterfaceFields
  ...WooCommerceCrossSellableProductInterfaceFields
  ...WooCommerceTaxableProductInterfaceFields
}

fragment WooCommerceProductVariationFields on WooCommerceProductVariation {
  # Specific fields for this type
  parentID
  parent {
    id
    name
    slug
    sku
  }
  taxClass
  attributes {
    taxonomy
    termSlug
    termObject {
      id
      name
      slug
    }
  }
  slug

  # Common fields
  ...WooCommerceProductOrProductVariationInterfaceFields
  ...WooCommerceDownloadableProductOrProductVariationInterfaceFields
  ...WooCommercePriceableProductOrProductVariationInterfaceFields
  ...WooCommerceShippableProductOrProductVariationInterfaceFields
  ...WooCommerceTaxableProductInterfaceFields
  ...WooCommerceWithStockManagementProductOrProductVariationInterfaceFields
}

# ----------------------------------------------------------------------
# Product Interface fields

fragment WooCommerceCrossSellableProductInterfaceFields on WooCommerceCrossSellableProduct {
  crossSellIDs
  crossSells {
    id
    name
    slug
    sku
  }
}

fragment WooCommerceDownloadableProductOrProductVariationInterfaceFields on WooCommerceDownloadableProductOrProductVariation {
  isDownloadable
  downloadLimit
  downloadExpiry
  downloads
  downloadsCount
}

fragment WooCommercePriceableProductOrProductVariationInterfaceFields on WooCommercePriceableProductOrProductVariation {
  price
  priceFormatted
  regularPrice
  regularPriceFormatted
  salePrice
  salePriceFormatted
  onSale
  dateOnSaleFrom
  dateOnSaleFromStr
  formattedDateOnSaleFromStr: dateOnSaleFromStr(format: "d/m/Y H:i:s")
  dateOnSaleTo
  dateOnSaleToStr
  formattedDateOnSaleToStr: dateOnSaleToStr(format: "d/m/Y H:i:s")
}

fragment WooCommerceShippableProductOrProductVariationInterfaceFields on WooCommerceShippableProductOrProductVariation {
  isVirtual
  weight
  length
  width
  height
  dimensions
  shippingClassID
  shippingClass {
    id
    name
    slug
    description
    count
  }
}

fragment WooCommerceTaxableProductInterfaceFields on WooCommerceTaxableProduct {
  taxStatus
  taxClass
}

fragment WooCommerceWithStockManagementProductOrProductVariationInterfaceFields on WooCommerceWithStockManagementProductOrProductVariation {
  manageStock
  stockQuantity
  stockStatus
  backorders
  backordersAllowed
  backordered
  soldIndividually
  lowStockThreshold
}

fragment WooCommerceProductOrProductVariationInterfaceFields on WooCommerceProductOrProductVariation {
  name
  description
  shortDescription
  sku
  globalUniqueID
  isPurchasable
  image {
    id
    src
    altText
    title
    caption
  }
  imageID
  catalogVisibility
  status
  date
  dateStr
  formattedDateStr: dateStr(format: "d/m/Y H:i:s")
  modifiedDate
  modifiedDateStr
  formattedModifiedDateStr: modifiedDateStr(format: "d/m/Y H:i:s")
}

fragment WooCommerceIdentifiableObjectInterfaceFields on IdentifiableObject {
  id
}

fragment WooCommerceProductInterfaceFields on WooCommerceProduct {
  url
  urlPath
  slug
  featured
  totalSales
  reviewsAllowed
  averageRating
  ratingCount
  upsellIDs
  upsells {
    id
    name
    slug
    sku
  }
  upsellsCount
  crossSellsCount
  purchaseNote
  menuOrder
  type
  isVisible
  categories {
    id
    name
    slug
  }
  categoriesCount
  tags {
    id
    name
    slug
  }
  tagsCount
  brands {
    id
    name
    slug
  }
  brandsCount
  galleryImages {
    id
    src
    altText
    title
    caption
  }
  galleryImagesCount
  attributes {
    name
    taxonomyObject {
      id
      name
      slug
      options {
        id
        name
        slug
      }
    }
    options
    optionTaxonomyTermObjects {
      id
      name
      slug
    }
    position
    isVisible
    isVariation
    isTaxonomy
  }
  reviews {
    ...ReviewFields
  }
  reviewsCount
}
```

