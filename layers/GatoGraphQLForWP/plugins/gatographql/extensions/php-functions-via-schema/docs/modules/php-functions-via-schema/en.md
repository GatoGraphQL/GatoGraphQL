# PHP Functions via Schema

This extension adds fields and directives to the GraphQL schema which expose functionalities commonly found in programming languages (such as PHP).

## Description

Function fields and directives are useful for manipulating the data once it has been retrieved, allowing us to transform a field value in whatever way it is required, and granting us powerful data import/export capabilities.

This query, containing a variety of function fields and directives:

```graphql
{
  _intAdd(add: 15, to: 56)
  _intArraySum(array: [1, 2, 3, 4, 5])

  _arrayJoin(array: ["Hello", "to", "everyone"], separator: " ")
  _arrayItem(array: ["one", "two", "three", "four", "five"], position: 3)
  _arraySearch(array: ["uno", "dos", "tres", "cuatro", "cinco"], element: "tres")
  _arrayUnique(array: ["uno", "dos", "uno", "tres", "cuatro", "dos", "cinco", "dos"])
  _arrayMerge(arrays: [["uno", "dos", "uno"], ["tres", "cuatro", "dos", "cinco", "dos"]])
  _arrayDiff(arrays: [["uno", "dos"], ["tres", "cuatro", "dos"]])
  _arrayAddItem(array: ["uno", "dos"], value: "tres")
  _arraySetItem(array: ["uno", "dos"], index: 0, value: "tres")
  _arrayKeys(array: ["uno", "dos", "tres"])
  _arrayLength(array: ["uno", "dos", "tres"])

  _strRegexFindMatches(regex: "/https?:\\/\\/([a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\\.[a-zA-Z]{2,})/", string: "In website https://gatographql.com there is more information")
  
  _strReplace(search: "https://", replaceWith: "http://", in: "https://gatographql.com")
  _strReplaceMultiple(search: ["https://", "gato"], replaceWith: ["http://", "dog"], in: "https://gatographql.com")
  _strRegexReplace(searchRegex: "/^https?:\\/\\//", replaceWith: "", in: "https://gatographql.com")
  _strRegexReplaceMultiple(searchRegex: ["/^https?:\\/\\//", "/([a-z]*)/"], replaceWith: ["", "$1$1"], in: "https://gatographql.com")
  
  _strStartsWith(search: "orld", in: "Hello world")
  _strEndsWith(search: "orld", in: "Hello world")
  _strUpperCase(text: "Hello world")
  _strLowerCase(text: "Hello world")
  _strTitleCase(text: "Hello world")


  falseToTrue: _echo(value: false) @boolOpposite
  trueToFalse: _echo(value: true) @boolOpposite
  plusOne: _echo(value: 2) @intAdd(number: 1)
  objectAddEntry: _echo(value: {
    user: "Leo",
    contact: {
      email: "leo@test.com"
    }
  })
    @objectAddEntry(key: "phone", value: "+0929094229", underPath: "contact")
    @objectAddEntry(key: "methods", value: {}, underPath: "contact")
    @objectAddEntry(key: "card", value: true, underPath: "contact.methods")
  upperCase: _echo(value: "Hello world") @strUpperCase
  lowerCase: _echo(value: "Hello world") @strLowerCase
  titleCase: _echo(value: "Hello world") @strTitleCase
  append: _echo(value: "Hello world") @strAppend(string: "!!!")
  prepend: _echo(value: "Hello world") @strPrepend(string: "!!!")
  arraySplice: _echo(value: ["uno", "dos", "tres"]) @arraySplice(offset: 1)
  arraySpliceWithLength: _echo(value: ["uno", "dos", "tres"]) @arraySplice(offset: 1, length: 1)
  arraySpliceWithReplacement: _echo(value: ["uno", "dos", "tres"]) @arraySplice(offset: 1, replacement: ["cuatro", "cinco"])
  arraySpliceWithLengthAndReplacement: _echo(value: ["uno", "dos", "tres"]) @arraySplice(offset: 1, length: 1, replacement: ["cuatro", "cinco"])
  arrayUnique: _echo(value: ["uno", "dos", "uno", "tres", "cuatro", "dos", "cinco", "dos"]) @arrayUnique
  arrayMerge: _echo(value: ["uno", "dos", "uno"]) @arrayMerge(with: ["tres", "cuatro", "dos", "cinco", "dos"])
  arrayDiff: _echo(value: ["uno", "dos"]) @arrayDiff (against: ["tres", "cuatro", "dos"])
  arrayFilter: _echo(value: ["uno", "dos", null, "tres", "", "dos", []]) @arrayFilter
  objectKeepProperties: _echo(value: { user: "Leo", email: "leo@test.com" } )
    @objectKeepProperties(
      keys: ["user"]
    )
}
```

