# “Automated Content Translation & Sync for WordPress Multisite” Bundle

This bundle automates the process of translating all content from a master site, into a WordPress multisite where each language corresponds to a language.

Whenever a new post is published on the master site, it is automatically translated using the <a href="https://cloud.google.com/translate/" target="_blank">Google Translate API</a> (into over 130 languages), directly within the corresponding language site in the WordPress multisite.

Both the Classic Editor and Gutenberg are supported: You can translate the whole HTML content in the post, and also translate each one of the attributes in all blocks in the post, even deep within each block's structure.

The translation workflow is executed like this:

- Set-up the automation process, to be triggered when a new post is published
- The triggered query will call the Google Translate API to translate the content
- A new post on the corresponding language site will be created, containing the translation

After this, the translation can be fixed directly within the corresponding language site, in the WordPress editor.

Notice that you don't need to copy/paste strings, saving you plenty of time!

A single call to the Google Translate API can already send all the content to translate. As a consequence, the context provided to Google Translate will be large, producing a higher quality translation.

There are no extra tables added to the DB, and no extra language columns or meta fields that would require inner joins. As such, the speed to query the database will not be affected.

## List of bundled extensions

- [Automation](../../../../../extensions/automation/docs/modules/automation/en.md)
- [Conditional Field Manipulation](../../../../../extensions/conditional-field-manipulation/docs/modules/conditional-field-manipulation/en.md)
- [Field on Field](../../../../../extensions/field-on-field/docs/modules/field-on-field/en.md)
- [Field Response Removal](../../../../../extensions/field-response-removal/docs/modules/field-response-removal/en.md)
- [Field To Input](../../../../../extensions/field-to-input/docs/modules/field-to-input/en.md)
- [Field Value Iteration and Manipulation](../../../../../extensions/field-value-iteration-and-manipulation/docs/modules/field-value-iteration-and-manipulation/en.md)
- [Google Translate](../../../../../extensions/google-translate/docs/modules/google-translate/en.md)
- [HTTP Client](../../../../../extensions/http-client/docs/modules/http-client/en.md)
- [Internal GraphQL Server](../../../../../extensions/internal-graphql-server/docs/modules/internal-graphql-server/en.md)
- [Multiple Query Execution](../../../../../extensions/multiple-query-execution/docs/modules/multiple-query-execution/en.md)
- [PHP Constants and Environment via Schema](../../../../../extensions/php-constants-and-environment-variables-via-schema/docs/modules/php-constants-and-environment-variables-via-schema/en.md)
- [PHP Functions via Schema](../../../../../extensions/php-functions-via-schema/docs/modules/php-functions-via-schema/en.md)
- [Response Error Trigger](../../../../../extensions/response-error-trigger/docs/modules/response-error-trigger/en.md)
- [Schema Editing Access](../../../../../extensions/schema-editing-access/docs/modules/schema-editing-access/en.md)
