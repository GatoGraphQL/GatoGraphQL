# Using Markdown to compose the email




	Add to some recipe (from submodules/PoP/layers/GatoGraphQLForWP/plugins/gato-graphql/docs-pro/modules/email-sender/en.md):

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

		
	
	Add in some recipe:
		content from send-email-with-content-from-post-and-markdown.gql:
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
