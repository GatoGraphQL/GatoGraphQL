# Remove Output Directive

Add directive `@remove` to remove the output of a field from the response.

## Description

The GraphQL spec indicates that the GraphQL response needs to match exactly the shape of the query. However, in certain circumstances we'd rather avoid sending back the response of the field, because:

- It is of no value
- We already know what it is
- An empty field can be distinguished from a `null` value

For instance, let's say we want to retrieve some specific data from an external REST API endpoint, and we don't need the rest of the data. Then, we can use field `_requestJSONObjectItem` (from the **HTTP Request Fields** module) to connect to the REST API, process this data to extract the needed piece of information (via **Field to Input** and the `_objectProperty` field from **Function Fields**), and finally `@remove` the original data from the REST endpoint, which is of no use to us:

```graphql
{
  postData: _requestJSONObjectItem(
    url: "https://newapi.getpop.org/wp-json/wp/v2/posts/1"
  ) @remove
  renderedTitle: _objectProperty(
    object: $__postData,
    by: {
      path: "title.rendered"
    }
  )
}
```

In the response to this query, field `postData` has been removed:

```json
{
  "data": {
    "renderedTitle": "Hello world!"
  }
}
```

**Please notice:** `@remove` takes place at the very end of the resolution of all the fields under the same node. That's why, in the query above, the field `renderedTitle` is processed before field `postData` is `@remove`d.

## How to use

Directive `@remove` has argument `condition`, with which we can specify under what condition to remove the field. It has 3 possible values:

- `ALWAYS` (default value): Remove it always
- `IS_NULL`: Remove it whenever the value is `null`
- `IS_EMPTY`: Remove it whenever the value is empty

For instance, in the query below, when a post does not have a featured image, field `featuredImage` will have value `null`. By adding `@remove(condition: IS_NULL)`, this value will not be added to the response:

```graphql
query {
  posts {
    title
    featuredImage @remove(condition: IS_NULL) {
      src
    }
  }
}
```

## GraphQL spec

This functionality is currently not part of the GraphQL spec, but it has been requested:

- <a href="https://github.com/graphql/graphql-spec/issues/275#issuecomment-338538911" target="_blank">Issue #275 - @include(unless null) ?</a>
- <a href="https://github.com/graphql/graphql-spec/issues/766" target="_blank">Issue #766 - GraphQL query: skip value field if null</a>
