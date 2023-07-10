# Customizing content for different users

We can retrieve a different response in a field depending on some piece of queried data, such as the roles of the logged-in user.

## GraphQL query to customize content for different users

This GraphQL query retrieves the post content, appending an "edit post" link at the bottom of the content for the admin user only:

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
    wpAdminEditURL @remove
    originalContent: content @remove
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

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Field `_inArray` is available through the **PHP Functions Via Schema** extension, which provides the most common PHP functions as global fields, including:

- `_arrayItem`
- `_equals`
- `_inArray`
- `_intAdd`
- `_isEmpty`
- `_isNull`
- `_objectProperty`
- `_sprintf`
- `_strContains`
- `_strRegexReplace`
- `_strSubstr`
- And many more

</div>

### Conditional execution of operations

When **Multiple Query Execution** is enabled, directives `@include` and `@skip` can also be applied to operations. This way, we can execute an operation or not depending on the value of some dynamic variable.

In the query below, only one of the two operations will be executed:

- `RetrieveContentForAdminUser` is executed only when `$isAdminUser` is `true`
- `RetrieveContentForNonAdminUser` is executed only when `$isAdminUser` is `false`

```graphql
query RetrieveContentForAdminUser($postId: ID!)
  @depends(on: "ExportConditionalVariables")
  @include(if: $isAdminUser)
{
  # ...
}

query RetrieveContentForNonAdminUser($postId: ID!)
  @depends(on: "ExportConditionalVariables")
  @skip(if: $isAdminUser)
{
  # ...
}
```

In our case, if the user is an admin, we want to provide a different value for the post's `content` field. While the second operation retrieves this field directly, the first operation uses `content` as an alias, and computes its value dynamically:

```graphql
query RetrieveContentForAdminUser($postId: ID!)
  @depends(on: "ExportConditionalVariables")
  @include(if: $isAdminUser)
{
  post(by: { id : $postId }) {
    wpAdminEditURL
    originalContent: content
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
