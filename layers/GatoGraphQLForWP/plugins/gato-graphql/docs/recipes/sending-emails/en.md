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

```graphql
mutation {
  passingAllInputs: _sendEmail(
    input: {
      to: "target@email.com"
      from: {
        email: "from@email.com"
        name: "From my beautiful name"
      }
      subject: "Passing all inputs in the email"
      messageAs: {
        html: """
        <p><strong>Hello "world"!</strong> Is everything good?</p>
        <p>Of course it is!</p>
        """
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
}
```

```graphql
query GetPostData($postID: ID!) {
  post(by: {id: $postID}) {
    title @export(as: "postTitle")
    excerpt @export(as: "postExcerpt")
    url @export(as: "postLink")
    author {
      name @export(as: "postAuthorName")
      url @export(as: "postAuthorLink")
    }
  }
}

query GetEmailData @depends(on: "GetPostData") {
  emailMessage: _strReplaceMultiple(
    search: ["{$postAuthorName}", "{$postAuthorLink}", "{$postTitle}", "{$postExcerpt}", "{$postLink}"],
    replaceWith: [$postAuthorName, $postAuthorLink, $postTitle, $postExcerpt, $postLink],
    in: """
      <p>There is a new post by <a href="{$postAuthorLink}">{$postAuthorName}</a>:

      <p><strong>{$postTitle}</strong>: {$postExcerpt}</p>

      <p><a href="{$postLink}">Read online</a>
    """
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

Add example of sending to admin user with wp_option
