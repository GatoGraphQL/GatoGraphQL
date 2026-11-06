# AnyBuiltInScalar Type

Scalar type `AnyBuiltInScalar` represents any of [GraphQL's built-in scalar types](https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars):

- `String`
- `Int`
- `Boolean`
- `Float`
- `ID`

## Description

The GraphQL specification currently [does not support the union of scalar types](https://github.com/graphql/graphql-spec/issues/215).

As such, if a field can return different scalar types, such as `Int` and `String`, currently we would need to recreate the field multiple times, one time per type, such as:

- `optionValueInt`
- `optionValueString`

However, this can easily make the GraphQL schema become bloated, so it should be avoided, as much as possible.

For this reason, Gato GraphQL introduces `AnyBuiltInScalar`. The schema uses it where WordPress can store data in different formats, such as the options saved in the `wp_options` table:

- `optionValues: [AnyBuiltInScalar]`

In addition, the sibling type `AnyScalar` extends to custom scalars too, such as `JSONObject`. The meta mutations use it for the `value` input.
