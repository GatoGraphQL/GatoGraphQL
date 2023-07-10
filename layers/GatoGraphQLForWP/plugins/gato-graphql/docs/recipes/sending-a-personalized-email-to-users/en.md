# Sending a personalized email to users

Add "Config alert"



`_sendEmail` is a global field (or, more precisely, a global mutation). This means that, if **Nested Mutations** are enabled, this mutations can be executed on any type from the GraphQL schema (i.e. not only in `MutationRoot`).

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

Combined with features from other extensions (in this case, [**Field to Input**](https://gatographql.com/extensions/field-to-input/) and [**PHP Functions Via Schema**](https://gatographql.com/extensions/php-functions-via-schema/)), we can create personalized messages for every user:

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
