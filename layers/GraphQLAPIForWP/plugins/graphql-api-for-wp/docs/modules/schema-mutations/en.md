# Mutations

GraphQL mutations enable to modify data (i.e. perform side-effect) through the query.

The "Mutations" module acts as an upstream dependency for all modules containing mutations. This allows us to disable this module to remove all mutations from the GraphQL schema.

## Description

The query must use the operation type `mutation`:

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

The available mutation fields are those under the `MutationRoot` type.
