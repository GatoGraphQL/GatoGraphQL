# Global ID Field

Addition of a `globalID` field to all types in the GraphQL schema, which provides a unique ID for every entity across all types.

The value returned by this field is the base-64-encoded composition of:

- The entity's namespaced type name
- The separator `:`
- The entity's ID

## Description

All types in the GraphQL schema corresponding to the WordPress data model, such as `Post` and `User`, offer the `id` field, which returns the ID for the entity in WordPress.

For instance, in this query:

```graphql
{
    posts {
        id
    }

    users {
        id
    }
}
```

...we may obtain this response:

```json
{
    "data": {
        "posts": [
            {
                "id": 1
            },
            {
                "id": 2
            },
            {
                "id": 3
            }
        ],
        "users": [
            {
                "id": 1
            }
        ]
    }
}
```

While posts will always have a different ID from each other, they may share the same ID a user (or another entity type), as can be seen in the example above where both a post and a user have ID `1`.

If a unique ID is required for all entities across all types in the GraphQL schema (for instance, when using a GraphQL client that caches the response), then we can use the `globalID` field instead:

```graphql
{
    posts {
        id
        globalID
    }

    users {
        id
        globalID
    }
}
```

...which will produce a unique ID for all posts and users:

```json
{
    "data": {
        "posts": [
            {
                "id": 1,
                "globalID": "Post:1"
            },
            {
                "id": 2,
                "globalID": "Post:2"
            },
            {
                "id": 3,
                "globalID": "Post:3"
            }
        ],
        "users": [
            {
                "id": 1,
                "globalID": "User:1"
            }
        ]
    }
}
```
