# Searching WordPress data

Searching for data within WordPress is limited in several cases, and Gato GraphQL can help augment these capabilities.

One such case is custom fields (i.e. meta values): We may use custom fields to add extra information to posts, users, comments, or taxonomies, however WordPress does not search within meta values.

We can then create a Gato GraphQL query that retrieves the required data. For instance, this query retrieves all posts that either have a thumbnail (and that specific data) or not:

```graphql
query {
  postsWithThumbnail: posts(
    filter: {
      metaQuery: [
        {
          key: "_thumbnail_id",
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
    featuredImage {
      id
      src
    }
  }

  postsWithoutThumbnail: posts(
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

let's say we have a meta key "

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
