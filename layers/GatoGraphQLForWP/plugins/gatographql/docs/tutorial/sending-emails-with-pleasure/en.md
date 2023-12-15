# Sending emails (with pleasure)

This tutorial lesson demonstrates several capabilities by Gato GraphQL to send emails.

## Sending emails

We send emails via mutation `_sendEmail` provided by the [**Email Sender**](https://gatographql.com/extensions/email-sender/) extension.

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- The email is sent with content type "text" or "HTML" depending on what property from the `messageAs` input is used
- The `from` input is optional; if not provided, the settings stored in WordPress are used
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

## Composing the email using Markdown

Field `_strConvertMarkdownToHTML` from the [**Helper Function Collection**](https://gatographql.com/extensions/helper-function-collection/) extension converts Markdown to HTML.

We can use this field to compose the email using Markdown:

```graphql
query GetEmailData {
  emailMessage: _strConvertMarkdownToHTML(
    text: """

We have great news: **Version 1.0 of our plugin will be released soon!**

If you'd like to help us beta test it, please complete [this form](https://forms.gle/FpXNromWAsZYC1zB8).

_Please reply by 30th June 🙏_

Thanks!

    """
  )
    @export(as: "emailMessage")
}

mutation SendEmail @depends(on: "GetEmailData") {
  _sendEmail(
    input: {
      to: "target@email.com"
      subject: "Great news!"
      messageAs: {
        html: $emailMessage
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

## Injecting dynamic data into the email

Using the function fields provided by the [**PHP Functions via Schema**](https://gatographql.com/extensions/php-functions-via-schema/), we can create a message template containing placeholders, and replace them with dynamic data:

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

## Sending a notification email to the admin

We can retrieve the email of the admin user from the WordPress `wp_options` table, and inject this value into the `to` field:

```graphql
query ExportData {
  adminEmail: optionValue(name: "admin_email")
    @export(as: "adminEmail")
}

mutation SendEmail @depends(on: "ExportData") {
  _sendEmail(
    input: {
      to: $adminEmail
      subject: "Admin notification"
      messageAs: {
        html: "There is a new post on the site, go check!"
      }
    }
  ) {
    status
  }
}
```

Alternatively, if [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) is enabled in the Schema Configuration, we can retrieve the admin email in the `mutation` operation already (and inject it into the mutation via [**Field to Input**](https://gatographql.com/extensions/field-to-input/)):

```graphql
mutation SendEmail {
  adminEmail: optionValue(name: "admin_email")
  _sendEmail(
    input: {
      to: $__adminEmail
      subject: "Admin notification"
      messageAs: {
        html: "There is a new post on the site, go check!"
      }
    }
  ) {
    status
  }
}
```

## Sending a personalized email to users

<div class="doc-config-highlight" markdown=1>

⚙️ **Configuration alert:**

For this GraphQL query to work, the [Schema Configuration](https://gatographql.com/guides/use/creating-a-schema-configuration/) applied to the endpoint needs to have [Nested Mutations](https://gatographql.com/guides/schema/using-nested-mutations/) enabled

</div>

Because `_sendEmail` is a global field (or, more precisely, a global mutation), it can be executed on any type from the GraphQL schema, including `User`.

This query retrieves a list of users, obtains their data (name, email and number of remaining credits, which is stored as meta), and sends a personalized email to each of them:

```graphql
mutation {
  users {
    email
    displayName
    credits: metaValue(key: "credits")
    
    # If the user does not have meta entry "credits", use `0` credits
    hasNoCreditsEntry: _isNull(value: $__credits)
    remainingCredits: _if(condition: $__hasNoCreditsEntry, then: 0, else: $__credits)

    emailMessageTemplate: _strConvertMarkdownToHTML(
      text: """

Hello %s,

Your have **%s remaining credits** in your account.

Would you like to [buy more](%s)?

      """
    )
    emailMessage: _sprintf(
      string: $__emailMessageTemplate,
      values: [
        $__displayName,
        $__remainingCredits,
        "https://mysite.com/buy-credits"
      ]
    )

    _sendEmail(
      input: {
        to: $__email
        subject: "Remaining credits alert"
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
