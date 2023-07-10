# Sending emails

Mutation `_sendEmail` sends emails by executing WordPress `wp_mail` function. As a result, it will use the configuration defined for sending emails in WordPress (such as the SMTP provider to use).

The email can be sent with content types "text" or "HTML", depending on the value of the `messageAs` input (which is a "oneof" InputObject, so that only one of its properties can be provided).

To send as text, provide property `messageAs.text`:

```graphql
mutation {
  _sendEmail(
    input: {
      to: "target@email.com"
      subject: "Email with text content"
      messageAs: {
        text: "Hello world!"
      }
    }
  ) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
}
```

To send as HTML, provide property `messageAs.html`:

```graphql
mutation {
  _sendEmail(
    input: {
      to: "target@email.com"
      subject: "Email with HTML content"
      messageAs: {
        html: "<p>Hello world!</p>"
      }
    }
  ) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
}
```