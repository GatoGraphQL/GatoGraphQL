# Release Notes: 19.2

## Added

### Composing directives with `@start` and `@end`

Meta directives (such as `@underEachArrayItem`, `@if` and `@unless`) can now indicate which directives they affect by wrapping them between the helper directives `@start` and `@end`, as an alternative to argument `affectDirectivesUnderPos` ([#3369](https://github.com/GatoGraphQL/GatoGraphQL/pull/3369)).

Instead of calculating the relative position of every affected directive:

```graphql
{
  someField
    @underEachArrayItem(affectDirectivesUnderPos: [1, 2])
      @strTrim
      @strUpperCase
}
```

...place `@start` right after the meta directive, and `@end` after the last affected directive:

```graphql
{
  someField
    @underEachArrayItem @start
      @strTrim
      @strUpperCase
    @end
}
```

Both queries produce the same result. `@start` and `@end` are pure syntax: they are removed while parsing the query, and never reach the GraphQL schema.

The benefit grows with the size of the query, as the relative positions must otherwise account for every directive nested inside the affected ones. In the query below, `@underEachArrayItem` affects `@applyField`, `@if` and `@unless` — which, using `affectDirectivesUnderPos`, must be indicated as `[1, 2, 5]`:

```graphql
{
  _echo(value: ["one two", "three four", null, "five six"])
    @underEachArrayItem(passValueOnwardsAs: "value") @start
      @applyField(
        name: "_notNull"
        arguments: { value: $value }
        passOnwardsAs: "isNotNullValue"
      )
      @if(condition: $isNotNullValue) @start
        @strTitleCase
        @strReplace(
          search: " "
          replaceWith: "-"
        )
      @end
      @unless(condition: $isNotNullValue)
        @default(value: "Added by @default only on `null` value")
    @end
}
```

As shown above, `@start` and `@end` can be omitted when the meta directive affects a single directive (as with `@unless` and `@default`).

Both methods can be combined within the same query, but not on the same meta directive. The query is validated while parsing it, returning an error whenever `@start` has no matching `@end` (or the other way around), the block contains no directives, `@start` is not placed right after a meta directive, or a meta directive affects a directive placed outside of its `@start`/`@end` block.

## Improvements

- Updated "Field Value Iteration and Manipulation" docs with the `@underDynamicVariable` meta directive ([#3371](https://github.com/GatoGraphQL/GatoGraphQL/pull/3371))
