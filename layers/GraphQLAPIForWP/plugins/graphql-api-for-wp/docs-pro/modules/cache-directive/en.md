# Cache Directive

The `@cache` directive stores the result from a field in disk for a requested amount of time. When executing the same field within that time span, the cached value is returned

## Description

Add `@cache` to the field to cache, specifying for how long (in seconds) under argument `time`.

In this example, the Google-Translated `title` field is cached for 10 seconds. Executing the query twice within this time span will have the second call execute very fast.

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

## When to use

This directive is useful to avoid the execution of expensive operations, such as when interacting with external APIs.
