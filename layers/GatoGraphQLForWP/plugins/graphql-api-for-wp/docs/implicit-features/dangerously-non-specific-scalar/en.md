# DangerouslyNonSpecificScalar Type

Scalar type `DangerouslyNonSpecificScalar` represents any scalar type (including both built-in and custom scalar types), and in addition it can be a single value, a List, or a List of Lists.

In other words, considering a hypothetical scalar type `AnyScalar` that handles all scalar types, `DangerouslyNonSpecificScalar` represents all of these, at the same time:

- `AnyScalar`
- `[AnyScalar]`
- `[[AnyScalar]]`

_Please notice: This feature is available in the GraphQL API, however it is not currently used anywhere in the schema. The GraphQL API PRO does make use of it. Check the fields added by module **Function Fields**, such as `_echo`, to see examples of it._

## Description

Fields cannot be defined to return all potential combinations of types and their modifiers: a single value, a list of values, or a list of list of values.

For instance, field `optionValue` returns type `AnyBuiltInScalar` (i.e. it can handle any of [GraphQL's built-in scalar types](https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars)), but it can only retrieve a single value, and not a list of values. If we need to retrieve a list of values, then we need to use field `optionValues` instead, which returns `[AnyBuiltInScalar]`.

However, being able to return either a single value, a list of values, or a list of lists of values, always from the same field, is useful for the **Function Fields**, as they provide functionalities which, in many cases, are independent of the type or cardinality of the value.

An example is the field `_echo` which, whatever input it gets:

```graphql
{
  single: _echo(value: "hello")
  list: _echo(value: [1, 2, 3])
  listOfLists: _echo(value: [["a", "b", "c"], ["d", "e"], ["f"]])
}
```

...it will print it back:

```json
{
  "data": {
    "single": "hello",
    "list": [1, 2, 3],
    "listOfLists": [["a", "b", "c"], ["d", "e"], ["f"]]
  }
}
```

Another example is field `_arrayItem` which, given an array an a position, retrieves the item at that position from the array. This field does not care if the array contains single values, lists, lists of lists, or what not; whatever item is contained in the array at that position will be retrieved.

For instance, in this query, the posts' categories are exported to a dynamic variable via the **Field to Input** feature, and then the first item is retrieved:

```graphql
{
  posts(pagination: { limit: 3 }) {
    categoryNames
    mainCategory: _arrayItem(array: $__categoryNames, position: 0)
  }
}
```

...producing:

```json
{
  "data": {
    "posts": [
      {
        "categoryNames": [
          "Uncategorized"
        ],
        "mainCategory": "Uncategorized"
      },
      {
        "categoryNames": [
          "Advanced"
        ],
        "mainCategory": "Advanced"
      },
      {
        "categoryNames": [
          "Resource",
          "Blog",
          "Advanced"
        ],
        "mainCategory": "Resource"
      }
    ]
  }
}
```

As `categoryNames` returns `[String]`, then `_arrayItem` will produce `String`. If the input were instead `[[String]]`, then `_arrayItem` will produce `[String]`. And so on.
