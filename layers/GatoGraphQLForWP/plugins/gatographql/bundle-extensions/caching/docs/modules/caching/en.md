# “Caching” Bundle

Use your WordPress site to receive, process and execute some operation using the incoming data from any service, via customized webhooks published directly within the wp-admin.

Some examples include:

- Register a newsletter subscriber from InstaWP to Mailchimp
- Send an email when a user unsubscribes from your newsletter list in ConvertKit
- Add metadata in your WordPress site containing information from your plugin, whenever a pull request is merged on your GitHub repo
- Update a post referencing a document in Dropbox whenever it's updated

Read tutorial lesson [Interacting with external services via webhooks](https://gatographql.com/tutorial/interacting-with-external-services-via-webhooks/) to learn more.

In addition to the HTTP client extension to interact with external services, this bundle includes the Access Control extensions, to validate that your public webhooks only accept calls from the expected services.

## List of bundled extensions

- [Access Control](../../../../../extensions/access-control/docs/modules/access-control/en.md)
- [Access Control Visitor IP](../../../../../extensions/access-control-visitor-ip/docs/modules/access-control-visitor-ip/en.md)
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
- [Multiple Query Execution](../../../../../extensions/multiple-query-execution/docs/modules/multiple-query-execution/en.md)
- [PHP Constants and Environment via Schema](../../../../../extensions/php-constants-and-environment-variables-via-schema/docs/modules/php-constants-and-environment-variables-via-schema/en.md)
- [PHP Functions via Schema](../../../../../extensions/php-functions-via-schema/docs/modules/php-functions-via-schema/en.md)
- [Response Error Trigger](../../../../../extensions/response-error-trigger/docs/modules/response-error-trigger/en.md)
