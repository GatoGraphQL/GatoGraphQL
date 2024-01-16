# “Tailored WordPress Automator” Bundle

Automate tasks in your site. The automation action is done by executing a GraphQL persisted query, triggered by:

- Scheduling a WP-Cron event
- Reacting to a WordPress hook
- Chaining after the completion of a previous GraphQL persisted query

As these GraphQL persisted queries can be set as `private`, these tasks will only be executed internally (i.e. they are not exposed to the Internet).

Some automation tasks you can execute are:

- When uploading an image, if it has no description, call the OpenAI API and generate a caption for that image
- If a published post has no thumbnail, automatically create an image with generative AI using the post's title as the prompt, and set it as a post's featured image
- Check if a newly-published post contains some mandatory block and, if not, add it
- Post newly-uploaded images to Instagram
- Send a daily summary of activity in the site to your email
- Send a "happy birthday" email to your users
- Send yourself a reminder to contact a customer, two weeks after a product was sold
- Send a notification to users when their comment was replied to
- Send a welcome email to new users
- Many more...

Read tutorial lesson [Sending a daily summary of activity](https://gatographql.com/tutorial/sending-a-daily-summary-of-activity/) for an example of using WP-Cron to automate sending a daily activity email to the admin.

## List of bundled extensions

- [Automation](../../../../../extensions/automation/docs/modules/automation/en.md)
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

<!-- ## Tutorial lessons powered by the “Tailored WordPress Automator” Bundle

- [Searching WordPress data](../../../../../docs/tutorial/searching-wordpress-data/en.md)
- [Querying dynamic data](../../../../../docs/tutorial/querying-dynamic-data/en.md)
- [Complementing WP-CLI](../../../../../docs/tutorial/complementing-wp-cli/en.md)
- [Injecting multiple resources into WP-CLI](../../../../../docs/tutorial/injecting-multiple-resources-into-wp-cli/en.md)
- [Mapping JS components to (Gutenberg) blocks](../../../../../docs/tutorial/mapping-js-components-to-gutenberg-blocks/en.md)
- [Duplicating a blog post](../../../../../docs/tutorial/duplicating-a-blog-post/en.md)
- [Duplicating multiple blog posts at once](../../../../../docs/tutorial/duplicating-multiple-blog-posts-at-once/en.md)
- [Customizing content for different users](../../../../../docs/tutorial/customizing-content-for-different-users/en.md)
- [Search, replace, and store again](../../../../../docs/tutorial/search-replace-and-store-again/en.md)
- [Adapting content in bulk](../../../../../docs/tutorial/adapting-content-in-bulk/en.md)
- [Site migrations](../../../../../docs/tutorial/site-migrations/en.md)
- [Inserting/Removing a (Gutenberg) block in bulk](../../../../../docs/tutorial/inserting-removing-a-gutenberg-block-in-bulk/en.md)
- [Retrieving structured data from blocks](../../../../../docs/tutorial/retrieving-structured-data-from-blocks/en.md)
- [Modifying (and storing again) the image URLs from all Image blocks in a post](../../../../../docs/tutorial/modifying-and-storing-again-the-image-urls-from-all-image-blocks-in-a-post/en.md)
- [Sending emails with pleasure](../../../../../docs/tutorial/sending-emails-with-pleasure/en.md)
- [Sending a notification when there is a new post](../../../../../docs/tutorial/sending-a-notification-when-there-is-a-new-post/en.md)
- [Sending a daily summary of activity](../../../../../docs/tutorial/sending-a-daily-summary-of-activity/en.md)
- [Automatically adding a mandatory block](../../../../../docs/tutorial/automatically-adding-a-mandatory-block/en.md)
- [Interacting with external services via webhooks](../../../../../docs/tutorial/interacting-with-external-services-via-webhooks/en.md)
- [Retrieving data from an external API](../../../../../docs/tutorial/retrieving-data-from-an-external-api/en.md)
- [Combining user data from different sources](../../../../../docs/tutorial/combining-user-data-from-different-sources/en.md)
- [Not leaking credentials when connecting to services](../../../../../docs/tutorial/not-leaking-credentials-when-connecting-to-services/en.md)
- [Handling errors when connecting to services](../../../../../docs/tutorial/handling-errors-when-connecting-to-services/en.md)
- [Creating an API gateway](../../../../../docs/tutorial/creating-an-api-gateway/en.md)
- [Transforming data from an external API](../../../../../docs/tutorial/transforming-data-from-an-external-api/en.md)
- [Filtering data from an external API](../../../../../docs/tutorial/filtering-data-from-an-external-api/en.md)
- [Pinging external services](../../../../../docs/tutorial/pinging-external-services/en.md)
- [Updating large sets of data](../../../../../docs/tutorial/updating-large-sets-of-data/en.md)
- [Importing a post from another WordPress site](../../../../../docs/tutorial/importing-a-post-from-another-wordpress-site/en.md)
- [Distributing content from an upstream to multiple downstream sites](../../../../../docs/tutorial/distributing-content-from-an-upstream-to-multiple-downstream-sites/en.md)
- [Automatically sending newsletter subscribers from InstaWP to Mailchimp](../../../../../docs/tutorial/automatically-sending-newsletter-subscribers-from-instawp-to-mailchimp/en.md) -->
