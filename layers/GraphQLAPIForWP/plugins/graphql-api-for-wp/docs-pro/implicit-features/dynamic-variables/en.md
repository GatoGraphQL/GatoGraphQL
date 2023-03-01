# Dynamic Variables

Read the value of a variable that was created and had its value assigned dynamically, when resolving the GraphQL query

## Description

The behavior of variables in GraphQL (as [defined in the spec](https://spec.graphql.org/draft/#sec-Language.Variables)) is "static": the variable name and type must be declared in the declaration of the operation, and its value is provided via a "variables dictionary" in the payload of the GraphQL request.

For instance, in this query, we define variable `$postID` with type `ID`:

```graphql
query GetPost($postID: ID!) {
  post(id: $postID) {
    title
    content
  }
}
```

When sending the GraphQL request to the server, we must also provide the variable's value':

```json
{
  "postID": 1
}
```

"Dynamic" variables are different than "static" variables in that they do not need be defined in advance, but can be created on runtime, assigning a value from some resolved field in the same GraphQL query.

For instance, in the query below, every users has a meta value `"preferred-date-format"`. We can retrieve this value for the logged in user, export it under dynamic variable `$userPreferredDateFormat`, and then inject this variable into another field, `Post.dateStr`. Please notice how variable `$userPreferredDateFormat` does not need be defined in the operation `GetCustomizedPosts`, and its value is not provided in advance:

```graphql
query ExportLoggedInUserPreferredDateFormat {
  me {
    metaValue(key: "preferred-date-format")
      @export(as: "userPreferredDateFormat")
  }
}

query GetCustomizedPosts {
  posts {
    id
    title
    dateStr(format: $userPreferredDateFormat)
  }
}
```

Having support for dynamc variables gives us several benefits, including:

- Increased performance via **Multiple Query Execution**: Execute a first query, export its field value(s) via some dynamic variables, and input these into a second query which will be executed immediately, already on the same request
- Field value manipulation: By exporting the value from a field as a dynamic variable, it can be input into a **Function Field** (`_sprintf`, `_strSubstr`, `_not`, and many others), thus manipulating the field in the server before sending it back in the reponse

## Further examples

In the query below, dynamic variables are created on the first and second operations, and consumed in a third operation, thanks to **Multiple Query Execution**.

We first export the admin's name and email as stored in `wp_options`, then export the logged-in user's email, and finally consume these dynamic variables to compose and send an email:

```graphql
query ExportAdminNameAndEmail {
  adminName: optionValue(name: "admin_name")
    @export(as: "adminName")

  adminEmail: optionValue(name: "admin_email")
    @export(as: "adminEmail")
}

query ExportLoggedInUserEmail {
  me {
    email
      @export(as: "userEmail")
  }    
}

mutation SendWelcomeEmailToLoggedInUser {
  sendEmail(
    from: $adminName
    fromEmail: $adminEmail
    toEmail: $userEmail
    content: "Thanks for creating an account on our site!"
  ) {
    status
  }
}
```

In the query below, the dynamic variable is created and consumed in the same operation (and also within the resolution of a single field) to have the posts' titles shortened to not more than 150 chars.

To achieve this, the value of the field is exported to dynamic variable `$postTitle` (thanks to the **Pass Onwards Directive**), and this value is input to the **Function Field** `_strSubstr` (thanks to the **Apply Field Directive**):

```graphql
{
  posts {
    id
    shortenedTitle: title
      @passOnwards(as: "postTitle")
      @applyField(
        name: "_strSubstr"
        arguments: {
          string: $postTitle
          offset: 0
          length: 150
        },
        setResultInResponse: true
      )
  }
}
```

## GraphQL spec

This functionality is currently not part of the GraphQL spec, but it has been requested:

- <a href="https://github.com/graphql/graphql-spec/issues/583" target="_blank">Issue #583 - [RFC] Dynamic variable declaration</a>
