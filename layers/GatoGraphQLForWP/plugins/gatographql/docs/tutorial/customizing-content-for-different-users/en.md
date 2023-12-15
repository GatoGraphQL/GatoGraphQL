# Customizing content for different users

We can retrieve a different response in a field depending on some piece of queried data, such as the roles of the logged-in user.

## GraphQL query to customize content for different users

This GraphQL query retrieves the post content, appending an "Edit this post" link at the bottom of the content for the admin user only:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  isAdminUser: _echo(value: false)
    @export(as: "isAdminUser")
    @remove
}

query ExportConditionalVariables
  @depends(on: "InitializeDynamicVariables")
{
  me {
    roleNames @remove
    isAdminUser: _inArray(
      value: "administrator",
      array: $__roleNames
    )
      @export(as: "isAdminUser")
  }
}

query RetrieveContentForAdminUser($postId: ID!)
  @depends(on: "ExportConditionalVariables")
  @include(if: $isAdminUser)
{
  post(by: { id : $postId }) {
    originalContent: content @remove
    wpAdminEditURL @remove
    content: _sprintf(
      string: "%s<p><a href=\"%s\">%s</a></p>",
      values: [
        $__originalContent,
        $__wpAdminEditURL,
        "(Admin only) Edit post"
      ]
    )
  }
}

query RetrieveContentForNonAdminUser($postId: ID!)
  @depends(on: "ExportConditionalVariables")
  @skip(if: $isAdminUser)
{
  post(by: { id : $postId }) {
    content
  }
}

query ExecuteAll
  @depends(on: [
    "RetrieveContentForAdminUser",
    "RetrieveContentForNonAdminUser"
  ])
{
  id @remove
}
```

For admin users, the response will be:

```json
{
  "data": {
    "user": {
      "isAdminUser": true
    },
    "post": {
      "content": "\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!<\/p>\n<p><a href=\"https:\/\/mysite.com\/wp-admin\/post.php?post=1&amp;action=edit\">(Admin only) Edit post<\/a><\/p>"
    }
  }
}
```

For non-admin users, the response will be:

```json
{
  "data": {
    "user": {
      "isAdminUser": false
    },
    "post": {
      "content": "\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!<\/p>\n"
    }
  }
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Having the GraphQL server (given all the possible multiple conditions) dynamically compute the required value for a field:

- Simplifies the logic of the application, as there is a single source of truth, the code becomes DRY, and clients don't need to implement the corresponding logic anymore
- Makes the application more reliable, specially when multiple clients access data from the server, as different implementations of the same logic can be non-identical, potentially leading to bugs (more so when clients are based on different technologies, such as JavaScript for a website, Java for an Android app, Swift for an iPhone app, and others)

</div>

## Step by step: creating the GraphQL query

Below is the detailed analysis of how the query works.

### Finding out if the user is an admin

This query checks if the logged-in user has the `"administrator"` role, and exports this condition under dynamic variable `$isAdminUser`:

```graphql
query
{
  me {
    roleNames
    isAdminUser: _inArray(
      value: "administrator",
      array: $__roleNames
    )
      @export(as: "isAdminUser")
  }
}
```

### Conditional execution of operations

When [**Multiple Query Execution**](https://gatographql.com/extensions/multiple-query-execution/) is enabled, directives `@include` and `@skip` can also be applied to operations. This way, we can execute an operation or not depending on the value of some dynamic variable.

In the query below, only one of the two operations will be executed:

- `RetrieveContentForAdminUser` is executed only when `$isAdminUser` is `true`
- `RetrieveContentForNonAdminUser` is executed only when `$isAdminUser` is `false`

```graphql
query RetrieveContentForAdminUser
  @depends(on: "ExportConditionalVariables")
  @include(if: $isAdminUser)
{
  # ...
}

query RetrieveContentForNonAdminUser
  @depends(on: "ExportConditionalVariables")
  @skip(if: $isAdminUser)
{
  # ...
}
```

Let's provide two different responses for the post's `content` field depending on the user being an admin or not:

- The first operation uses `content` as an alias, and computes the field's value dynamically, appending fields `originalContent` and `wpAdminEditURL` together via `_sprintf`
- The second operation retrieves the `content` field directly

```graphql
query RetrieveContentForAdminUser($postId: ID!)
  @depends(on: "ExportConditionalVariables")
  @include(if: $isAdminUser)
{
  post(by: { id : $postId }) {
    originalContent: content
    wpAdminEditURL
    content: _sprintf(
      string: "%s<p><a href=\"%s\">%s</a></p>",
      values: [
        $__originalContent,
        $__wpAdminEditURL,
        "(Admin only) Edit post"
      ]
    )
  }
}

query RetrieveContentForNonAdminUser($postId: ID!)
  @depends(on: "ExportConditionalVariables")
  @skip(if: $isAdminUser)
{
  post(by: { id : $postId }) {
    content
  }
}
```

### Adding the operation to be executed

Now we have two operations that may be executed, however we can provide only one `?operationName=...` when executing the query.

Then, we add operation `ExecuteAll` that depends on both `RetrieveContentForAdminUser` and `RetrieveContentForNonAdminUser`, containing the simple field `id` (because we must query something in the operation):

```graphql
query ExecuteAll
  @depends(on: [
    "RetrieveContentForAdminUser",
    "RetrieveContentForNonAdminUser"
  ])
{
  id
}
```

Invoking the endpoint with `?operationName=ExecuteAll` will now load both operations, however only one of them will be actually executed.

### Removing unneeded data

The final step is to remove all fields that are auxiliary (and as such we don't need to print their output in the response) via `@remove`.

The consolidated GraphQL query is:

```graphql
query InitializeDynamicVariables
  @configureWarningsOnExportingDuplicateVariable(enabled: false)
{
  isAdminUser: _echo(value: false)
    @export(as: "isAdminUser")
    @remove
}

query ExportConditionalVariables
  @depends(on: "InitializeDynamicVariables")
{
  me {
    roleNames @remove
    isAdminUser: _inArray(
      value: "administrator",
      array: $__roleNames
    )
      @export(as: "isAdminUser")
  }
}

query RetrieveContentForAdminUser($postId: ID!)
  @depends(on: "ExportConditionalVariables")
  @include(if: $isAdminUser)
{
  post(by: { id : $postId }) {
    originalContent: content @remove
    wpAdminEditURL @remove
    content: _sprintf(
      string: "%s<p><a href=\"%s\">%s</a></p>",
      values: [
        $__originalContent,
        $__wpAdminEditURL,
        "(Admin only) Edit post"
      ]
    )
  }
}

query RetrieveContentForNonAdminUser($postId: ID!)
  @depends(on: "ExportConditionalVariables")
  @skip(if: $isAdminUser)
{
  post(by: { id : $postId }) {
    content
  }
}

query ExecuteAll
  @depends(on: [
    "RetrieveContentForAdminUser",
    "RetrieveContentForNonAdminUser"
  ])
{
  id @remove
}
```
