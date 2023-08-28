# Helper Function Collection

Collection of fields added to the GraphQL schema, providing useful functionality concerning URLs, date formatting, text manipulation, and others.

Helper fields are **Global Fields**, hence they are added to every single type in the GraphQL schema: in `QueryRoot`, but also in `Post`, `User`, etc.

## List of Helper Fields

This is the list of helper fields.

### `_objectConvertToNameValueEntryList`

Retrieve the properties from a JSON object to create a list of JSON entries.

This field is used to transform a `JSONObject` output from some field, into a `[JSONObject]` that is input into another field.

For instance, the response from `_httpRequestHeaders` (from the **HTTP Request via Schema** extension) is a `StringValueJSONObject`, and the headers passed as input in `_sendHTTPRequest` are `[HTTPRequestOptionHeaderInput!]`, with each `HTTPRequestOptionHeaderInput` having shape:

```json
{
  "name": "...",
  "value": "..."
}
```

Then, the following query allows to bridge between output and input:

```graphql
{
  headers: _httpRequestHeaders
  headersInput: _objectConvertToNameValueEntryList(
    object: $__headers
  )
  _sendHTTPRequest(
    input: {
      url: "...",
      options: {
        headers: $__headersInput
      }
    }
  ) {
    # ...
  }
}
```

### `_strConvertMarkdownToHTML`

Converts Markdown to HTML.

This method can help produce HTML content that is provided as input to some field or mutation. That is the case with mutation `_sendEmail` (from the **Email Sender** extension), which can send emails in HTML format.

For instance, this query uses Markdown content to produce the HTML to send in the email:

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

### `_urlAddParams`

Adds params to a URL.

The parameters input is a `JSONObject` of `param name => value`, allowing us to pass values of multiple types, including `String`, `Int`, List (eg: `[String]`) and also `JSONObject`.

This query:

```graphql
{
  _urlAddParams(
    url: "https://gatographql.com",
    params: {
      stringParam: "someValue",
      intParam: 5,
      stringListParam: ["value1", "value2"],
      intListParam: [8, 9, 4],
      objectParam: {
        "1st": "1stValue",
        "2nd": 2,
        "3rd": ["uno", 2.5]
        "4th": {
          nestedIn: "nestedOut"
        }
      }
    }
  )
}
```

...produces:

```json
{
  "data": {
    "_urlAddParams": "https:\/\/gatographql.com?stringParam=someValue&intParam=5&stringListParam%5B0%5D=value1&stringListParam%5B1%5D=value2&intListParam%5B0%5D=8&intListParam%5B1%5D=9&intListParam%5B2%5D=4&objectParam%5B1st%5D=1stValue&objectParam%5B2nd%5D=2&objectParam%5B3rd%5D%5B0%5D=uno&objectParam%5B3rd%5D%5B1%5D=2.5&objectParam%5B4th%5D%5BnestedIn%5D=nestedOut"
  }
}
```

(The decoded URL is `"https://gatographql.com?stringParam=someValue&intParam=5&stringListParam[0]=value1&stringListParam[1]=value2&intListParam[0]=8&intListParam[1]=9&intListParam[2]=4&objectParam[1st]=1stValue&objectParam[2nd]=2&objectParam[3rd][0]=uno&objectParam[3rd][1]=2.5&objectParam[4th][nestedIn]=nestedOut"`.)

Please notice that `null` values are not added to the URL.

This query:

```graphql
{
  _urlAddParams(
    url: "https://gatographql.com",
    params: {
      stringParam: null,
      listParam: [1, null, 3],
      objectParam: {
        uno: null,
        dos: 2
      }
    }
  )
}
```

...produces:

```json
{
  "data": {
    "_urlAddParams": "https:\/\/gatographql.com?listParam%5B0%5D=1&listParam%5B2%5D=3&objectParam%5Bdos%5D=2"
  }
}
```

(The decoded URL is `"https://gatographql.com?listParam[0]=1&listParam[2]=3&objectParam[dos]=2"`.)

### `_urlRemoveParams`

Removes params from a URL.

This query:

```graphql
{
  _urlRemoveParams(
    url: "https://gatographql.com/?existingParam=existingValue&stringParam=originalValue&stringListParam[]=firstVal&stringListParam[]=secondVal&stringListParam[]=thirdVal",
    names: [
      "existingParam"
      "stringParam"
      "stringListParam"
    ]
  )
}
```

...produces:

```json
{
  "data": {
    "_urlRemoveParams": "https:\/\/gatographql.com\/"
  }
}
```

## Examples

In combination with extensions **HTTP Request via Schema** and **Field to Input**, we can retrieve the currently-requested URL when executing a GraphQL custom endpoint or persisted query, add extra parameters, and send another HTTP request to the new URL.

For instance, in this query, we retrieve the IDs of the users in the website and execute a new GraphQL query passing their ID as parameter:

```graphql
{
  users {
    userID: id
    url: _urlAddParams(
      url: "https://somewebsite.com/endpoint/user-data",
      params: {
        userID: $__userID
      }
    )
    headers: _httpRequestHeaders
    headerNameValueEntryList: _objectConvertToNameValueEntryList(
      object: $__headers
    )
    _sendHTTPRequest(input: {
      url: $__url
      options: {
        headers: $__headerNameValueEntryList
      }
    }) {
      statusCode
      contentType
      body
    }
  }
}
```

## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-extensions/docs/modules/all-extensions/en.md)
- [“Application Glue & Automator” Bundle](../../../../../bundle-extensions/application-glue-and-automator/docs/modules/application-glue-and-automator/en.md)

## Recipes using extension

- [Sending emails with pleasure](../../../../../docs/recipes/sending-emails-with-pleasure/en.md)
- [Sending a notification when there is a new post](../../../../../docs/recipes/sending-a-notification-when-there-is-a-new-post/en.md)
- [Sending a daily summary of activity](../../../../../docs/recipes/sending-a-daily-summary-of-activity/en.md)
