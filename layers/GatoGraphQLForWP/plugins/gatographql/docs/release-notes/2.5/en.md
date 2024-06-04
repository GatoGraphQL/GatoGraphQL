# Release Notes: 2.5

## Improvements

### Added predefined persisted queries

- [PRO] Send email to users about post

### Added documentation for "WordPress hook mapping" for the [PRO] Automation extension ([#2691](https://github.com/GatoGraphQL/GatoGraphQL/pull/2691))

There are WordPress hooks which cannot be directly used in the Automation Configurator, because they provide a PHP object via the hook, which can't be input as a GraphQL variable.

Starting from `v2.5` of Gato GraphQL PRO, several of these hooks have been mapped, by triggering a new hook prepended with `gatographql:` and the same hook name, and passing the corresponding object ID as a variable, which can be input as a GraphQL variable.

For instance, WordPress hook `draft_to_publish` passes the `$post` as variable (of type `WP_Post`). Gato GraphQL PRO maps this hook as `gatographql:draft_to_publish`, and passes the `$postId` (of type `int`) as variable.
