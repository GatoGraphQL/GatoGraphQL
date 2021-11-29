# Schema Self Fields

Add "self" fields to the GraphQL schema, which can help give a particular shape to the GraphQL response.

## How it works

Sometimes we need to modify the shape of the response, to emulate the same response from another GraphQL server, or from the REST API.

We can do this via the `self` field, added to all types in the GraphQL schema, which echoes back the same object where it is applied:

```graphql
type QueryRoot {
  self: QueryRoot!
}

type Post {
  self: Post!
}

type User {
  self: User!
}
```

The `self` field allows to append extra levels to the query without leaving the queried object. Running this query:

```graphql
{
  __typename
  self {
    __typename
  }
  
  post(by: { id: 1 }) {
    self {
      id
      __typename
    }
  }
  
  user(by: { id: 1 }) {
    self {
      id
      __typename
    }
  }
}
```

...produces this response:

```json
{
  "data": {
    "__typename": "QueryRoot",
    "self": {
      "__typename": "QueryRoot"
    },
    "post": {
      "self": {
        "id": 1,
        "__typename": "Post"
      }
    },
    "user": {
      "self": {
        "id": 1,
        "__typename": "User"
      }
    }
  }
}
```

## How to use

Use `self` to artificially append the extra levels needed for the response, and field aliases to rename those levels appropriately:

```graphql
{
  categories: self {
    edges: postCategories {
      node: self {
        name
        slug
      }
    }
  }
}
```
