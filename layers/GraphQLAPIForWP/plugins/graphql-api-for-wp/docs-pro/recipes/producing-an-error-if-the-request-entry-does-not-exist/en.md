# Producing an error if the requested entry does not exist

fail-if-post-not-exists.gql

```graphql
query GetPost($id: ID!) {
  post(by:{id: $id}) {
    id
    title
  }
  _notNull(value: $__post) @export(as: "postExists")
}

query FailIfPostNotExists($id: ID!)
  @skip(if: $postExists)
  @depends(on: "GetPost")
{
  errorMessage: _sprintf(
    string: "There is no post with ID '%s'",
    values: [$id]
  ) @remove
  _fail(
    message: $__errorMessage
    data: {
      id: $id
    }
  ) @remove
}
```

var

```json
{
    "id": 8888888888
}
```

fail-if-post-not-exists-via-directive.gql

```graphql
query GetPost($id: ID!) {
  original: post(by:{id: $id}) {
    id
    title
  }

  post(by:{id: $id})
    @fail(
      message: "There is no post with the provided ID"
      data: {
        id: $id
      }
    )
  {
    id
    title
  }
}
```

var:

```json
{
    "id": 8888888888
}
```
