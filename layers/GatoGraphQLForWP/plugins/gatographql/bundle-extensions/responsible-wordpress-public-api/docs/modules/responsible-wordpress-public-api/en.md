# “Responsible WordPress Public API” Bundle

If you need to expose data via a public API in your WordPress site, while being able to sleep at night (knowing that your site is safe), then you need this bundle!

You will be able to:

- Render your WordPress site in headless mode, using the JS framework of your choice
- Give access to your clients to their own data
- Power applications (mobile app, newsletter, etc) fetching data from your WordPress site
- Create a single source of truth for all your content, editing content in a master site only, from which it is distributed to multiple downstream sites

This bundle enhances your public APIs with the following qualities:

**Security:** Grant access to the API to selected users only via Access Control, with granular access, field by field. Validate that the endpoint can only be accessed from a given IP. Create multiple custom endpoints for different clients, customizing access to each of them, making sure that only the relevant data is exposed to each of them.

**Speed:** Cache the response of the GraphQL API via standard HTTP caching, at the browser and intermediate layers between the client and the server (such as a CDN). Avoid installing 3rd-party libraries on the client-side that slow your website down (eg: when accessed from a cheap mobile phone).

**Schema Evolution:** Deprecate fields and inform your users of the changes, already within the wp-admin, without any PHP code.

**Power:** Access and manipulate data with a plethora of fields and directives.

**Control:** Allow teammates to create and configure endpoints.

With the provided extensions in this bundle, creating and configuring public endpoints is super easy, and it only takes minutes.

## List of bundled extensions

- [Access Control](../../../../../extensions/access-control/docs/modules/access-control/en.md)
- [Access Control Visitor IP](../../../../../extensions/access-control-visitor-ip/docs/modules/access-control-visitor-ip/en.md)
- [Cache Control](../../../../../extensions/cache-control/docs/modules/cache-control/en.md)
- [Conditional Field Manipulation](../../../../../extensions/conditional-field-manipulation/docs/modules/conditional-field-manipulation/en.md)
- [Deprecation Notifier](../../../../../extensions/deprecation-notifier/docs/modules/deprecation-notifier/en.md)
- [Field Default Value](../../../../../extensions/field-default-value/docs/modules/field-default-value/en.md)
- [Field Deprecation](../../../../../extensions/field-deprecation/docs/modules/field-deprecation/en.md)
- [Field To Input](../../../../../extensions/field-to-input/docs/modules/field-to-input/en.md)
- [Field Value Iteration and Manipulation](../../../../../extensions/field-value-iteration-and-manipulation/docs/modules/field-value-iteration-and-manipulation/en.md)
- [Low-Level Persisted Query Editing](../../../../../extensions/low-level-persisted-query-editing/docs/modules/low-level-persisted-query-editing/en.md)
- [Multiple Query Execution](../../../../../extensions/multiple-query-execution/docs/modules/multiple-query-execution/en.md)
- [Response Error Trigger](../../../../../extensions/response-error-trigger/docs/modules/response-error-trigger/en.md)
- [Schema Editing Access](../../../../../extensions/schema-editing-access/docs/modules/schema-editing-access/en.md)

<!-- ## Tutorial lessons powered by the “Responsible WordPress Public API” Bundle

- [Searching WordPress data](../../../../../docs/tutorial/searching-wordpress-data/en.md)
- [Complementing WP-CLI](../../../../../docs/tutorial/complementing-wp-cli/en.md)
- [Mapping JS components to (Gutenberg) blocks](../../../../../docs/tutorial/mapping-js-components-to-gutenberg-blocks/en.md) -->
