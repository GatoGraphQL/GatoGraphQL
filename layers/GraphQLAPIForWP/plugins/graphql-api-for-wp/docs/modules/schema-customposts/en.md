# Custom Posts

Base functionality for all custom posts

## Description

This module provides the basic schema functionality for custom posts, so it must also be enabled whenever any custom post entity (including posts, pages, or any Custom Post Type) must be added to the schema

In addition, it provides type `CustomPostUnion`, which is used whenever an entity can return custom posts.

![`CustomPostUnion` type](../../images/interactive-schema-custompost-union.png "`CustomPostUnion` type")

For instance, a comment can be added to a post, but also to a page and to a CPT, hence type `Comment` must indicate where the comment has been added through field `customPost` of type `CustomPostUnion` (not through field `post` of type `Post`).

![`Comment` type](../../images/interactive-schema-comment.png "`Comment` type")

## How to use

The different CPT modules can make their type be part of `CustomPostUnion` through their Settings.

For instance, type `Page` is added to `CustomPostUnion` under the Settings for `Schema Pages`:

![Settings for Schema Pages](../../images/settings-schema-pages.png "Settings for Schema Pages")

If there is only one type added to `CustomPostUnion`, we can then have the fields that resolve to `CustomPostUnion` be instead resolved to that unique type instead:

![Settings for Custom Posts](../../images/settings-customposts.png "Settings for Custom Posts")

For instance, if `Post` is the only type, field `customPosts` from type `Root` resolves to it directly:

![`customPosts` field resolves to `Post` type](../../images/interactive-schema-root.png "`customPosts` field resolves to `Post` type")

---

Through the Settings for `Schema Custom Posts`, we can also define:

- The default number of elements to retrieve (i.e. when field argument `limit` is not set) when querying for a list of any custom post type
- The maximum number of elements that can be retrieved in a single query execution

![Settings for Custom Post limits](../../images/settings-customposts-limits.png "Settings for Custom Post limits")

