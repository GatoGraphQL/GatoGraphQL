# Sample recipe 4

<!-- (This feature has been implemented in advance to its [approval for the GraphQL spec](https://github.com/graphql/graphql-spec/pull/825).) -->

OneOf Input Objects are a special variant of Input Objects where the type system asserts that exactly one of the fields must be set and non-null, all others being omitted.

## Description

The "oneof" input object is a particular type of input object, where exactly one of the input fields must be provided as input (or otherwise it returns a validation error). This behavior introduces polymorphism for inputs.

The benefit is that a single field can then be used to tackle different use cases (such as the field `post` selecting posts by either ID or slug), so we can avoid creating a different field for each use case (such as `postByID`, `postBySlug`, etc), thus making the GraphQL schema leaner and more elegant.

For instance, the field `QueryRoot.post` receives a field argument `by`, which is a oneof input object allowing is to retrieve the post via different properties, including by `id`:

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

...and by `slug`:

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

It is mandatory to provide one, and only one, of the available properties. If we provide two or more, such as both `id` and `slug`:

```graphql
{
  post(
    by: {
      id: 1
      slug: "hello-world"
    }
  ) {
    id
    title
  }
}
```

...we will then get an error:

```json
{
  "errors": [
    {
      "message": "The oneof input object 'PostByInput' must be provided exactly one value, but 2 have been provided",
      "extensions": {
        "type": "QueryRoot",
        "field": "post(by:{id:1,slig:\"hello-world\"})",
        "argument": "by"
      }
    }
  ],
  "data": {
    "post": null
  }
}
```

## GraphQL spec

This functionality is not yet part of the GraphQL spec, but has been requested:

- <a href="https://github.com/graphql/graphql-spec/pull/825" target="_blank">PR #825 - RFC: OneOf Input Objects</a>
