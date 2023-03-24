# Searching WordPress data

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
