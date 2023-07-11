# Using Markdown to compose the email

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
