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
- The error messages (if any), or
- The successfully mutated entity

For instance, mutation `createPost` returns an object of type `RootCreatePostMutationPayload`:

```graphql
mutation CreatePost {
  createPost(input: {
    title: "Some title"
    content: "Some content"
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
      id
    }
  }
}
```

### Mutated entity

Directly the mutated entity in case of success or <code>null</code> in case of failure, and any error message will be displayed in the JSON response\'s top-level <code>errors</code> entry.