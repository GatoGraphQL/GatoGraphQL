# “Selective Content Import, Export & Sync for WordPress” Bundle

This bundle provides functionality to import content using different formats:

- JSON
- XML
- CSV

This allows you to import posts from another WordPress site via GraphQL or the REST API, from any non-WordPress site that has an API, from a WordPress RSS feed, or from Excel or Google Sheets (or other) by first exporting their data as CSV.

You can also export data into another WordPress site, for instance to synchronize content from a master site to multiple downstream sites in a WordPress multisite.

Importing/exporting data is granular and selective: Instead of generating a single database dump containing all data, you get to choose what entries are to be imported or exported, and how they are mapped between the source and destination.

You can also dynamically create posts based on customized data structures: Provide a CSV file with any desired columns (eg: `"Image URL 1"`, `"Image URL 2"`, and so on), provide an HTML template containing variables for each of the columns on the CSV (eg: `${imageURL1}`, `${imageURL2}` and so on), and the post will then be created with the custom format.

## List of bundled extensions

- [Conditional Field Manipulation](../../../../../extensions/conditional-field-manipulation/docs/modules/conditional-field-manipulation/en.md)
- [Field Default Value](../../../../../extensions/field-default-value/docs/modules/field-default-value/en.md)
- [Field on Field](../../../../../extensions/field-on-field/docs/modules/field-on-field/en.md)
- [Field Response Removal](../../../../../extensions/field-response-removal/docs/modules/field-response-removal/en.md)
- [Field To Input](../../../../../extensions/field-to-input/docs/modules/field-to-input/en.md)
- [Field Value Iteration and Manipulation](../../../../../extensions/field-value-iteration-and-manipulation/docs/modules/field-value-iteration-and-manipulation/en.md)
- [Helper Function Collection](../../../../../extensions/helper-function-collection/docs/modules/helper-function-collection/en.md)
- [HTTP Client](../../../../../extensions/http-client/docs/modules/http-client/en.md)
- [Multiple Query Execution](../../../../../extensions/multiple-query-execution/docs/modules/multiple-query-execution/en.md)
- [PHP Constants and Environment via Schema](../../../../../extensions/php-constants-and-environment-variables-via-schema/docs/modules/php-constants-and-environment-variables-via-schema/en.md)
- [PHP Functions via Schema](../../../../../extensions/php-functions-via-schema/docs/modules/php-functions-via-schema/en.md)
- [Response Error Trigger](../../../../../extensions/response-error-trigger/docs/modules/response-error-trigger/en.md)
