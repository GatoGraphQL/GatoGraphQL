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

- Tested up to WordPress 7.1 ([#aa2cdc8d](https://github.com/GatoGraphQL/GatoGraphQL/commit/aa2cdc8d))
- The read-only lists of selected schema elements are spaced out, instead of having their items sit flush against each other ([#3382](https://github.com/GatoGraphQL/GatoGraphQL/pull/3382)) (`v19.2.1`).
- Updated "Field Value Iteration and Manipulation" docs with the `@underDynamicVariable` meta directive ([#3371](https://github.com/GatoGraphQL/GatoGraphQL/pull/3371))
- Updated the WooCommerce docs with the store configuration, and brought the bundle teaser in line with what the extension reaches ([#3374](https://github.com/GatoGraphQL/GatoGraphQL/pull/3374))
- The tutorials on translating block content to a different language now also translate the header and footer cells of a `core/table` block, which the query extracted for the body rows and the caption only ([#3375](https://github.com/GatoGraphQL/GatoGraphQL/pull/3375))

## Fixed

- The dropdowns in the plugin's post type editors are styled again under WordPress 7.1 ([#3380](https://github.com/GatoGraphQL/GatoGraphQL/pull/3380)) (`v19.2.1`).

- The checkmarks in the read-only lists of selected schema elements are no longer greyed out under WordPress 7.1 ([#3381](https://github.com/GatoGraphQL/GatoGraphQL/pull/3381)) (`v19.2.1`).

- Serializing the value of a field of type `Date` or `DateTime` no longer produces an uncaught PHP error when that value is not a date ([#3372](https://github.com/GatoGraphQL/GatoGraphQL/pull/3372)).

  A meta directive can override the value of a field while keeping its type, as in:

  ```graphql
  {
    post(by: { id: 1 }) {
      date
        @underDynamicVariable(scopedDynamicVariable: $someList) @start
          @underEachArrayItem(passValueOnwardsAs: "item") @start
            @exportFrom(scopedDynamicVariable: $item, as: "items", type: DICTIONARY)
          @end
        @end
    }
  }
  ```

  Field `date` is of type `DateTime`, but the value being processed is not a date. Serializing it formatted the value as a date regardless, producing a PHP error which made the whole request fail with a 500 response, instead of returning a GraphQL error.

  Such a value is now serialized as any other scalar would be, and only an actual date is formatted as a date.

- Running a WP-CLI command that loads WordPress no longer prints `Symfony\Component\Finder\Finder` deprecation notices on PHP 8.1 and above ([#3373](https://github.com/GatoGraphQL/GatoGraphQL/pull/3373)).

  On sites with `WP_DEBUG` enabled, the first WP-CLI command run after the service container cache was purged printed:

  ```
  PHP Deprecated:  Return type of Symfony\Component\Finder\Finder::getIterator() should either be compatible with IteratorAggregate::getIterator(): Traversable, or the #[\ReturnTypeWillChange] attribute should be used to temporarily suppress the notice in phar:///usr/local/bin/wp/vendor/symfony/finder/Finder.php on line 566
  PHP Deprecated:  Return type of Symfony\Component\Finder\Finder::count() should either be compatible with Countable::count(): int, or the #[\ReturnTypeWillChange] attribute should be used to temporarily suppress the notice in phar:///usr/local/bin/wp/vendor/symfony/finder/Finder.php on line 637
  ```

  While compiling the service container, Symfony's Config component checks whether the Finder component is installed, to decide how to resolve the glob patterns in the `services.yaml` files. As the plugin did not ship Finder, that class was instead resolved against the copy bundled inside the WP-CLI `.phar`, which is Symfony 3.4 (still supporting PHP 5.6, and hence predating PHP 8.1's tentative return types).

  The plugin now requires `symfony/finder` itself, so its own (current) copy of the component is used.
