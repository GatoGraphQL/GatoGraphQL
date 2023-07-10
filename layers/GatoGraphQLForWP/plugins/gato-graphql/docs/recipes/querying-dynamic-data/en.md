# Querying dynamic data

Gato GraphQL can further augment WordPress' capabilities to search data via the use of "functionality" fields, allowing us to dynamically compute data, input it back into the query, and affect the response with granular control.

## Examples

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

To support "functionality" fields efficiently, Gato GraphQL has created the concept of [Global fields](https://gatographql.com/guides/special-features/global-fields/): Fields that are accessible not under a specific type (such as `Post` or `User`), but under all the types from the GraphQL schema (while being defined only once).

</div>

We can create dynamically-generated data, and input it into a filter to fetch posts, comments, etc.

This query retrieves the number of comments added to the site starting from "yesterday", "1 year ago", "beginning of the month", and "beginning of the year":

```graphql
query {
  DATE_ISO8601: _env(name: DATE_ISO8601)
  timeToday: _time  
  timeYesterday: _intSubstract(substract: 86400, from: $__timeToday)
  dateYesterday: _date(format: $__DATE_ISO8601, timestamp: $__timeYesterday)  
  time1YearAgo: _intSubstract(substract: 31536000, from: $__timeToday)
  date1YearAgo: _date(format: $__DATE_ISO8601, timestamp: $__time1YearAgo)
  timeBegOfThisMonth: _makeTime(hour: 0, minute: 0, second: 0, day: 1)
  dateBegOfThisMonth: _date(format: $__DATE_ISO8601, timestamp: $__timeBegOfThisMonth)
  timeBegOfThisYear: _makeTime(hour: 0, minute: 0, second: 0, month: 1, day: 1)
  dateBegOfThisYear: _date(format: $__DATE_ISO8601, timestamp: $__timeBegOfThisYear)
  
  commentsAddedInLast24Hs: commentCount(filter: { dateQuery: { after: $__dateYesterday } } )  
  commentsAddedInLast1Year: commentCount(filter: { dateQuery: { after: $__date1YearAgo } } )  
  commentsAddedSinceBegOfThisMonth: commentCount(filter: { dateQuery: { after: $__dateBegOfThisMonth } } )  
  commentsAddedSinceBegOfThisYear: commentCount(filter: { dateQuery: { after: $__dateBegOfThisYear } } )
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Fields `_time`, `_intSubstract`, `_date` and `_makeTime` are available through the **PHP Functions Via Schema** extension, which provides many of the most common PHP functions as global fields, including:

- `_arrayItem`
- `_equals`
- `_inArray`
- `_intAdd`
- `_isEmpty`
- `_isNull`
- `_objectProperty`
- `_sprintf`
- `_strContains`
- `_strRegexReplace`
- `_strSubstr`
- And many more...

</div>
