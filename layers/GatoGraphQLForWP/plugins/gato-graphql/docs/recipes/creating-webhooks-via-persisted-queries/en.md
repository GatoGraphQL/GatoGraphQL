# Creating Webhooks to interact with external services

A webhook is an HTTP-based callback function that an external service calls when it produces an event, passing a payload of data along with it. Webhooks enable these different webapps and services to communicate with each other.

Some examples of services invoking webhooks include:

- GitHub, when a repository has a new release
- Dropbox, when a document is updated
- WooCommerce, when an order is added
- Microsoft Teams, to receive rich text messages and post in public channels

We can react to events from external services by adding a webhook that points to some Persisted Query on our website.

The Persisted Query must interpret the payload, and do something with its data.