...produces:

```json
{
  "data": {
    "_intAdd": 71,
    "_intArraySum": 15,
    "_arrayJoin": "Hello to everyone",
    "_arrayItem": "four",
    "_arraySearch": 2,
    "_arrayUnique": [
      "uno",
      "dos",
      "tres",
      "cuatro",
      "cinco"
    ],
    "_arrayMerge": [
      "uno",
      "dos",
      "uno",
      "tres",
      "cuatro",
      "dos",
      "cinco",
      "dos"
    ],
    "_arrayDiff": [
      "uno"
    ],
    "_arrayAddItem": [
      "uno",
      "dos",
      "tres"
    ],
    "_arraySetItem": [
      "tres",
      "dos"
    ],
    "_arrayKeys": [
      0,
      1,
      2
    ],
    "_arrayLength": 3,
    "_strRegexFindMatches": [
      [
        "https:\/\/gatographql.com"
      ],
      [
        "gatographql.com"
      ]
    ],
    "_strReplace": "http://gatographql.com",
    "_strReplaceMultiple": "http://doggraphql.com",
    "_strRegexReplace": "gatographql.com",
    "_strRegexReplaceMultiple": "gatographqlgatographql.comcom",
    "_strStartsWith": false,
    "_strEndsWith": true,
    "_strUpperCase": "HELLO WORLD",
    "_strLowerCase": "hello world",
    "_strTitleCase": "Hello World",
    "falseToTrue": true,
    "trueToFalse": false,
    "plusOne": 3,
    "objectAddEntry": {
      "user": "Leo",
      "contact": {
        email: "leo@test.com",
        "phone": "+0929094229",
        "methods": {
          "card": true
        }
      }
    },
    "upperCase": "HELLO WORLD",
    "lowerCase": "hello world",
    "titleCase": "Hello World",
    "append": "Hello world!!!",
    "prepend": "!!!Hello world",
    "arraySplice": [
      "uno"
    ],
    "arraySpliceWithLength": [
      "uno",
      "tres"
    ],
    "arraySpliceWithReplacement": [
      "uno",
      "cuatro",
      "cinco"
    ],
    "arraySpliceWithLengthAndReplacement": [
      "uno",
      "cuatro",
      "cinco",
      "tres"
    ],
    "arrayUnique": [
      "uno",
      "dos",
      "tres",
      "cuatro",
      "cinco"
    ],
    "arrayMerge": [
      "uno",
      "dos",
      "uno",
      "tres",
      "cuatro",
      "dos",
      "cinco",
      "dos"
    ],
    "arrayDiff": [
      "uno"
    ],
    "arrayFilter": [
      "uno",
      "dos",
      "tres",
      "dos"
    ],
    "objectKeepProperties": {
      "user": "Leo"
    }
  }
}
```

## Function Fields

Function fields are **Global Fields**, hence they are added to every single type in the GraphQL schema: in `QueryRoot`, but also in `Post`, `User`, etc.

This is the list of function fields.

### `_and`

Return an `AND` operation among several boolean properties.

### `_arrayAddItem`

Adds an element to the array.

### `_arrayCombine`

Create a JSON object using the elements from an array as keys, and the elements from another array as values.

### `_arrayChunk`

Split an array into chunks.

### `_arrayDiff`

Return an array containing all the elements from the first array which are not present on any of the other arrays.

### `_arrayFill`

Create an array filled with values.

### `_arrayFlipToObject`

Exchanges all numeric keys with their associated values in an array, returning an object.

### `_arrayInnerJoinJSONObjectProperties`

