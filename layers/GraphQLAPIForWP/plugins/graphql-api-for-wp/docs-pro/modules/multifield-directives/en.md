# Multi-Field Directives

A single directive can be applied to multiple fields, for performance and extended use cases.

## Description

This module allows directives to be applied to multiple fields, instead of only one. When enabled, an argument `affectAdditionalFieldsUnderPos` is added to all directives, where the relative positions of additional fields to apply the directive to can be specified.

For instance, in the following query, directive `@strTranslate` is applied only to field `content`:

```graphql
{
  posts {
    excerpt
    content @strTranslate
  }
}
```

Field `excerpt` can also be applied directive `@strTranslate`, by adding the directive argument `affectAdditionalFieldsUnderPos` with value `[1]` (as `1` is the relative position of field `excerpt` from directive `@strTranslate`):

```graphql
{
  posts {
    excerpt
    content
      @strTranslate(
        affectAdditionalFieldsUnderPos: [1]
      )
  }
}
```

The number of fields to add is not limited. In this query, the `dateStr` is also being translated:

```graphql
{
  posts {
    dateStr
    excerpt
    content
      @strTranslate(
        affectAdditionalFieldsUnderPos: [1, 2]
      )
  }
}
```

The field over which the directive is naturally applied (such as `content` in all queries above) must not be specified on the argument.

On the query above, the relative positions from directive `@strTranslate` to the previous fields are:

- Position `2`: `dateStr`
- Position `1`: `excerpt`
- Position `0`: `content` <= It's implicit, always applied

## Use cases

There are two main use cases for this feature:

1. Performance
2. Extended functionality

### Performance

For directives that execute calls to external APIs, the lower number of requests they execute, they faster they will be resolved.

That's the case with directive `@strTranslate`, which connects to the Google Translate API. Normally, to translate fields `content` and `excerpt` from a list of posts, the query would be this one:

```graphql
{
  posts {
    excerpt @strTranslate
    content @strTranslate
  }
}
```

By adding `@strTranslate` twice, this query executes two requests to the Google Translate API (one to translate all values for `excerpt`, one for all values for `content`).

Thanks to the **Multi-Field Directives** feature, the query below also translates all values for both `content` and `excerpt` fields, but instead it executes a single request to the Google Translate API:

```graphql
{
  posts {
    excerpt
    content
      @strTranslate(
        affectAdditionalFieldsUnderPos: [1]
      )
  }
}
```

### Extended Functionality

Directives receiving extra fields can provide additional calculations.

For instance, directive `@export` normally exports the value of a single field, such as the logged-in user's name:

```graphql
query GetLoggedInUserName {
  me {
    name @export(as: "userName")
  }
}
```

Through argument `affectAdditionalFieldsUnderPos`, `@export` can receive multiple fields, and will then export a dictionary containing those fields as entries:

```graphql
query GetLoggedInUserNameAndSurname {
  me {
    name
    surname
      @export(
        as: "userProps"
        affectAdditionalFieldsUnderPos: [1]
      )
  }
}
```

`@export` will now produce the following value on variable `$userProps`

```json
{
  "name": "Leo",
  "surname": "Loso"
}
```
