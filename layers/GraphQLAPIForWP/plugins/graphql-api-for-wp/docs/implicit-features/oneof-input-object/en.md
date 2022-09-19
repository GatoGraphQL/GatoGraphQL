# OneOf Input Object

(This feature has been implemented in advance to its [approval for the GraphQL spec](https://github.com/graphql/graphql-spec/pull/825).)

OneOf Input Objects are a special variant of Input Objects where the type system asserts that exactly one of the fields must be set and non-null, all others being omitted.

## Description

The "oneof" input object is a particular type of input object, where exactly one of the input fields must be provided as input (or otherwise it returns a validation error). This behavior introduces polymorphism for inputs.

For instance, the field `QueryRoot.post` now receives a field argument `by`, which is a oneof input object allowing is to retrieve the post via different properties, such as by `id`:

```graphql
{
  post(
    by: {
      id: 1
    }
  ) {
    id
    title
  }
}
```

...or by `slug`:

```graphql
{
  post(
    by: {
      slug: "hello-world"
    }
  ) {
    id
    title
  }
}
```

The benefit is that a single field can then be used to tackle different use cases, so we can avoid creating a different field for each use case (such as `postByID`, `postBySlug`, etc), thus making the GraphQL schema leaner and more elegant.

## GraphQL spec

This functionality is not yet part of the GraphQL spec, but it is expected to be added soon:

- <a href="https://github.com/graphql/graphql-spec/pull/825" target="_blank">PR #825 - RFC: OneOf Input Objects</a>
