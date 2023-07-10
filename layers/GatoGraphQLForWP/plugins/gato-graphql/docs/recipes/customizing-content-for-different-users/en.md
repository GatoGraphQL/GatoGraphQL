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

This query finds out if the logged-in user is an admin, by querying the roles and checking if `"administrator"` is one of them, and exports this condition under dynamic variable `$isAdminUser`:

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

The **PHP Functions Via Schema** extension provides the most common PHP functions as global fields, including:

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

These global fields are useful to manipulate the field value to check if it satisfies a condition (as with `_inArray`), format fields into the expected output (as with `_sprintf`), and others.

</div>
