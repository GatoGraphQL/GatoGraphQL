# Sending a notification email to the admin

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
