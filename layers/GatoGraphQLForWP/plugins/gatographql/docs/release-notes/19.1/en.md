# Release Notes: 19.1

## Added

The schema can now manage users, delete entities, and moderate comments.

### Managing users

New mutations to create, update and delete users ([#3362](https://github.com/GatoGraphQL/GatoGraphQL/pull/3362)):

- On the `Root` type: `createUser`, `updateUser` and `deleteUser`, and their bulk versions `createUsers`, `updateUsers` and `deleteUsers`.
- On the `User` type (for nested mutations): the `update` and `delete` fields.
- `createUser` and `updateUser` (and their nested and bulk versions) accept a `meta` input to set the user's custom meta.
- The "payload types for mutations" schema configuration (global setting and per-endpoint schema config) applies to these mutations.

Every failure mode is returned as a typed error payload (missing capability, user does not exist, username/email already exists, role does not exist, reassign-user does not exist, and cannot-delete-yourself). Capabilities follow WordPress core: `create_users` to create, `edit_users`/`edit_user` to update, `delete_users`/`delete_user` to delete, and `promote_users` to assign roles. These mutations run on single-site installs.

```graphql
mutation {
  createUser(input: { username: "newuser", email: "newuser@example.com", roles: ["editor"] }) {
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

### Deleting entities

New mutations to delete posts, pages, custom posts, media items and menus ([#3358](https://github.com/GatoGraphQL/GatoGraphQL/pull/3358)):

- On the `Root` type: `deletePost`, `deletePage`, `deleteCustomPost`, `deleteMediaItem`, `deleteMenu` and `deleteComment`, and their bulk versions (`deletePosts`, and so on).
- On the entity types (for nested mutations): the `delete` field on `Post`, `Page`, `GenericCustomPost`, `Media`, `Menu` and `Comment`.

The entity is sent to the trash by default, and permanently deleted when providing `true` in the `force` input:

```graphql
mutation {
  deletePost(input: { id: 1, force: true }) {
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

Menus are always deleted permanently, as WordPress stores them as taxonomy terms, which have no trash. Media items also require `force: true` unless the `MEDIA_TRASH` constant is enabled, as WordPress does not send attachments to the trash by default.

The user must be logged-in and have the capability to delete the entity, which WordPress resolves taking into account the entity's ownership and status.

### Updating and moderating comments

New mutations `updateComment` and `updateComments` on the `Root` type, and the nested `update` field on the `Comment` type ([#3358](https://github.com/GatoGraphQL/GatoGraphQL/pull/3358)).

The comment is moderated via the `status` input, which can approve it, hold it for moderation, mark it as spam, or send it to the trash. All input fields are optional: only the provided ones are applied.

```graphql
mutation {
  updateComment(input: { id: 1, status: spam }) {
    status
    comment {
      id
      status
    }
  }
}
```

### Querying the site's options

The Settings module gains two new fields to query the site's options (i.e. the settings, stored in table `wp_options`) in bulk ([#3364](https://github.com/GatoGraphQL/GatoGraphQL/pull/3364)):

- `optionNames: [String!]!` returns the list of the allowed option names stored in the DB. It accepts a `filterBy` input to include or exclude the names containing some string.
- `options(names: [String!]!): JSONObject` returns a JSON object, with the option name as key and the option value as value, for the provided (allowed) option names.

As with the existing `optionValue` field, only the options explicitly configured as accessible can be queried; any option that cannot be accessed is omitted from `optionNames`, and providing it to `options` returns an error.

```graphql
{
  optionNames(filterBy: { include: "blog" })
  options(names: $__optionNames)
}
```

### Filtering custom posts by parent

The `customPosts` query can now be filtered by the parent custom post, using the same inputs already available on the `pages` query ([#3366](https://github.com/GatoGraphQL/GatoGraphQL/pull/3366)):

- `parentID: ID` returns the custom posts with the given parent (`'0'` means "no parent").
- `parentIDs: [ID!]` returns the custom posts with any of the given parents.
- `excludeParentIDs: [ID!]` excludes the custom posts with any of the given parents.

```graphql
{
  customPosts(filter: { parentIDs: [1, 5] }) {
    id
    ... on CustomPost {
      title
    }
  }
}
```

## Improvements

- The collapsible "Show details" descriptions in the plugin settings are now printed using the native HTML `<details>`/`<summary>` elements, instead of a JavaScript-driven show/hide link ([#3368](https://github.com/GatoGraphQL/GatoGraphQL/pull/3368))
- Updated WooCommerce docs with mutations ([#3356](https://github.com/GatoGraphQL/GatoGraphQL/pull/3356))

## Fixes

- The `defaultValue` of input values (field and directive arguments, and input object fields) in the introspection query is now encoded using the GraphQL language, as required by the GraphQL spec, and not as JSON. Enum default values were previously quoted as strings (such as `["approve"]` instead of `[approve]`), so GraphQL clients (such as GraphiQL) discarded the default value and, for non-nullable arguments, wrongly reported that the argument is required.
- Corrected the `parentID` and `parentIDs` filter input descriptions (on the custom posts and pages filters), which had the singular/plural wording swapped.
- The "payload types for mutations" schema configuration (its "Do not use payload types for mutations" and "Use payload types for mutations, and add fields to query those payload objects" options) now also applies to the meta, taxonomy term (category and tag) and menu mutations. Previously only the post, page, custom post, media, comment and user-state mutations honored this setting, so meta/taxonomy/menu mutations always used payload types and never exposed their `...MutationPayloadObjects` query fields.
- Made the ordering of taxonomy terms (such as categories and tags) deterministic by adding a stable secondary sort by term ID, so that terms sharing the same primary sort value (such as a duplicate name) are always returned in a consistent order when sorting and paginating.
