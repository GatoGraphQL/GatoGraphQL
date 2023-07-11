# Sending emails (with pleasure)

This recipe demonstrates several capabilities by Gato GraphQL to send emails.

## Sending emails

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
      replyTo: "replyTo@email.com"

      to: "target@email.com"
      cc: ["cc1@email.com", "cc2@email.com"]
      bcc: ["bcc1@email.com", "bcc2@email.com", "bcc3@email.com"]
      
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

## Using Markdown to compose the email

Field `_strConvertMarkdownToHTML` from the [**Helper Function Collection**](https://gatographql.com/extensions/helper-function-collection/) extension converts Markdown to HTML.

We can use this field, together with [**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/), to compose an email with Markdown:

```graphql
query GetEmailData {
  emailMessageTemplate: _strConvertMarkdownToHTML(
    text: """

Hi {$user}, **we have incredible news!**:

Our plugin will be released soon. Would you like to be part of the beta testing?

If so, please complete this form: [Read online]({$formLink})

    """
  )
  emailMessage: _strReplaceMultiple(
    search: ["{$postAuthorName}", "{$postAuthorLink}", "{$postTitle}", "{$postExcerpt}", "{$postLink}"],
    replaceWith: [$postAuthorName, $postAuthorLink, $postTitle, $postExcerpt, $postLink],
    in: $__emailMessageTemplate
  )
    @export(as: "emailMessage")
  subject: _sprintf(string: "New post created by %s", values: [$postAuthorName])
    @export(as: "emailSubject")
}

mutation SendEmail @depends(on: "GetEmailData") {
  _sendEmail(
    input: {
      to: "target@email.com"
      subject: $emailSubject
      messageAs: {
        html: $emailMessage
      }
    }
  ) {
    status
  }
}
```
