# Searching WordPress data

Searching for data within WordPress is limited in several cases, and Gato GraphQL can help augment these capabilities.

<div class="doc-highlight" markdown=1>

🔥 Some recommendations:

- Create the queries below as <a href="https://gatographql.com/guides/use/creating-a-persisted-query/" target="_blank">Persisted Queries</a>, as to keep them stored in the website, and execute them time and again
- Publish them <a href="https://gatographql.com/guides/special-features/public-private-and-password-protected-endpoints/" target="_blank">as `private`</a>, so that they are available only within the wp-admin, and only to the admin
- Use an <a href="https://gatographql.com/guides/use/creating-an-api-hierarchy/" target="_blank">API hierarchy</a> to manage them, eg: by placing them under `internal/` (eg: `internal/search/search-posts-without-thumbnail`)

</div>

One such case where finding data in WordPress is limited concerns custom fields (i.e. meta values): We may use custom fields to add extra information to posts (and also to users, comments, and taxonomies), however when searching for posts with some keyword, WordPress does not search within meta values.

We can use Gato GraphQL to retrieve posts (and also users, comments, and taxonomies), filtering them by meta key and value.

For instance, this query retrieves all posts that have a thumbnail, and those that do not:

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
    filter: { metaQuery:
      {
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

Filtering by meta can also be combined with any of the standard data items. This query retrieves all posts without thumbnail that were created after a certain date:

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
      }
    }
  ) {
    id
    title
  }
}
```

Moreover, we can search meta using regex expressions.

This query searches for all users with a Spanish locale (for instance, `es_AR` for Argentina, `es_ES` for Spain, and so on):

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

---

Also use:

Add recipe with "Representing a date with some specific format":
  layers/GatoGraphQLForWP/phpunit-packages/gato-graphql-pro/tests/Unit/Faker/fixture-data-transformations/dates.gql
