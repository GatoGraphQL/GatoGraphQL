# “Private GraphQL Server for WordPress” Bundle

Use GraphQL as a tool to internally power your application, whether for your custom blocks, themes or plugins.

This bundle provides an internal GraphQL server, that makes it possible to fetch data within PHP code, without accessing any endpoint (whether public or private). This allows you to simplify your functionality, making use of GraphQL to fetch and mutate data.

Read tutorial lesson [Sending a notification when there is a new post](https://gatographql.com/tutorial/sending-a-notification-when-there-is-a-new-post/) for an example invoking the internal GraphQL server.

In addition, fetching data to render dynamic blocks can become DRY: The same GraphQL query can be executed for both the WordPress editor (via JS code) and the back-end when accessing the single post (via PHP code).

Read tutorial lesson [DRY code for blocks in Javascript and PHP](https://gatographql.com/tutorial/dry-code-for-blocks-in-javascript-and-php/), to learn more.

## List of bundled extensions

- [Conditional Field Manipulation](../../../../../extensions/conditional-field-manipulation/docs/modules/conditional-field-manipulation/en.md)
- [Email Sender](../../../../../extensions/email-sender/docs/modules/email-sender/en.md)
- [Field Default Value](../../../../../extensions/field-default-value/docs/modules/field-default-value/en.md)
- [Field on Field](../../../../../extensions/field-on-field/docs/modules/field-on-field/en.md)
- [Field Resolution Caching](../../../../../extensions/field-resolution-caching/docs/modules/field-resolution-caching/en.md)
- [Field Response Removal](../../../../../extensions/field-response-removal/docs/modules/field-response-removal/en.md)
- [Field To Input](../../../../../extensions/field-to-input/docs/modules/field-to-input/en.md)
- [Field Value Iteration and Manipulation](../../../../../extensions/field-value-iteration-and-manipulation/docs/modules/field-value-iteration-and-manipulation/en.md)
- [Helper Function Collection](../../../../../extensions/helper-function-collection/docs/modules/helper-function-collection/en.md)
- [HTTP Client](../../../../../extensions/http-client/docs/modules/http-client/en.md)
- [HTTP Request via Schema](../../../../../extensions/http-request-via-schema/docs/modules/http-request-via-schema/en.md)
- [Internal GraphQL Server](../../../../../extensions/internal-graphql-server/docs/modules/internal-graphql-server/en.md)
- [Multiple Query Execution](../../../../../extensions/multiple-query-execution/docs/modules/multiple-query-execution/en.md)
- [PHP Constants and Environment via Schema](../../../../../extensions/php-constants-and-environment-variables-via-schema/docs/modules/php-constants-and-environment-variables-via-schema/en.md)
- [PHP Functions via Schema](../../../../../extensions/php-functions-via-schema/docs/modules/php-functions-via-schema/en.md)
- [Response Error Trigger](../../../../../extensions/response-error-trigger/docs/modules/response-error-trigger/en.md)
