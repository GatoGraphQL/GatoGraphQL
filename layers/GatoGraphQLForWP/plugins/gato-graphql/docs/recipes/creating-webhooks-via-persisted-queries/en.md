# Creating Webhooks to interact with external services

A webhook is an HTTP-based callback function that an external service calls when it produces an event, passing a payload of data along with it. Webhooks enable these different webapps and services to communicate with each other.

Some examples of services invoking webhooks include:

- GitHub, when a repository has a new release
- Dropbox, when a document is updated
- WooCommerce, when an order is added
- Microsoft Teams, to receive rich text messages and post in public channels

With Gato GraphQL, we can create Persisted Queries that act as webhooks:

- The Persisted Query is exposed under its own URL, which must be input as the outgoing webhook into the service
- It must interpret the incoming payload, and do something with its data

In this recipe, we will provide a webhook to interact with GitHub Actions.

