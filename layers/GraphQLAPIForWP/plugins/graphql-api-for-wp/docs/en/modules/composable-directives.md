# Composable Directives

Have a directive modify the behavior of another directives. It introduces a directive argument `nestedUnder` on each directive, to indicate which is its parent directive. This number is a negative integer, defining the the parent directive's relative position.

In this example below, we have:

- `@directive1` is the parent directive
- `@directive11` and `@directive12` are added under `@directive1`
- `@directive 121` is added under `@directive12`:

```graphql
{
  someField
    @directive1
    @directive11(nestedUnder:-1)
    @directive12(nestedUnder:-2)
    @directive121(nestedUnder:-1)
}
```

## When to use

Sometimes, a directive cannot be applied on the field, because it has an input which is different than the field's output.

For instance, `User.capabilities` returns `[String]` (an array of strings), and directive `@upperCase` receives `String`. Hence, executing the following query returns an error due to the type mismatch:

```graphql
{
  users {
    capabilities @upperCase
  }
}
```

With composable directives, a directive can augment another directive to fill a gap.

For instance, directive `@forEach` can iterate over an array of elements, and apply its nested directive on each of them. The query from above can be satisfied like this:

```graphql
{
  users {
    capabilities
      @forEach
      @upperCase(nestedUnder: -1)
  }
}
```

## Examples

Translating the content of all paragraph blocks inside a post, from English to French:

```graphql
query {
  posts {
    id
    title
    # Data for all the blocks
    blockMetadata
    # Data for all the paragraph blocks
    paragraphs: blockMetadata(
      blockName: "core/paragraph"
    )
    # Translated content for all the paragraph blocks
    translatedParagraphs: blockMetadata(
      blockName: "core/paragraph"
    )
      @advancePointerInArray(path: "meta.content")
      @forEach(nestedUnder: -1)
      @translate(
        from: "en",
        to: "fr",
        nestedUnder: -1
      )
  }
}
```

## GraphQL spec

This functionality is currently not part of the GraphQL spec, but a related one has been requested:

- <a href="https://github.com/graphql/graphql-spec/issues/683" target="_blank">Issue #683 - [RFC] Composable directives</a>
