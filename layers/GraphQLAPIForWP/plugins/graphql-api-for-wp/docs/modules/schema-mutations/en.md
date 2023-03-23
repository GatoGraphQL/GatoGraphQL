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

