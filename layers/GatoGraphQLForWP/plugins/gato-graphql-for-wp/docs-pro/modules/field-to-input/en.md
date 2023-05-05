# Field to Input

Retrieve the value of a field, manipulate it, and input it into another field, all within the same query.

## Description

The field to obtain the value from is referenced using the "Variable" syntax `$`, and `__` before the field alias or name. (For instance, the value from field `excerpt` is referenced as `$__excerpt`.) The response from the second field can itself be used as input to another field:

```graphql
{
  posts {
    excerpt

    # Referencing previous field with name "excerpt"
    isEmptyExcerpt: _isEmpty(value: $__excerpt)

    # Referencing previous field with alias "isEmptyExcerpt"
    isNotEmptyExcerpt: _not(value: $__isEmptyExcerpt)
  }
}
```

The response will be:

```json
{
  "data": {
    "posts": [
      {
        "excerpt": "Some post excerpt",
        "isEmptyExcerpt": false,
        "isNotEmptyExcerpt": true
      },
      {
        "excerpt": "",
        "isEmptyExcerpt": true,
        "isNotEmptyExcerpt": false
      }
    ]
  }
}
```

The field can only be referenced by any of its previous sibling fields in the same node. The following queries will NOT work:

```graphql
# This will fail because the reference to the field must appear after the field, not before
{
  posts {
    isEmptyExcerpt: _isEmpty(value: $__excerpt)
    excerpt
  }
}

# This will fail because the reference must be done within the same node
{
  posts {
    excerpt
  }
  isEmptyExcerpt: _isEmpty(value: $__excerpt)
}
```

The field also cannot be referenced from a directive argument (for that, use the **Pass Onwards Directive**):

```graphql
# This will fail because the reference can be only used as input to a field, not to a directive
{
  posts {
    hasComments
    title @include(if: $__hasComments)
  }
}
```

## Examples

If the post's excerpt is empty, use the title instead:

```graphql
{
  posts {
    title
    originalExcerpt: excerpt
    isEmptyExcerpt: _isEmpty(value: $__originalExcerpt)
    excerpt: _if(condition: $__isEmptyExcerpt, then: $__title, else: $__originalExcerpt)
  }
}
```

Retrieve data from an external REST endpoint, and manipulate its data to suit your requirements.

```graphql
{
  externalData: _sendJSONObjectItemHTTPRequest(input: { url: "https://example.com/rest/some-external-endpoint"} )
  userName: _objectProperty(object: $__externalData, by: { path: "data.user.name" })
  userLastName: _objectProperty(object: $__externalData, by: { path: "data.user.surname" })
}
```

This will produce:

```json
{
  "data": {
    "externalData": {
      "data": {
        "user": {
          "id": 1,
          "name": "Leo",
          "surname": "Loso"
        }
      }
    },
    "userName": "Leo",
    "userLastName": "Loso"
  }
}
```

Using the `@remove` directive on `externalData`, we can also avoid printing the external endpoint source data in the response:

```graphql
{
  externalData: _sendJSONObjectItemHTTPRequest(input: { url: "https://example.com/rest/some-external-endpoint" } ) @remove
  userName: _objectProperty(object: $__externalData, by: { path: "data.user.name" })
  userLastName: _objectProperty(object: $__externalData, by: { path: "data.user.surname" })
}
```

This will now produce:

```json
{
  "data": {
    "userName": "Leo",
    "userLastName": "Loso"
  }
}
```

Retrieve the posts for every user that mention the user's email:

```graphql
{
  users {
    email
    posts(filter: { search: $__email }) {
      id
      title
    }
  }
}
```

Send a newsletter defining the `to` and `from` emails through the `optionValue` field:

```graphql
mutation {
  fromEmail: optionValue(name: "admin_email")
  toEmail: optionValue(name: "subscribers_email_list_recipient_address")
  sendEmail(
    from: $__fromEmail
    to: $__toEmail
    subject: "Weekly summary"
    content: "..."
  )
}
```
