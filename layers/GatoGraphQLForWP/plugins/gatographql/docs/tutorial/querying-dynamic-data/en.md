# Lesson 2: Querying dynamic data

Gato GraphQL can further augment WordPress' capabilities to search data via the use of "function" fields (a distinct type of field which provides functionality instead of data), allowing us to dynamically compute data, input it back into the query, and affect the response with granular control.

## Examples

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Gato GraphQL provides function fields under the concept of [Global fields](https://gatographql.com/guides/special-features/global-fields/): Fields that are accessible under all the types from the GraphQL schema. (Normal fields in GraphQL, in contrast, are accessible only under some specific type, such as `Post` or `User`).

By convention, global fields in Gato GraphQL start with `_` (and normal fields do not).

The [**PHP Functions Via Schema**](https://gatographql.com/extensions/php-functions-via-schema/) extension provides many of the most common PHP functions as global fields, including:

- `_arrayItem`
- `_arrayJoin`
- `_date`
- `_equals`
- `_inArray`
- `_intAdd`
- `_isEmpty`
- `_isNull`
- `_makeTime`
- `_objectProperty`
- `_sprintf`
- `_strContains`
- `_strRegexReplace`
- `_strSubstr`
- `_time`,
- And many more...

</div>

We can create dynamically-generated data, and input it into a filter to fetch posts, comments, etc.

This query retrieves the number of comments added to the site in the last 24 hs, which is computed as "time now minus 86400 seconds":

```graphql
query {
  timeNow: _time  
  time24HsAgo: _intSubstract(
    substract: 86400,
    from: $__timeNow
  )
  date24HsAgo: _date(
    format: "Y-m-d\\TH:i:sO",
    timestamp: $__time24HsAgo
  )  
  commentsAddedInLast24Hs: commentCount(
    filter: {
      dateQuery: {
        after: $__date24HsAgo
      }
    }
  ) 
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

`$__timeNow` is a variable dynamically created by the [**Field to Input**](https://gatographql.com/extensions/field-to-input/) extension, which allows us to obtain the value of a field and [input it into another field](https://gatographql.com/guides/schema/using-field-to-input/) in that same operation.

The field to obtain the value from is referenced using the "Variable" syntax `$`, and `__` before the field alias or name:

```graphql
{
  posts {
    excerpt

    # Referencing previous field with name "excerpt"
    isEmptyExcerpt: _isEmpty(value: $__excerpt)

    # Referencing previous field with alias "isEmptyExcerpt"
    isNotEmptyExcerpt: _not(value: $__isEmptyExcerpt)
  }
}
```

</div>

This query retrieves the number of comments added to the site starting from "24 hours ago", "1 year ago", "beginning of the month", and "beginning of the year":

```graphql
query {
  timeNow: _time  
  time24HsAgo: _intSubstract(substract: 86400, from: $__timeNow)
  date24HsAgo: _date(format: "Y-m-d\\TH:i:sO", timestamp: $__time24HsAgo)  
  time1YearAgo: _intSubstract(substract: 31536000, from: $__timeNow)
  date1YearAgo: _date(format: "Y-m-d\\TH:i:sO", timestamp: $__time1YearAgo)
  timeBegOfThisMonth: _makeTime(hour: 0, minute: 0, second: 0, day: 1)
  dateBegOfThisMonth: _date(format: "Y-m-d\\TH:i:sO", timestamp: $__timeBegOfThisMonth)
  timeBegOfThisYear: _makeTime(hour: 0, minute: 0, second: 0, month: 1, day: 1)
  dateBegOfThisYear: _date(format: "Y-m-d\\TH:i:sO", timestamp: $__timeBegOfThisYear)
  
  commentsAddedInLast24Hs: commentCount(filter: { dateQuery: { after: $__date24HsAgo } } )  
  commentsAddedInLast1Year: commentCount(filter: { dateQuery: { after: $__date1YearAgo } } )  
  commentsAddedSinceBegOfThisMonth: commentCount(filter: { dateQuery: { after: $__dateBegOfThisMonth } } )  
  commentsAddedSinceBegOfThisYear: commentCount(filter: { dateQuery: { after: $__dateBegOfThisYear } } )
}
```

This query is the same as the previous one, however it retrieves the standardized time format `"Y-m-d\\TH:i:sO"` from PHP constant `DATE_ISO8601`:

```graphql
query {
  # This PHP constant will provide standard time format "Y-m-d\\TH:i:sO"
  DATE_ISO8601: _env(name: DATE_ISO8601)
  timeNow: _time  
  time24HsAgo: _intSubstract(
    substract: 86400,
    from: $__timeNow
  )
  date24HsAgo: _date(
    format: $__DATE_ISO8601,
    timestamp: $__time24HsAgo
  )
}
```

<div class="doc-highlight" markdown=1>

🔥 **Tips:**

Field `_env` is provided via the [**PHP Constants and Environment Variables via Schema**](https://gatographql.com/extensions/php-constants-and-environment-variables-via-schema/) extension.

Via the applied Schema Configuration and plugin Settings, we can configure [which constants and environment variables can be queried](https://gatographql.com/guides/config/configuring-what-environment-variables-and-php-constants-can-be-queried/).

<!-- ![Defining the entries on the Schema Configuration](../../../extensions/php-constants-and-environment-variables-via-schema/docs/images/environment-fields-schema-configuration-entries.png "Defining the entries on the Schema Configuration") -->

</div>
