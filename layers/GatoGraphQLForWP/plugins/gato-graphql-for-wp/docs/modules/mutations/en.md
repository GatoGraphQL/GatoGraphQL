# Mutations

Mutations are operations that have side effects, such as performing an insert or update of data in the database. The available mutation fields are those under the `MutationRoot` type (or some of the fields under `Root` when using nested mutations), and these can be executed in the GraphQL document via the operation type `mutation`:

```graphql
mutation {
  updatePost(input: {
    id: 5,
    title: "New title"
  }) {
    title
  }
}
```

The **Mutations** module acts as an upstream dependency for all modules containing mutations. This allows us to remove all mutations from the GraphQL schema simply by disabling this module.

## Returning a Payload Object or the Mutated Entity

Mutation fields can be configured to return either of these 2 different entities:

- A payload object type
- Directly the mutated entity

### Payload object type

A payload object type contains all the data concerning the mutation:

- The status of the mutation (success or failure)
- The errors (if any) using distinctive GraphQL types, or
- The successfully mutated entity

For instance, mutation `updatePost` returns an object of type `PostUpdateMutationPayload` (please notice that we still need to query its field `post` to retrieve the updated post entity):

```graphql
mutation UpdatePost {
  updatePost(input: {
    id: 1724,
    title: "New title",
    status: publish
  }) {
    # This is the status of the mutation: SUCCESS or FAILURE
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
      id
      title
      # This is the status of the post: publish, pending, trash, etc
      status
    }
  }
}
```

The payload object allows us to represent better the errors, even having a unique GraphQL type per kind of error. This allows us to present different reactions for different errors in the application, thus improving the user experience.

In the example above, the `PostUpdateMutationPayload` type contains field `errors`, which returns a list of `CustomPostUpdateMutationErrorPayloadUnion`. This is a union type which includes the list of all possible errors that can happen when modifying a custom post (to be queried via introspection field `__typename`):

- `CustomPostDoesNotExistErrorPayload`
- `GenericErrorPayload`
- `LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload`
- `LoggedInUserHasNoPermissionToEditCustomPostErrorPayload`
- `LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload`
- `UserIsNotLoggedInErrorPayload`

If the operation was successful, we will receive:

```json
{
  "data": {
    "updatePost": {
      "status": "SUCCESS",
      "errors": null,
      "post": {
        "id": 1724,
        "title": "Some title",
        "status": "publish"
      }
    }
  }
}
```

If the user is not logged in, we will receive:

```json
{
  "data": {
    "updatePost": {
      "status": "FAILURE",
      "errors": [
        {
          "__typename": "UserIsNotLoggedInErrorPayload",
          "message": "You must be logged in to create or update custom posts"
        }
      ],
      "post": null
    }
  }
}
```

If the user doesn't have the permission to edit posts, we will receive:

```json
{
  "data": {
    "updatePost": {
      "status": "FAILURE",
      "errors": [
        {
          "__typename": "LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload",
          "message": "Your user doesn't have permission for editing custom posts."
        }
      ],
      "post": null
    }
  }
}
```

As a consequence of all the additional `MutationPayload`, `MutationErrorPayloadUnion` and `ErrorPayload` types added, the GraphQL schema will have a bigger size:

![GraphQL schema with payload object types for mutations](../../images/mutations-using-payload-object-types.png "GraphQL schema with payload object types for mutations")

### Mutated entity

The mutation will directly return the mutated entity in case of success, or <code>null</code> in case of failure, and any error message will be displayed in the JSON response's top-level <code>errors</code> entry.

For instance, mutation `updatePost` will return the object of type `Post`:

```graphql
mutation UpdatePost {
  updatePost(input: {
    id: 1724,
    title: "New title",
    status: publish
  }) {
    id
    title
    status
  }
}
```

If the operation was successful, we will receive:

```json
{
  "data": {
    "updatePost": {
      "id": 1724,
      "title": "Some title",
      "status": "publish"
    }
  }
}
```

In case of errors, these will appear under the `errors` entry of the response. For instance, if the user is not logged in, we will receive:

```json
{
    "errors": [
      {
        "message": "You must be logged in to create or update custom posts'",
        "locations": [
          {
            "line": 2,
            "column": 3
          }
        ]
      }
  ],
  "data": {
    "updatePost": null
  }
}
```

We must notice that, as a result, the top-level `errors` entry will contain not only syntax, schema validation and logic errors (eg: not passing a field argument's name, requesting a non-existing field, or calling `_sendHTTPRequest` and the network is down respectively), but also "content validation" errors (eg: "you're not authorized to modify this post").

Because there are no additional types added, the GraphQL schema will look leaner:

![GraphQL schema without payload object types for mutations](../../images/mutations-not-using-payload-object-types.png "GraphQL schema without payload object types for mutations")

### Configuration

Using payload object types for mutations in the schema can be configured as follows, in order of priority:

✅ Specific mode for the custom endpoint or persisted query, defined in the schema configuration

![Defining if to use payload object types for mutations, set in the Schema configuration](../../images/schema-configuration-payload-object-types-for-mutations.png "Defining if to use payload object types for mutations, set in the Schema configuration")

✅ Default mode, defined in the Settings

If the schema configuration has value `"Default"`, it will use the mode defined in the Settings:

![Defining if to use payload object types for mutations, in the Settings](../../images/settings-payload-object-types-for-mutations-default.png "Defining if to use payload object types for mutations, in the Settings")

#### Payload object types for mutations in the Admin clients

In the Settings, we can select to use payload object types for mutations in the wp-admin's GraphiQL and Interactive Schema clients:

![Defining if to use payload object types for mutations in the admin clients, in the Settings](../../images/settings-payload-object-types-for-mutations-for-admin.png "Defining if to use payload object types for mutations in the admin clients, in the Settings")
