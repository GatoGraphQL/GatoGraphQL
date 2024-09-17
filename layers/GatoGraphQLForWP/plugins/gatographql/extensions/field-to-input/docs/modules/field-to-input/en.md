# Field To Input

Retrieve the value of a field, manipulate it, and input it into another field or directive, all within the same operation.

Pass the value of field `field` as input to another field via `$__field`, and as input to a directive via `field @passOnwards(as: "variableName")`.

## `$__field`

Pass the field value as input to another field. The syntax to reference the field value is: `$` (i.e. the symbol for a variable in GraphQL), followed by `__` and the field alias or name.

For instance, the value from field `excerpt` is referenced as `$__excerpt`, and `postTitle: title` is referenced as `$__postTitle`.

The response from the second field can itself be used as input to another field:

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

<!-- The field can only be referenced by any of its previous sibling fields in the same node. The following queries will NOT work:

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

The field also cannot be referenced from a directive argument (for that, use `@passOnwards`):

```graphql
# This will fail because the reference can be only used as input to a field, not to a directive
{
  posts {
    hasComments
    title @include(if: $__hasComments)
  }
}
``` -->

## `@passOnwards`

Directive `@passOnwards` makes the field's resolved value available to subsequent directives via a dynamic variable.

In the query below, field `notHasComments` is composed by obtaining the value from field `hasComments` and calculating its opposite value. This works by:

- Making the field's value available via `@passOnwards`; the field's value can then be input into any subsequent directive
- `@applyField` takes the input (exported under dynamic variable `$postHasComments`), applies the global field `not` into it, and stores the result back into the field

```graphql
{
  posts {
    id
    hasComments
    notHasComments: hasComments
      @passOnwards(as: "postHasComments")
      @applyField(
        name: "_not"
        arguments: {
          value: $postHasComments
        },
        setResultInResponse: true
      )
  }
}
```

This will produce:

```json
{
  "data": {
    "posts": [
      {
        "id": 1724,
        "hasComments": true,
        "notHasComments": false
      },
      {
        "id": 358,
        "hasComments": false,
        "notHasComments": true
      },
      {
        "id": 555,
        "hasComments": false,
        "notHasComments": true
      }
    ]
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

---

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

---

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

---

Send a newsletter defining the `to` and `from` emails through the `optionValue` field:

```graphql
mutation {
  fromEmail: optionValue(name: "admin_email")
  toEmail: optionValue(name: "subscribers_email_list_recipient_address")
  _sendEmail(
    from: {
      email: $__fromEmail
    }
    to: $__toEmail
    subject: "Weekly summary"
    messageAs: {
      html: "..."
    }
  )
}
```

---

Execute conditional operations based on the value of the field. In this query, users `"Leo"` and `"Peter"` get their names converted to upper case, since they are in the "special user" array, while `"Martin"` does not:

```graphql
query {
  users {
    name
      @passOnwards(as: "userName")
      @applyField(
        name: "_inArray"
        arguments: {
          value: $userName
          array: ["Leo", "John", "Peter"]
        }
        passOnwardsAs: "isSpecialUser"
      )
      @if(
        condition: $isSpecialUser
      )
        @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "users": [
      {
        "name": "LEO"
      },
      {
        "name": "Martin"
      },
      {
        "name": "PETER"
      }
    ]
  }
}
```
<!-- 
## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-feature-bundled-extensions/docs/modules/all-feature-bundled-extensions/en.md)
- [“Caching” Bundle](../../../../../bundle-extensions/caching/docs/modules/caching/en.md)
- [“Easy WordPress Bulk Transform & Update” Bundle](../../../../../bundle-extensions/custom-endpoints/docs/modules/custom-endpoints/en.md)
- [“Private GraphQL Server for WordPress” Bundle](../../../../../bundle-extensions/private-graphql-server-for-wordpress/docs/modules/private-graphql-server-for-wordpress/en.md)
- [“Responsible WordPress Public API” Bundle](../../../../../bundle-extensions/responsible-wordpress-public-api/docs/modules/responsible-wordpress-public-api/en.md)
- [“Selective Content Import, Export & Sync for WordPress” Bundle](../../../../../bundle-extensions/selective-content-import-export-and-sync-for-wordpress/docs/modules/selective-content-import-export-and-sync-for-wordpress/en.md)
- [“Simplest WordPress Content Translation” Bundle](../../../../../bundle-extensions/simplest-wordpress-content-translation/docs/modules/simplest-wordpress-content-translation/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md)
- [“Unhindered WordPress Email Notifications” Bundle](../../../../../bundle-extensions/unhindered-wordpress-email-notifications/docs/modules/unhindered-wordpress-email-notifications/en.md)
- [“Versatile WordPress Request API” Bundle](../../../../../bundle-extensions/versatile-wordpress-request-api/docs/modules/versatile-wordpress-request-api/en.md) -->
