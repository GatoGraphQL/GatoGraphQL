# Searching WordPress data

Searching for data within WordPress is limited in several cases, and Gato GraphQL can help augment these capabilities.

Such an example involves custom fields (i.e. meta values): We may use custom fields to add extra information to posts (and also to users, comments, and taxonomies), however when searching for posts with some keyword, WordPress does not search within meta values.

We can then use Gato GraphQL to search for posts (and also users, comments, and taxonomies) by meta key and value.

## Examples

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

- Create the queries below as <a href="https://gatographql.com/guides/use/creating-a-persisted-query/" target="_blank">Persisted Queries</a>, as to keep them stored in the website, and execute them time and again
- Publish them <a href="https://gatographql.com/guides/special-features/public-private-and-password-protected-endpoints/" target="_blank">as `private`</a>, so that they are available only within the wp-admin, and only to the admin
- Use an <a href="https://gatographql.com/guides/use/creating-an-api-hierarchy/" target="_blank">API hierarchy</a> to manage them (eg: by placing query `search-posts-without-thumbnail` under parent `internal`, it will be available as `internal/search-posts-without-thumbnail`)

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

We can also search meta using regex expressions.

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

With extensions, we can input dynamically-generated inputs to the filter.

This query retrieves the number of comments added to the site starting from "yesterday", "1 year ago", "beginning of the month", and "beginning of the year":

```graphql
query {
  DATE_ISO8601: _env(name: DATE_ISO8601) @remove
  timeToday: _time @remove  
  timeYesterday: _intSubstract(substract: 86400, from: $__timeToday) @remove
  dateYesterday: _date(format: $__DATE_ISO8601, timestamp: $__timeYesterday) @remove  
  time1YearAgo: _intSubstract(substract: 31536000, from: $__timeToday) @remove
  date1YearAgo: _date(format: $__DATE_ISO8601, timestamp: $__time1YearAgo) @remove
  timeBegOfThisMonth: _makeTime(hour: 0, minute: 0, second: 0, day: 1) @remove
  dateBegOfThisMonth: _date(format: $__DATE_ISO8601, timestamp: $__timeBegOfThisMonth) @remove
  timeBegOfThisYear: _makeTime(hour: 0, minute: 0, second: 0, month: 1, day: 1) @remove
  dateBegOfThisYear: _date(format: $__DATE_ISO8601, timestamp: $__timeBegOfThisYear) @remove
  
  commentsAddedInLast24Hs: commentCount(filter: { dateQuery: { after: $__dateYesterday } } )  
  commentsAddedInLast1Year: commentCount(filter: { dateQuery: { after: $__date1YearAgo } } )  
  commentsAddedSinceBegOfThisMonth: commentCount(filter: { dateQuery: { after: $__dateBegOfThisMonth } } )  
  commentsAddedSinceBegOfThisYear: commentCount(filter: { dateQuery: { after: $__dateBegOfThisYear } } )
}
```
