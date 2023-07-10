# Customizing content for different users

We can retrieve a different response in a field depending on the capabilities of the logged-in user.

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
    isAdminUser: _inArray(value: "administrator", array: $__roleNames)
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

### Retrieving the post data