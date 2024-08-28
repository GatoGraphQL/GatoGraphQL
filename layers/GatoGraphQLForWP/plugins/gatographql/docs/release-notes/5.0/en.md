# Release Notes: 5.0

## Improvements

- Increase limit of chars in truncated response by Guzzle ([#2800](https://github.com/GatoGraphQL/GatoGraphQL/pull/2800))
- Added field `isGutenbergEditorEnabled` ([#2801](https://github.com/GatoGraphQL/GatoGraphQL/pull/2801))
- Use `isGutenbergEditorEnabled` in predefined persisted queries ([#2802](https://github.com/GatoGraphQL/GatoGraphQL/pull/2802))
- Added mutations to assign custom tags/categories to custom posts ([#2803](https://github.com/GatoGraphQL/GatoGraphQL/pull/2803))

## Fixed

- Add `featuredImage` field on `GenericCustomPost` ([#2806](https://github.com/GatoGraphQL/GatoGraphQL/pull/2806))

## [PRO] Improvements

### Updated mapped WordPress hooks for automation

The WordPress hooks mapped for catching them with Automation rules have been updated, as follows:

| Source WordPress hook | Triggered Gato GraphQL hook |
| --- | --- |
| [`{$old_status}_to_{$new_status}`](https://developer.wordpress.org/reference/hooks/old_status_to_new_status/)<br/><em>(Passing `WP_Post $post`)</em> | `gatographql:any_to_{$new_status}`<br/>`gatographql:{$old_status}_to_any`<br/>`gatographql:{$old_status}_to_{$new_status}:{$post_type}`<br/>`gatographql:any_to_{$new_status}:{$post_type}`<br/>`gatographql:{$old_status}_to_any:{$post_type}`<br/><em>(All passing `int $postId, string $postType`)</em> |
| [`set_object_terms`](https://developer.wordpress.org/reference/hooks/set_object_terms/) | `gatographql:set_object_terms:{$taxonomy}`<br/>`gatographql:updated_object_terms:{$taxonomy}` <em>(When there is a delta between old and new terms)</em> |
| [`added_post_meta`](https://developer.wordpress.org/reference/hooks/added_meta_type_meta/) | `gatographql:added_post_meta:{$meta_key}`<br/>`gatographql:added_post_meta:{$post_type}:{$meta_key}` |
| [`updated_post_meta`](https://developer.wordpress.org/reference/hooks/updated_meta_type_meta/) | `gatographql:updated_post_meta:{$meta_key}`<br/>`gatographql:updated_post_meta:{$post_type}:{$meta_key}` |
| [`deleted_post_meta`](https://developer.wordpress.org/reference/hooks/deleted_meta_type_meta/) | `gatographql:deleted_post_meta:{$meta_key}`<br/>`gatographql:deleted_post_meta:{$post_type}:{$meta_key}` |
| [`added_term_meta`](https://developer.wordpress.org/reference/hooks/added_meta_type_meta/) | `gatographql:added_term_meta:{$meta_key}`<br/>`gatographql:added_term_meta:{$taxonomy}:{$meta_key}` |
| [`updated_term_meta`](https://developer.wordpress.org/reference/hooks/updated_meta_type_meta/) | `gatographql:updated_term_meta:{$meta_key}`<br/>`gatographql:updated_term_meta:{$taxonomy}:{$meta_key}` |
| [`deleted_term_meta`](https://developer.wordpress.org/reference/hooks/deleted_meta_type_meta/) | `gatographql:deleted_term_meta:{$meta_key}`<br/>`gatographql:deleted_term_meta:{$taxonomy}:{$meta_key}` |
