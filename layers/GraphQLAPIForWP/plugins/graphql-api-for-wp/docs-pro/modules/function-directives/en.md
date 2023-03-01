# Function Directives

Manipulate the field output using standard programming language functions (provided via special directives)

## Description

This module adds several directives to the GraphQL schema which expose functionalities commonly found in programming languages (such as PHP).

Directive fields are useful for manipulating the data once it has been retrieved, allowing us to transform a field value in whatever way it is required, and granting us powerful data import/export capabilities.

## Available Function Directives

This is the list of currently-available function directives.

### `@arrayAddItem`

Adds an element to the array.

### `@arrayDiff`

Computes the difference with another array.

### `@arrayFilter`

Filters out the null or empty elements in the array.

### `@arrayMerge`

Merge the array with another array.

### `@arrayPad`

Pad an array to the specified length with a value.

### `@arrayRemoveFirst`

Remove the first element in the array.

### `@arrayRemoveLast`

Remove the last element in the array.

### `@arrayReverse`

Reverse an array.

### `@arraySetItem`

Sets an element on some position of the array.

### `@arraySlice`

Extract a slice of an array.

### `@arraySplice`

Remove a portion of an array and replace it with something else.

### `@arrayUnique`

Filters out all duplicated elements in the array.

### `@boolOpposite`

Convert a bool to its opposite value.

### `@fail`

Add an entry under the response's `errors` to trigger the failure of the GraphQL request whenever some condition on the field is met.

### `@intAdd`

Add an integer number to the field value.

### `@objectAddEntry`

Add an entry to the JSON object.

### `@objectRemoveEntry`

Removes an entry from the JSON object.

### `@setNull`

Set the field's value as `null`.

### `@strAppend`

Append some string to the end of the string in the field value.

### `@strLowerCase`

Convert a string to lower case.

### `@strPad`

Pad a string to a certain length with another string.

### `@strPrepend`

Append some string to the beginning of the string in the field value.

### `@strRegexReplace`

Execute a regular expression to search and replace a string (see [documentation for PHP function `preg_replace`](https://www.php.net/manual/en/function.preg-replace.php)).

### `@strRepeat`

Repeat a string.

### `@strReplace`

Replace a string with another string.

### `@strReverse`

Reverse a string.

### `@strShuffle`

Randomly shuffles a string.

### `@strStripSlashes`

Returns a string with backslashes stripped off. (\\\' becomes \' and so on.) Double backslashes (\\\\) are made into a single backslash .

### `@strSubstr`

Return part of a string.

### `@strTitleCase`

Convert a string to title case.

### `@strTrim`

Strip whitespace (or other characters) from the beginning and end of a string.

### `@strUpperCase`

Convert a string to upper case.

## Examples

For instance, if this query produces the results below:

```graphql
{
  posts {
    title
  }
}
```

```json
{
  "data": {
    "posts": [
      {
        "title": "Hello world!"
      },
      {
        "title": "lovely weather"
      }
    ]
  }
}
```

Then, the following query will produce the following response:

```graphql
{
  posts {
    title @strUpperCase
  }
}
```

```json
{
  "data": {
    "posts": [
      {
        "title": "HELLO WORLD!"
      },
      {
        "title": "LOVELY WEATHER"
      }
    ]
  }
}
```
