# Sending emails

We send emails via mutation `_sendEmail` provided by the [**Email Sender**](https://gatographql.com/extensions/email-sender/) extension.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- The emails is sent with content type "text" or "HTML" depending on what property from the `messageAs` input is used
- The `from` input is optional; if not provided, the settings stored in WordPress is used
- `_sendEmail` executes WordPress `wp_mail` function, so it will use the configuration defined for sending emails in WordPress (such as the SMTP provider to use)

</div>

```graphql
mutation {
  sendTextEmail: _sendEmail(
    input: {
      from: {
        email: "from@email.com"
        name: "Me myself"
      }
      to: "target@email.com"
      subject: "Email with text content"
      messageAs: {
        text: "Hello world!"
      }
      replyTo: "replyTo@email.com"
      cc: ["cc1@email.com", "cc2@email.com"]
      bcc: ["bcc1@email.com", "bcc2@email.com", "bcc3@email.com"]
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
  
  sendHTMLEmail: _sendEmail(
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
