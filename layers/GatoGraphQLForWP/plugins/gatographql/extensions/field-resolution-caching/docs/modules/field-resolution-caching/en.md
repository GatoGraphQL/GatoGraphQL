# Field Resolution Caching

Addition of the `@cache` directive to the GraphQL schema, which stores the result from a field in disk for a requested amount of time. When executing the same field within that time span, the cached value is returned.

Add `@cache` to the field to cache in the GraphQL query, specifying for how much time (in seconds) must the result be cached.

This directive can boost performance when executing expensive operations (such as when interacting with external APIs), as we can cache and reuse their response.

## Example

The `@strTranslate` connects to the Google Translate API. By using `@cache(time: 10)`, the translated value for the `title` field will be cached for 10 seconds, and executing the same query again within this time span will avoid connecting to Google Translate, resulting in a very fast resolution.

<!-- @todo Un-comment here when FeedbackCategories::LOG is enabled and documented, and `@traceExecutionTime` is supported -->
<!-- A log entry will indicate if the field is being retrieved from the cache. -->

```graphql
query {
  posts(pagination:{ limit: 3 }) {
    id
    title
      @strTranslate(from: "en", to: "es")
      @cache(time: 10)
  }
}
```

<!-- @todo Un-comment here when FeedbackCategories::LOG is enabled and documented, and `@traceExecutionTime` is supported -->
<!-- Use `@traceExecutionTime` to log the difference in field resolution time:

```graphql
query {
  posts(pagination:{ limit: 3 }) {
    id
    title
      @strTranslate(from: "en", to: "es")
      @cache(time: 10)
      @traceExecutionTime
  }
}
```
-->

## Bundles including extension

- [“All Extensions” Bundle](../../../../../bundle-extensions/all-extensions/docs/modules/all-extensions/en.md)
- [“WordPress Automator” Bundle](../../../../../bundle-extensions/wordpress-automator/docs/modules/wordpress-automator/en.md)

## Tutorial lessons referencing extension

- [Retrieving data from an external API](../../../../../docs/tutorial/retrieving-data-from-an-external-api/en.md)