Fill the JSON objects inside a target array with properties from a JSON object from a source array, where a certain property is the same for both objects.

### `_arrayItem`

Access the element on the given position in the array.

### `_arrayJoin`

Join all the strings in an array, using a provided separator.

### `_arrayKeys`

Keys in an array.

### `_arrayLength`

Number of elements in an array.

### `_arrayMerge`

Merge two or more arrays together.

### `_arrayPad`

Pad an array to the specified length with a value.

### `_arrayRandom`

Randomly select one element from the provided ones.

### `_arrayRemoveFirst`

Remove the first element in the array.

### `_arrayRemoveLast`

Remove the last element in the array.

### `_arrayReverse`

Reverse an array.

### `_arraySearch`

Search in what position is an element placed in the array. If found, it returns its position, otherwise it returns `false`.

### `_arraySetItem`

Sets an element on some position of the array.

### `_arraySlice`

Extract a slice of an array.

### `_arraySplice`

Remove a portion of an array and replace it with something else.

### `_arrayUnique`

Filters out all duplicated elements in the array.

### `_date`

Returns a string formatted according to the given format string using the given integer `timestamp` (Unix timestamp) or the current time if no timestamp is given. In other words, `timestamp` is optional and defaults to the value of `time()` (provided via field `_time`).

### `_echo`

Repeat back the input, whatever it is.

### `_equals`

Indicate if the result from a field equals a certain value.

### `_floatCeil`

Rounds up a number to the next highest integer.

### `_floatDivide`

Divide a number by another number.

### `_greaterThan`

Indicate if number1 > number2.

### `_greaterThanOrEquals`

Indicate if number1 >= number2.

### `_htmlStripTags`

Strip HTML tags.

### `_if`

If a boolean property is true, execute a field, else, execute another field.

### `_inArray`

Indicate if the array contains the value

### `_intAdd`

Add an integer to another integer number.

### `_intArraySum`

Sum of the integer elements in the array.

### `_intMultiply`

Multiple an integer with another integer number.

### `_intSubstract`

Substract an integer from another integer number.

### `_isEmpty`

Indicate if a value is empty.

### `_isNull`

Indicate if a value is null.

### `_lowerThan`

Indicate if number1 < number2.

### `_lowerThanOrEquals`

Indicate if number1 <= number2.

### `_makeTime`

Returns the Unix timestamp corresponding to the arguments given. This timestamp is a long integer containing the number of seconds between the Unix Epoch (January 1 1970 00:00:00 GMT) and the time specified.

Any optional arguments omitted or null will be set to the current value according to the local date and time.

### `_not`

Return the opposite value of a boolean property.

### `_notEmpty`

Indicate if the value is not empty.

### `_notEquals`

Are the two values not equal to each other.

### `_notInArray`

Indicate if the array does not contain the value.

### `_notNull`

Indicate if the value is not `null`.

### `_objectAddEntry`

Adds an entry to the object.

### `_objectIntersectKey`

Computes the intersection of objects using keys for comparison.

### `_objectKeepProperties`

Keeps specific properties only in the JSON object.

### `_objectProperties`

Retrieve the properties in a JSON object.

### `_objectProperty`

Retrieve a property from a JSON object.

### `_objectRemoveEntry`

Removes an entry from the JSON object.

### `_objectRemoveProperties`

Removes one or more entries from the JSON object.

### `_objectValues`

Retrieve the values in a JSON object.

### `_or`

Return an `OR` operation among several boolean properties.

### `_propertyExistsInJSONObject`

Indicate if a property exists on a JSON object.

### `_propertyIsSetInJSONObject`

Indicate if a property exists and is not `null` on a JSON object.

### `_sprintf`

Replace placeholders inside a string with provided values.

### `_strAppend`

Append a string to another string.

### `_strBase64Encode`

Encodes data with MIME base64.

<!-- ### `_strBase64Decode`

Decodes data encoded with MIME base64, or `null` if the input contains character from outside the base64 alphabet. -->

### `_strContains`

Indicates if a string contains another string.

### `_strDecodeJSONObject`

Decode a string into a JSON object, or return `null` if it is not possible.

### `_strDecodeList`

Decode a string into an array (of any type), or return `null` if it is not possible.

### `_strEndsWith`

