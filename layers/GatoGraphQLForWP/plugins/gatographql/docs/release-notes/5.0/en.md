# Release Notes: 5.0

## Breaking changes

- Bumped minimum WordPress version to 6.1 ([#2811](https://github.com/GatoGraphQL/GatoGraphQL/pull/2811))

### Return no results when filtering data by an empty array ([#2809](https://github.com/GatoGraphQL/GatoGraphQL/pull/2809))

This GraphQL query filter posts by ID:

```graphql
query FilterPostsByIDs(
  $ids: [ID!]
) {
  posts(filter: { ids: $ids }) {
    title
  }
}
```

Previously, when passing an empty array in variable `$ids`:

```json
{
  "ids": []
}
```

...input `filter.ids` would be ignored, and the field would then return all results.

Now, passing an empty array means "retrieve no results".

To ignore the filter input, pass `null` instead.

The same behavior applies for all fields that accept the `filter.ids` input:

- `categories`
- `comments`
- `customPosts`
- `tags`
- `users`
- etc

## Improvements

- Increase limit of chars in truncated response by Guzzle ([#2800](https://github.com/GatoGraphQL/GatoGraphQL/pull/2800))
- Added field `isGutenbergEditorEnabled` ([#2801](https://github.com/GatoGraphQL/GatoGraphQL/pull/2801))
- Use `isGutenbergEditorEnabled` in predefined persisted queries ([#2802](https://github.com/GatoGraphQL/GatoGraphQL/pull/2802))
- Added mutations to assign custom tags/categories to custom posts ([#2803](https://github.com/GatoGraphQL/GatoGraphQL/pull/2803))
- Support additional taxonomies for mutations on post tags/categories (not only `post_tag` and `category`) ([#2823](https://github.com/GatoGraphQL/GatoGraphQL/pull/2823))
- Added taxonomy field also to `PostTag` and `PostCategory` types ([#2824](https://github.com/GatoGraphQL/GatoGraphQL/pull/2824))

### Added Settings option to enable/disable logs ([#2813](https://github.com/GatoGraphQL/GatoGraphQL/pull/2813))

The complete GraphQL response for specific items (eg: when doing automation in PRO) can be logged under file `wp-content/gatographql/logs/info.log`.

A new option **Enable Logs?** in **Settings > Plugin Configuration > General** has been added, to enable printing these logs (it is `false` by default):

<div class="img-width-1024" markdown=1>

![Enable Logs? option in Settings](https://github.com/user-attachments/assets/fc523bc0-ccec-4ff0-8d22-b4ccc81563bb "Enable Logs? option in Settings")

</div>

### Application password failed authentication: Show error in GraphQL response ([#2817](https://github.com/GatoGraphQL/GatoGraphQL/pull/2817))

If using Application passwords to authenticate the user against the GraphQL endpoint, and the authentication fails, the error message is now shown in the GraphQL response:

```json
{
  "errors": [
    {
      "message": "Application Password authentication error: The provided password is an invalid application password."
    }
  ],
  "data": {
    "me": null
  }
}
```

### Added predefined persisted queries

Several persisted queries have been added:

- [PRO] Import post from WordPress RSS feed and rewrite its content with ChatGPT ([#2818](https://github.com/GatoGraphQL/GatoGraphQL/pull/2818))
- [PRO] Import new posts from WordPress RSS feed ([#2819](https://github.com/GatoGraphQL/GatoGraphQL/pull/2819))
- [PRO] Import HTML from URLs as new posts in WordPress ([#2822](https://github.com/GatoGraphQL/GatoGraphQL/pull/2822))

## Fixed

- Add `featuredImage` field on `GenericCustomPost` ([#2806](https://github.com/GatoGraphQL/GatoGraphQL/pull/2806))
- On fields `blocks`, `blockDataItems`, and `blockFlattenedDataItems`, avoid error when post has no content ([#2814](https://github.com/GatoGraphQL/GatoGraphQL/pull/2814))

## [PRO] Improvements

### Updated mapped WordPress hooks for automation

The WordPress hooks mapped for catching them with Automation rules have been updated, as follows:

| Source WordPress hook | Triggered Gato GraphQL hook |
| --- | --- |
| [`{$old_status}_to_{$new_status}`](https://developer.wordpress.org/reference/hooks/old_status_to_new_status/)<br/><em>(Passing `WP_Post $post`)</em> | `gatographql:any_to_{$new_status}`<br/>`gatographql:{$old_status}_to_any`<br/>`gatographql:{$old_status}_to_{$new_status}:{$post_type}`<br/>`gatographql:any_to_{$new_status}:{$post_type}`<br/>`gatographql:{$old_status}_to_any:{$post_type}`<br/><em>(All passing `int $postId, string $postType`)</em> |
| [`created_term`](https://developer.wordpress.org/reference/hooks/created_term/) | `gatographql:created_term:{$taxonomy}` |
| [`set_object_terms`](https://developer.wordpress.org/reference/hooks/set_object_terms/) | `gatographql:set_object_terms:{$taxonomy}`<br/>`gatographql:updated_object_terms:{$taxonomy}` <em>(When there is a delta between old and new terms)</em> |
| [`added_post_meta`](https://developer.wordpress.org/reference/hooks/added_meta_type_meta/) | `gatographql:added_post_meta:{$meta_key}`<br/>`gatographql:added_post_meta:{$post_type}:{$meta_key}` <em>(Also passing `string $post_type` as 5th param)</em> |
| [`updated_post_meta`](https://developer.wordpress.org/reference/hooks/updated_meta_type_meta/) | `gatographql:updated_post_meta:{$meta_key}`<br/>`gatographql:updated_post_meta:{$post_type}:{$meta_key}` <em>(Also passing `string $post_type` as 5th param)</em> |
| [`deleted_post_meta`](https://developer.wordpress.org/reference/hooks/deleted_meta_type_meta/) | `gatographql:deleted_post_meta:{$meta_key}`<br/>`gatographql:deleted_post_meta:{$post_type}:{$meta_key}` <em>(Also passing `string $post_type` as 5th param)</em> |
| [`added_term_meta`](https://developer.wordpress.org/reference/hooks/added_meta_type_meta/) | `gatographql:added_term_meta:{$meta_key}`<br/>`gatographql:added_term_meta:{$taxonomy}:{$meta_key}` <em>(Also passing `string $taxonomy` as 5th param)</em> |
| [`updated_term_meta`](https://developer.wordpress.org/reference/hooks/updated_meta_type_meta/) | `gatographql:updated_term_meta:{$meta_key}`<br/>`gatographql:updated_term_meta:{$taxonomy}:{$meta_key}` <em>(Also passing `string $taxonomy` as 5th param)</em> |
| [`deleted_term_meta`](https://developer.wordpress.org/reference/hooks/deleted_meta_type_meta/) | `gatographql:deleted_term_meta:{$meta_key}`<br/>`gatographql:deleted_term_meta:{$taxonomy}:{$meta_key}` <em>(Also passing `string $taxonomy` as 5th param)</em> |
