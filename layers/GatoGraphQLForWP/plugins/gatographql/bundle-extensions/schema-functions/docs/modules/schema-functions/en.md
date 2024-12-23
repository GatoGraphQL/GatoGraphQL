# Schema Functions

The GraphQL schema is provided with fields and directives which expose functionalities from the PHP programming language.

---

With **Function Fields**, the GraphQL schema is provided with "function" fields, which are useful for manipulating the data once it has been retrieved, allowing us to transform a field value in whatever way it is required, and granting us powerful data import/export capabilities.

Function fields are global fields, hence they are added to every single type in the GraphQL schema: in `QueryRoot`, but also in `Post`, `User`, etc.

For instance, while we have a `Post.hasComments` fields, we may need the opposite value. Instead of creating a new field `Post.notHasComments` (for which we'd need to edit PHP code), we can use the Field to Input feature to input the value from `hasComments` into a `not` field, thus calculating the new value always within the GraphQL query:

```graphql
query {
  posts {
    id
    hasComments
    notHasComments: _not(value: $__hasComments)
  }
}
```

---

With **Function Directives**, the GraphQL schema is provided with directives useful for manipulating the data once it has been retrieved, allowing us to transform a field value in whatever way it is required.

For instance, this query:

```graphql
query {
  posts {
    title @strUpperCase
  }
}
```

...will produce this response:

```json
{
  "data": {
    "posts": [
      {
        "title": "HELLO WORLD!"
      },
      {
        "title": "LOVELY WEATHER"
      }
    ]
  }
}
```

---

With **Helper Fields**, the GraphQL schema is added fields which provide commonly-used helper functionality.

Helper fields are global fields, hence they are added to every single type in the GraphQL schema: in `QueryRoot`, but also in `Post`, `User`, etc.

In this query, we retrieve the IDs of the users in the website and execute a new GraphQL query passing their ID as parameter:

```graphql
query {
  users {
    userID: id
    url: _urlAddParams(
      url: "https://somewebsite/endpoint/user-data",
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

---

With **Environment Fields**, the GraphQL schema is provided with global field `_env`, which allows to obtain a value from an environment variable, or from a PHP constant (most commonly defined in `wp-config.php`, but can also be defined elsewhere).

This query retrieves the environment constant `GITHUB_ACCESS_TOKEN` which we might set-up to access a private repository in GitHub:

```graphql
query {
  githubAccessToken: _env(name: "GITHUB_ACCESS_TOKEN")
}
```

---

With **Email Sender**, the GraphQL schema is provided with global mutation `_sendEmail`.

Mutation `_sendEmail` sends emails by executing WordPress `wp_mail` function. As a result, it will use the configuration defined for sending emails in WordPress (such as the SMTP provider to use).

The email can be sent with content types "text" or "HTML", depending on the value of the `messageAs` input (which is a "oneof" InputObject, so that only one of its properties can be provided).

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


<!-- ## List of bundled extensions

- [Email Sender](../../../../../extensions/email-sender/docs/modules/email-sender/en.md)
- [Helper Function Collection](../../../../../extensions/helper-function-collection/docs/modules/helper-function-collection/en.md)
- [HTTP Client](../../../../../extensions/http-client/docs/modules/http-client/en.md)
- [HTTP Request via Schema](../../../../../extensions/http-request-via-schema/docs/modules/http-request-via-schema/en.md)
- [PHP Constants and Environment via Schema](../../../../../extensions/php-constants-and-environment-variables-via-schema/docs/modules/php-constants-and-environment-variables-via-schema/en.md)
- [PHP Functions via Schema](../../../../../extensions/php-functions-via-schema/docs/modules/php-functions-via-schema/en.md) -->
