# User Mutations

Create, update and delete users

## Description

This module adds mutations to manage users to the GraphQL schema.

On the `Root` type:

- `createUser`, `updateUser` and `deleteUser`
- their bulk versions `createUsers`, `updateUsers` and `deleteUsers`

On the `User` type (for nested mutations):

- the `update` and `delete` fields

When creating a user, `username` and `email` are mandatory; the password is generated automatically when not provided. `username` cannot be changed afterwards, so it is not available when updating. Roles are assigned via the `roles` input (accepting more than one role).

The mutations enforce the corresponding WordPress capabilities: `create_users` to create, `edit_users` (or editing one's own profile) to update, `delete_users` to delete, and `promote_users` to assign roles. Every failure is returned as a typed error payload (missing permission, user does not exist, username/email already exists, role does not exist, and so on).

These mutations operate on single-site installations.

## Examples

### Creating a user

```graphql
mutation {
  createUser(input: {
    username: "newuser"
    email: "newuser@example.com"
    roles: ["editor", "author"]
    firstName: "New"
    lastName: "User"
    displayName: "New User"
    websiteURL: "https://example.com"
    description: "About the user"
    meta: {
      phone: ["555-1234"]
    }
  }) {
    status
    user {
      id
      name
      roles
    }
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
}
```

### Updating a user

`username` cannot be changed, so it is not accepted here.

```graphql
mutation {
  updateUser(input: {
    id: 5
    email: "updated@example.com"
    roles: ["administrator"]
    description: "Updated bio"
    meta: {
      phone: ["555-9999"]
    }
  }) {
    status
    user {
      id
      email
      roles
    }
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
}
```

### Deleting a user, reassigning their content

Pass `reassignAuthorContentTo` to transfer the deleted user's posts to another user (otherwise the content is trashed).

```graphql
mutation {
  deleteUser(input: {
    id: 5
    reassignAuthorContentTo: 1
  }) {
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
}
```

### Creating several users at once

The bulk mutations (`createUsers`, `updateUsers`, `deleteUsers`) receive a list of inputs and return a list of payloads:

```graphql
mutation {
  createUsers(inputs: [
    { username: "user1", email: "user1@example.com", roles: ["subscriber"] },
    { username: "user2", email: "user2@example.com", roles: ["subscriber"] }
  ]) {
    status
    user {
      id
      name
    }
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
  }
}
```

### Nested mutations on the `User` type

When the "Nested mutations" module is enabled, users can be updated or deleted from within the `User` type:

```graphql
mutation {
  user(by: { id: 5 }) {
    update(input: {
      displayName: "Updated via nested mutation"
    }) {
      status
      user {
        id
        displayName
      }
      errors {
        __typename
        ...on ErrorPayload {
          message
        }
      }
    }
  }
}
```
