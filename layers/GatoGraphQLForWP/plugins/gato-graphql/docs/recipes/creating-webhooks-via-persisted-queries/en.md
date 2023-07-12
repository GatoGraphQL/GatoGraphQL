# Creating Webhooks to interact with external services

A webhook is an HTTP-based callback function that an external service calls when it produces an event, passing a payload of data along with it. Webhooks enable these different webapps and services to communicate with each other.

Some examples of services invoking webhooks include:

- GitHub, when a repository has a new release
- Dropbox, when a document is updated
- WooCommerce, when an order is added
- Microsoft Teams, to receive rich text messages and post in public channels
- ConvertKit, when a subscriber is activated

With Gato GraphQL, we can create Persisted Queries that act as webhooks:

- Because the Persisted Query is exposed under its own URL, this one can conveniently be input as the target URL for the webhook
- The Persisted Query can deal with that webhook exclusively

In this recipe, we will create a Persisted Query to interact with ConvertKit.

## Browsing the webhook documentation

The first step is to read the documentation for the website, and understand what data is sent via the payload.

Analysing [webhooks in ConvertKit](https://developers.convertkit.com/#webhooks),  subscriber-related events send a `POST` request to our URL with a JSON payload like this:

```json
{
  "subscriber": {
    "id": 1,
    "first_name": "John",
    "email_address": "John@example.com",
    "state": "active",
    "created_at": "2018-02-15T19:40:24.913Z",
    "fields": {
      "My Custom Field": "Value"
    }
  }
}
```

## Extracting the data from the payload

This GraphQL query extracts items `"subscriber.first_name"` and `"subscriber.email_address"` from the payload, and exports them as dynamic variables:

```graphql
query ExtractPayloadData {
  body: _httpRequestBody
  bodyJSONObject: _strDecodeJSONObject(string: $__body)

  subscriberFirstName: _objectProperty(
    object: $__bodyJSONObject,
    by: { path: "subscriber.first_name" }
  )
    @export(as: "subscriberFirstName")
  
  subscriberEmail: _objectProperty(
    object: $__bodyJSONObject,
    by: { path: "subscriber.email_address" }
  )
    @export(as: "subscriberEmail")
}
```

## Executing some action with the data

Once we have extracted the data from the payload, we can execute some action with it.

This GraphQL query deals with the `subscriber.subscriber_unsubscribe` event, sending an email to the person asking for feedback.

```graphql
query CreateEmailMessage
  @depends(on: "ExtractPayloadData")
{
  emailMessageTemplate: _strConvertMarkdownToHTML(
    text: """

Hey {$subscriberFirstName}, it's sad to let you go!

Be welcome to complete [this form](https://forms.gle/FpXNromWAsZYC1zB8) and let us know if we can do anything to improve.

Thanks

    """
  )
  emailMessage: _strReplaceMultiple(
    search: ["{$subscriberFirstName}"],
    replaceWith: [$subscriberFirstName],
    in: $__emailMessageTemplate
  )
    @export(as: "emailMessage")
}

mutation SendEmail @depends(on: "CreateEmailMessage") {
  _sendEmail(
    input: {
      to: $subscriberEmail
      subject: "Would you like to give us feedback on how we can improve?"
      messageAs: {
        html: $emailMessage
      }
    }
  ) {
    status
  }
}
```
