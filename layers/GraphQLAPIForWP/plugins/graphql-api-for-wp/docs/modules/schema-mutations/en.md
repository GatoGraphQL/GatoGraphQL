# Mutations

GraphQL mutations enable to modify data (i.e. perform side-effects) through the query.

The "Mutations" module acts as an upstream dependency for all modules containing mutations. This allows us to remove all mutations from the GraphQL schema simply by disabling this module.

## Description

Mutations are operations that have side effects, such as performing an insert or update of data in the database. The available mutation fields are those under the `MutationRoot` type (or some of the fields under `Root` when using nested mutations), and these can be requested in the GraphQL document via the operation type `mutation`:

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

## Returning either a “Payload” Object or the Mutated Entity

Mutation fields can be configured to return either of these 2 different entities:

- A “payload” object type
- Directly the mutated entity

### “Payload” object type

A “payload” object type contains all the data concerning the mutation:

- The status of the mutation (success or failure)
- The errors (if any) as a distinctive GraphQL type, or
- The successfully mutated entity

For instance, mutation `createPost` returns an object of type `RootCreatePostMutationPayload`, and we still need to query its field `post` to retrieve the created post entity:

```graphql
mutation CreatePost {
  createPost(input: {
    title: "Some title"
    content: "Some content"
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
      content
      # This is the status of the post: publish, pending, trash, etc
      status
    }
  }
}
```

The “payload” object allows us to represent better the errors, even having a unique GraphQL type per kind of error. This allows us to present different reactions for different errors in the application, thus improving the user experience.

For instance, mutation `updatePost` returns a `PostUpdateMutationPayload`, whose field `errors` returns a list of `CustomPostUpdateMutationErrorPayloadUnion`. This is a union type which contains the list of all possible errors that can happen when modifying a custom post (to be queried via introspection field `__typename`):

- `CustomPostDoesNotExistErrorPayload`
- `GenericErrorPayload`
- `LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload`
- `LoggedInUserHasNoPermissionToEditCustomPostErrorPayload`
- `LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload`
- `UserIsNotLoggedInErrorPayload`

As a consequence of all the additional `MutationPayload`, `MutationErrorPayloadUnion` and `ErrorPayload` types added, the GraphQL schema will look bigger:



### Mutated entity

Directly the mutated entity in case of success or <code>null</code> in case of failure, and any error message will be displayed in the JSON response\'s top-level <code>errors</code> entry.

### Comparing the 2 methods

The main difference lies in that, 
