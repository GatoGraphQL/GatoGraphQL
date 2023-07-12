# Creating Webhooks (via Persisted Queries)

A webhook is an HTTP-based callback function that allows lightweight, event-driven communication between 2 application programming interfaces (APIs). Webhooks are used by a wide variety of web apps to receive small amounts of data from other apps, but webhooks can also be used to trigger automation workflows in GitOps environments. _([source](https://www.redhat.com/en/topics/automation/what-is-a-webhook))_

Some examples of websites invoking webhooks, sending along the payload of some action, include:

- GitHub, when the repository has a new release
- Dropbox, when a document is updated
- WooCommerce, when an order is added
- Microsoft Teams, to receive rich text messages and post in public channels



If we know these, we can extract their data, and do something with it

Recipes: design/code the GraphQL queries to react to every know Webhook
