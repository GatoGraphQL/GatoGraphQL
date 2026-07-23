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
