# Lesson 1: Searching WordPress data

Searching for data within WordPress is limited in several cases, and Gato GraphQL can help augment these capabilities.

Such an example involves custom fields (i.e. meta values): We may use custom fields to add extra information to posts (and also to users, comments, and taxonomies), however when searching for posts with some keyword, WordPress does not search within meta values.

We can then use Gato GraphQL to search for posts (and also users, comments, and taxonomies) by meta key and value.

## Examples

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- Create the queries below as [Persisted Queries](https://gatographql.com/guides/use/creating-a-persisted-query/), as to keep them stored in the website, and execute them time and again
- Publish them [as `private`](https://gatographql.com/guides/special-features/public-private-and-password-protected-endpoints/), so that they are available only within the wp-admin, and only to the admin
- Use an [API hierarchy](https://gatographql.com/guides/use/creating-an-api-hierarchy/) to manage them (eg: have a Persisted Query `internal` be the ancestor to all the internal queries: `internal/search-posts-without-thumbnail`, `internal/search-users-by-locale`, etc)

</div>

This query retrieves all posts that have a thumbnail, and those that do not:

```graphql
query {
  postsWithThumbnail: posts(
    filter: {
      metaQuery: {
        key: "_thumbnail_id",
        compareBy: {
          key: {
            operator: EXISTS
          }
        }
      }
    }
  ) {
    id
    title
    featuredImage {
      id
      src
    }
  }

  postsWithoutThumbnail: posts(
    filter: {
      metaQuery: {
        key: "_thumbnail_id",
        compareBy: {
          key: {
            operator: NOT_EXISTS
          }
        }
      }
    }
  ) {
    id
    title
  }
}
```

This query retrieves all users who use the locale "Spanish from Argentina":

```graphql
query {
  argentineSpanishLocaleUsers: users(
    filter: {
      metaQuery: {
        key: "locale",
        compareBy: {
          stringValue: {
            value: "es_AR"
            operator: EQUALS
          }
        }
      }
    }
  ) {
    id
    name
    locale: metaValue(key: "locale")
  }
}
```

We can use the `AND` and `OR` relations to filter data more precisely. This query retrieves posts that both have a thumbnail, and also have a custom meta `todo_action` with value `"replace"` (meaning that it needs to have the thumbnail replaced):

```graphql
query {
  posts(
    filter: {
      metaQuery: [
        {
          relation: AND
          key: "_thumbnail_id",
          compareBy: {
            key: {
              operator: EXISTS
            }
          }
        },
        {
          key: "todo_action",
          compareBy: {
            stringValue: {
              value: "replace"
              operator: EQUALS
            }
          }
        }
      ]
    }
  ) {
    id
    title
  }
}
```

Filtering by meta can also be combined with any of the standard data items. This query retrieves all posts without thumbnail that were created after a certain date and have been tagged `"wordpress"`:

```graphql
query {
  posts(
    filter: {
      metaQuery: {
        key: "_thumbnail_id",
        compareBy: {
          key: {
            operator: NOT_EXISTS
          }
        }
      },
      dateQuery: {
        after: "2020-07-01"
      },
      tags: {
        includeBy: {
          slugs: [
            "wordpress"
          ]
        }
      }
    }
  ) {
    id
    title
    tagNames
  }
}
```

We can also search meta using regex expressions. This query searches for all users with a Spanish locale (for instance, `es_AR` for Argentina, `es_ES` for Spain, and so on):

```graphql
query {
  spanishLocaleUsers: users(filter: { metaQuery: {
    key: "locale",
    compareBy: {
      stringValue: {
        value: "es_[A-Z]+"
        operator: REGEXP
      }
    }
  }}) {
    id
    name
    locale: metaValue(key: "locale")
  }
}
```
