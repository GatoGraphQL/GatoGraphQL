# Searching WordPress data

Searching for data within WordPress is limited in several cases, and Gato GraphQL can help augment these capabilities.

<div class="doc-highlight" markdown=1>

🔥 **Heads up!** We can create the queries described below as GraphQL Persisted Queries in our websites, as to keep them stored and execute them time and again. Since their use is for us (i.e. not for the application), we can <a href="https://gatographql.com/guides/special-features/public-private-and-password-protected-endpoints/" target="_blank">set their status as `private`</a> (hence they are available only within the wp-admin). To manage these queries, we can <a href="https://gatographql.com/guides/use/creating-an-api-hierarchy/" target="_blank">create an API hierarchy</a>, placing them under some `internal/search/` (or other) parent Persisted Query.

</div>

One such case where finding data in WordPress is limited concerns custom fields (i.e. meta values): We may use custom fields to add extra information to posts (and also to users, comments, and taxonomies), however when searching for posts with some keyword, WordPress does not search within meta values.

We can then create a Gato GraphQL query that retrieves the required data. For instance, this query retrieves all posts that either have a thumbnail (and that specific data) or not:

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

We can use the `AND` (and also `OR`) relation to filter data more precisely. This query retrieves posts with a thumbnail that was marked as "needs to have the thumbnail replaced" via a custom meta entry `todo_action`:

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

```graphql
query {
  posts(
    filter: {
      metaQuery: [
        {
          key: "_thumbnail_id",
          compareBy: {
            key: {
              operator: NOT_EXISTS
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

Let's say we have a meta key "

In WP can't look for posts or users with certain metadata. Here, can.

By meta queries:

```graphql
query {
  postsOR: posts(
    filter: {
      metaQuery: [
        {
          relation: OR
          key: "_thumbnail_id",
          compareBy: {
            key: {
              operator: EXISTS
            }
          }
        },
        {
          key: "key_not_exists_so_all_entries_eval_false",
          compareBy: {
            key: {
              operator: EXISTS
            }
          }
        }
      ]
    }
  ) {
    id
    title
  }

  postsAND: posts(
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
          key: "key_not_exists_so_all_entries_eval_false",
          compareBy: {
            key: {
              operator: EXISTS
            }
          }
        }
      ]
    }
  ) {
    id
    title
  }

  postsWithFeaturedImage: posts(filter: {
    metaQuery: {
      key: "_thumbnail_id",
      compareBy: {
        key: {
          operator: EXISTS
        }
      }
    }
  }, sort: { by: ID, order: ASC }) {
    id
    title
    featuredImage {
      id
    }
  }
  
  postsWithoutFeaturedImage: posts(filter: {
    metaQuery: {
      key: "_thumbnail_id",
      compareBy: {
        key: {
          operator: NOT_EXISTS
        }
      }
    }
  }, sort: { by: ID, order: ASC }) {
    id
    title
    featuredImage {
      id
    }
  }

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

  argentineSpanishLocaleUsers: users(filter: { metaQuery: {
    key: "locale",
    compareBy: {
      stringValue: {
        value: "es_AR"
        operator: EQUALS
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
