# Release Notes: 19.3

## Improvements

### Fewer options loaded on every request

WordPress loads the options marked as "autoload" on every single request, front-end ones included. Three of the plugin's own were marked that way and had no business being: the cached list of models retrieved from the AI services, the log entry counts, and the internal flags kept between one request and the next ([#3387](https://github.com/GatoGraphQL/GatoGraphQL/pull/3387)).

They are read in the wp-admin, in the WP-CLI commands and while a translation is running — the log counts also on any request that logs — and the AI model data in particular can run to hundreds of kilobytes on a site with several AI services configured. They are now read where they are used instead of on every request.

The same goes for the record of which version of the plugin and of each extension is installed, which is only consulted in the wp-admin to notice that one of them has just been activated or updated ([#3388](https://github.com/GatoGraphQL/GatoGraphQL/pull/3388)).

Nothing needs doing on an existing site: the options are migrated the next time the plugin is updated or activated.

### Smaller license records

The record of the activated licenses is one option the plugin does need on every request, since it is what tells an extension that it may run. Each license was stored together with the raw response received from the marketplace when it was activated, which nothing ever read back and which made up two thirds of the record: with the full set of extensions activated, 36 KB out of 56 KB ([#3397](https://github.com/GatoGraphQL/GatoGraphQL/pull/3397)).

The response is no longer stored when a license is activated or validated. The records written by earlier versions are left as they are; they shed the response the next time their license is validated against the marketplace, which happens every few days.

## Added

### Deleting the plugin's data

WordPress deletes a plugin's files when the plugin is deleted, and leaves everything it stored in the database behind. Under **Settings => Plugin Management => Uninstall** you can now ask for that data to be removed as well ([#3398](https://github.com/GatoGraphQL/GatoGraphQL/pull/3398)).

Two choices are offered, both off to begin with, so nothing is ever deleted unless you say so. The first removes what the plugin stored for itself: its settings, the metadata it added to your content, and its database tables. The second also removes the entries you created through the plugin, such as your Persisted Queries and Custom Endpoints; it only applies when the first is on.

Leaving both off keeps the current behaviour, which is also what you want when moving the plugin between servers, or reinstalling it: your settings and entries are still there when the plugin comes back.


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

## Fixed

### Bulk actions with custom settings on screens holding special characters

Executing a bulk action with custom settings takes a detour through the settings form, which carries every value of the originating request along with it and posts them back once the settings are chosen. The values were not encoded on the way, so a `#`, `&` or `=` inside one of them cut the list short, and whatever came after it was lost, the selected items included: the action then went back to the screen having done nothing ([#3396](https://github.com/GatoGraphQL/GatoGraphQL/pull/3396)).

Polylang's "Translations" screen posts every stored translation on the page, and an apostrophe stored as `&#039;` was enough to trigger it. The values are now encoded in both directions.

The same detour added a backslash before every quote in the values it carried, as they were read from the request WordPress had already slashed and then posted back to be slashed again. They are now carried unslashed.

### User queries after filtering by several emails

Filtering users by several emails at once is done by adjusting the SQL of that one query. The adjustment was meant to be removed right after, but stayed in place, so a later user query in the same request whose search held an `@` and a comma was adjusted too. It is now removed once the query has run ([#3404](https://github.com/GatoGraphQL/GatoGraphQL/pull/3404)).

### Log entries with an HTML entity in their context

The Logs page shows the context of an entry as JSON under "Additional context". When one of its strings held an HTML entity, such as `&quot;` in a response from an AI service, the page could not read the JSON, and printed the whole entry as one line of text instead. It now shows the context, with the entity as it was logged ([#3406](https://github.com/GatoGraphQL/GatoGraphQL/pull/3406)).

## Security

### Escaped IDs on the custom settings page

The custom settings page names the IDs selected on the originating screen in a notice. They were printed as they came in the URL, so a crafted link to that page could run a script in the wp-admin of the user who followed it. The IDs are now escaped ([#3396](https://github.com/GatoGraphQL/GatoGraphQL/pull/3396)).
