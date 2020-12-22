# Remove if Null

Add directive `@removeIfNull` to remove the response from a field when its value is `null`. 

## How to use

In the query below, when a post does not have a featured image, field `featuredImage` is not added in the response:

```graphql
query {
  posts {
    title
    featuredImage @removeIfNull {
      src
    }
  }
}
```

## GraphQL spec

This functionality is currently not part of the GraphQL spec, but it has been requested:

- <a href="https://github.com/graphql/graphql-spec/issues/275#issuecomment-338538911" target="_blank">Issue #275 - @include(unless null) ?</a>
- <a href="https://github.com/graphql/graphql-spec/issues/766" target="_blank">Issue #766 - GraphQL query: skip value field if null</a>
