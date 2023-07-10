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
