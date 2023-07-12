# Creating Webhooks to interact with external services

A webhook is an HTTP-based callback function that an external service calls when it produces an event, passing a payload of data along with it. Webhooks enable these different webapps and services to communicate with each other.

Some examples of services invoking webhooks include:

- GitHub, when a repository has a new release
- Dropbox, when a document is updated
- WooCommerce, when an order is added
- Microsoft Teams, to receive rich text messages and post in public channels
- ConvertKit, when a subscriber is activated

With Gato GraphQL, we can create Persisted Queries that act as webhooks:

- The Persisted Query is exposed under its own URL, which must be input as the outgoing webhook into the service
- It must interpret the incoming payload, and do something with its data

In this recipe, we will create a webhook that receives data from ConvertKit.

## Browsing the webhook documentation

The first step is to read the documentation for the website, and understand what data is sent in the payload.

For instance, [webhooks for subscriber-related events in ConvertKit](https://developers.convertkit.com/#webhooks) send a `POST` request to our URL with a JSON payload like this:

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