Indicates if a string ends with another string.

### `_strLength`

Length of the string.

### `_strLowerCase`

Transform a string to lower case.

### `_strPad`

Pad a string to a certain length with another string.

### `_strPos`

Position of a substring within the string, or `null` if not found.

### `_strRegexFindMatches`

Execute a regular expression to extract all matches from a string.

### `_strRegexReplace`

Execute a regular expression to search and replace a string.

### `_strRegexReplaceMultiple`

Execute regular expressions to search and replace strings.

### `_strRepeat`

Repeat a string

### `_strReplace`

Replace a string with another string.

### `_strReplaceMultiple`

Replace a list of strings with another list of strings.

### `_strReverse`

Reverse a string.

### `_strShuffle`

Randomly shuffles a string

### `_strStartsWith`

Indicates if a string starts with another string.

### `_strStripSlashes`

Returns a string with backslashes stripped off. (\\\' becomes \' and so on.) Double backslashes (\\\\) are made into a single backslash (\\).

### `_strSubstr`

Return part of a string

### `_strTitleCase`

Transform a string to title case.

### `_strToTime`

Parse about any English textual datetime description into a Unix timestamp

### `_strTrim`

Strip whitespace (or other characters) from the beginning and end of a string.

### `_strUpperCase`

Transform a string to upper case.

### `_strWordCount`

Number of words in the string

### `_time`

Return the time now.

## Function Directives

This is the list of function directives.

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

### `@floatDivide`

Divide the field value by a float number.

### `@intAdd`

Add an integer number to the field value.

### `@intMultiply`

Multiply an integer number with the field value.

### `@intSubstract`

Substract an integer number from the field value.

### `@objectAddEntry`

Add an entry to the JSON object.

### `@objectKeepProperties`

Keep specific properties only from the JSON object.

### `@objectRemoveEntry`

Removes an entry from the JSON object.

### `@objectRemoveProperties`

Remove specific properties from the JSON object.

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

### `@strRegexReplaceMultiple`

Execute regular expressions to search and replace a list of strings (see [documentation for PHP function `preg_replace`](https://www.php.net/manual/en/function.preg-replace.php)).

### `@strRepeat`

Repeat a string.

### `@strReplace`

Replace a string with another string.

### `@strReplaceMultiple`

Replace a list of strings with another list of strings.

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

### Function Fields

While we have a `Post.hasComments` fields, we may need the opposite value. Instead of creating a new field `Post.notHasComments` (for which we'd need to edit PHP code), we can use the **Field to Input** feature to input the value from `hasComments` into a `not` field, thus calculating the new value always within the GraphQL query:

```graphql
{
  posts {
    id
    hasComments
    notHasComments: _not(value: $__hasComments)
  }
}
```

We can apply function fields multiple times to perform a more complex calculation, such as generating a `summary` field based on the values from other fields:

```graphql
{
  posts {
    id
    content @remove
    shortContent: _strSubstr(string: $__content, offset: 0, length: 150) @remove
    excerpt @remove
    isExcerptEmpty: _isEmpty(value: $__excerpt) @remove
    summary: _if(
      condition: $__isExcerptEmpty
      then: $__content
      else: $__excerpt
    )
  }
}
```

In combination with the **HTTP Client** extension, we can dynamically generate an API endpoint to connect to (based on the data on our site), and then extract some specific field from the returned data:

```graphql
{
  users(
    pagination: { limit: 2 },
    sort: { order: ASC, by: ID }
  ) {
    id
    
    # Dynamically generate endpoint for the user
    endpoint: _arrayJoin(values: [
      "https://newapi.getpop.org/wp-json/wp/v2/users/",
      $__id,
      "?_fields=name,avatar_urls"
    ])
    
    # Retrieve the endpoint data
    endpointData: _sendJSONObjectItemHTTPRequest(input: { url: $__endpoint } )

    # Extract specific information
    userAvatar: _objectProperty(
      object: $__endpointData,
      by: {
        path: "avatar_urls.48"
      }
    )
  }
}
```

...producing:

```json
{
  "data": {
    "users": [
      {
        "id": 1,
        "endpoint": "https://newapi.getpop.org/wp-json/wp/v2/users/1?_fields=name,avatar_urls",
        "endpointData": {
          "name": "leo",
          "avatar_urls": {
            "24": "https://secure.gravatar.com/avatar/b28085726ee66e49f08be16ad668efd5?s=24&d=mm&r=g",
            "48": "https://secure.gravatar.com/avatar/b28085726ee66e49f08be16ad668efd5?s=48&d=mm&r=g",
            "96": "https://secure.gravatar.com/avatar/b28085726ee66e49f08be16ad668efd5?s=96&d=mm&r=g"
          },
          "_links": {
            "self": [
              {
                "href": "https://newapi.getpop.org/wp-json/wp/v2/users/1"
              }
            ],
            "collection": [
              {
                "href": "https://newapi.getpop.org/wp-json/wp/v2/users"
              }
            ]
          }
        },
        "userAvatar": "https://secure.gravatar.com/avatar/b28085726ee66e49f08be16ad668efd5?s=48&d=mm&r=g"
      },
      {
        "id": 2,
        "endpoint": "https://newapi.getpop.org/wp-json/wp/v2/users/2?_fields=name,avatar_urls",
        "endpointData": {
          "name": "themedemos",
          "avatar_urls": {
            "24": "https://secure.gravatar.com/avatar/7554514b65216821eeacde0fdcd6c6e6?s=24&d=mm&r=g",
            "48": "https://secure.gravatar.com/avatar/7554514b65216821eeacde0fdcd6c6e6?s=48&d=mm&r=g",
            "96": "https://secure.gravatar.com/avatar/7554514b65216821eeacde0fdcd6c6e6?s=96&d=mm&r=g"
          },
          "_links": {
            "self": [
              {
                "href": "https://newapi.getpop.org/wp-json/wp/v2/users/2"
              }
            ],
            "collection": [
              {
                "href": "https://newapi.getpop.org/wp-json/wp/v2/users"
              }
            ]
          }
        },
        "userAvatar": "https://secure.gravatar.com/avatar/7554514b65216821eeacde0fdcd6c6e6?s=48&d=mm&r=g"
      }
    ]
  }
}
```

### Function Directives

If this query:

```graphql
query {
  posts {
    title
  }
}
```

...produces these results:

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

...then this query:

```graphql
query {
  posts {
    title @strUpperCase
  }
}
```

...will produce:

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
<!-- 
## Bundles including extension

- [“All in One Toolbox for WordPress” Bundle](../../../../../bundle-extensions/all-in-one-toolbox-for-wordpress/docs/modules/all-in-one-toolbox-for-wordpress/en.md)
- [“Automated Content Translation & Sync for WordPress Multisite” Bundle](../../../../../bundle-extensions/automated-content-translation-and-sync-for-wordpress-multisite/docs/modules/automated-content-translation-and-sync-for-wordpress-multisite/en.md)
- [“Better WordPress Webhooks” Bundle](../../../../../bundle-extensions/better-wordpress-webhooks/docs/modules/better-wordpress-webhooks/en.md)
- [“Easy WordPress Bulk Transform & Update” Bundle](../../../../../bundle-extensions/easy-wordpress-bulk-transform-and-update/docs/modules/easy-wordpress-bulk-transform-and-update/en.md)
- [“Private GraphQL Server for WordPress” Bundle](../../../../../bundle-extensions/private-graphql-server-for-wordpress/docs/modules/private-graphql-server-for-wordpress/en.md)
- [“Selective Content Import, Export & Sync for WordPress” Bundle](../../../../../bundle-extensions/selective-content-import-export-and-sync-for-wordpress/docs/modules/selective-content-import-export-and-sync-for-wordpress/en.md)
- [“Simplest WordPress Content Translation” Bundle](../../../../../bundle-extensions/simplest-wordpress-content-translation/docs/modules/simplest-wordpress-content-translation/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md)
- [“Unhindered WordPress Email Notifications” Bundle](../../../../../bundle-extensions/unhindered-wordpress-email-notifications/docs/modules/unhindered-wordpress-email-notifications/en.md)
- [“Versatile WordPress Request API” Bundle](../../../../../bundle-extensions/versatile-wordpress-request-api/docs/modules/versatile-wordpress-request-api/en.md) -->
