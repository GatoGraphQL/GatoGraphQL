# Email Sender

Send emails via global mutation `_sendEmail`.

## Description

Mutation `_sendEmail` sends emails by executing WordPress `wp_mail` function. As a result, it will use the configuration defined for sending emails in WordPress (such as the SMTP provider to use).

The email can be sent with content types "text" or "HTML", depending on the value of the `messageAs` input, which is a "oneof" InputObject (i.e. only one of its properties can be provided).

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

## Global Field

`_sendEmail` is a global field (or, more precisely, a global mutation). This means that, if **Nested Mutations** are enabled, this mutations can be executed on any type (i.e. not only in `MutationRoot`).

This is useful for iterating a list of users, and sending an email to each of them (in this case, the mutation is triggered while in the `User` type):

```graphql
mutation {
  users {
    email
    _sendEmail(
      input: {
        to: $__email
        subject: "..."
        messageAs: {
          text: "..."
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

Combined with other features (provided by the **SpecialSchema** extension), we can create personalized messages for every user:

```graphql
mutation {
  users {
    email
    displayName
    remainingCredits: metaValue(key: "credits")
    emailMessage: _sprintf(
      string: """
      <p>Hello %s!</p>
      <p>Your have <strong>%s remaining credits</strong> in your account.</p>
      <p><a href="%s">Buy more?</a></p>
      """,
      values: [
        $__displayName,
        $__remainingCredits,
        "https://mysite.com/buy-credits"
      ]
    )
    _sendEmail(
      input: {
        to: $__email
        subject: "Remaining credits"
        messageAs: {
          html: $__emailMessage
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
}
```

## Further examples

The query below sends an email to the admin user with some post's content (eg: it can be triggered whenever a new post is published). It uses:

- **Multiple Query Execution** to manage the query into logical units
- Field `_strConvertMarkdownToHTML` from **Helper Fields** to compose the email message using Markdown
- Fields `_strReplaceMultiple` and `_sprintf` from **Function Fields** to dynamically inject values into the email subject and message
- **Field to Input** to retrieve and provide the admin's email from `wp_options`

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
  emailMessageTemplate: _strConvertMarkdownToHTML(
    text: """

There is a new post by [{$postAuthorName}]({$postAuthorLink}):

**{$postTitle}**: {$postExcerpt}

[Read online]({$postLink})

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
  adminEmail: optionValue(name: "admin_email")
  _sendEmail(
    input: {
      to: $__adminEmail
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
