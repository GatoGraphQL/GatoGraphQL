# GraphQL Parser

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Syntax to query GraphQL through URL params, which grants a GraphQL API the capability to be cached on the server.

## Install

Via Composer

```php bash
composer require getpop/graphql-parser
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`Engine/packages/graphql-parser`](https://github.com/leoloso/PoP/tree/master/layers/Engine/packages/graphql-parser).

## Usage

Initialize the component:

``` php
\PoP\Root\AppLoader::addComponentClassesToInitialize([
    \PoP\GraphQLParser\Component::class,
]);
```

Use it:

```php
use PoP\GraphQLParser\Facades\Query\GraphQLParserInterpreterFacade;

$fieldQueryInterpreter = GraphQLParserInterpreterFacade::getInstance();

// To create a field from its elements
$field = $fieldQueryInterpreter->getField($fieldName, $fieldArgs, $fieldAlias, $skipOutputIfNull, $fieldDirectives);

// To retrieve the elements from a field
$fieldName = $fieldQueryInterpreter->getFieldName($field);
$fieldArgs = $fieldQueryInterpreter->getFieldArgs($field);
$fieldAlias = $fieldQueryInterpreter->getFieldAlias($field);
$skipOutputIfNull = $fieldQueryInterpreter->isSkipOuputIfNullField($field);
$fieldDirectives = $fieldQueryInterpreter->getFieldDirectives($field);

// All other functions listed in GraphQLParserInterpreterInterface
// ...
```

## Why

The GraphQL query generally spans multiple lines, and it is provided through the body of the request instead of through URL params. As a result, it is difficult to cache the results from a GraphQL query in the server. In order to support server-side caching on GraphQL, we can attempt to provide the query through the URL instead, as to use standard mechanisms which cache a page based on the URL as its unique ID.

The syntax described and implemented in this project is a re-imagining of the GraphQL syntax, supporting all the same elements (field arguments, variables, aliases, fragments, directives, etc), however designed to be easy to write, and easy to read and understand, in a single line, so it can be passed as a URL param.

Being able to pass the query as a URL param has, in turn, several other advantages:

- It removes the need for a client-side library to convert the GraphQL query into the required format (such as [Relay](https://relay.dev/docs/en/graphql-in-relay)), leading to performance improvements and reduced amount of code to maintain
- The GraphQL API becomes easier to consume (same as REST), and avoids depending on a special client (such as [GraphiQL](https://github.com/graphql/graphiql)) to visualize the results of the query

## Who uses it

[PoP](https://github.com/leoloso/PoP) uses this syntax natively: To load data in each component within the application itself (as done by the [Component Model](https://github.com/getpop/component-model)), and to load data from an API through URL param `query` (as done by the [PoP API](https://github.com/getpop/api)).

A GraphQL server can implement this syntax as to support URI-based server-side caching. To achieve this, a service must translate the query from this syntax to the corresponding [GraphQL syntax](https://graphql.org/learn/queries/), and then pass the translated query to the GraphQL engine.

## Syntax

[Similar to GraphQL](https://graphql.org/learn/queries/#fields), the query describes a set of “fields”, where each field can contain the following elements:

- **The field name:** What data to retrieve
- **Field arguments:** How to filter the data, or format the results
- **Field alias:** How to name the field in the response
- **Field directives:** To change the behaviour of how to execute the operation

Differently than GraphQL, a field can also contain the following elements:

- **Property names in the field arguments may be optional:** To simplify passing arguments to the field
- **Bookmarks:** To keep loading data from an already-defined field
- **Operators and Helpers:** Standard operations (`and`, `or`, `if`, `isNull`, etc) and helpers to access environment variables (among other use cases) can be already available as fields
- **Composable fields:** The response of a field can be used as input to another field, through its arguments or field directives
- **Skip output if null:** To ignore the output if the value of the field is null
- **Composable directives:** A directive can modify the behaviour of other, nested directives
- **Expressions:** To pass values across directives

From the composing elements, only the field name is mandatory; all others are optional. A field is composed in this order:

1. The field name
2. Arguments: `(...)`
3. Bookmark: `[...]`
4. Alias: `@...` (if the bookmark is also present, it is placed inside)
5. Skip output if null: `?`
6. Directives: directive name and arguments: `<directiveName(...)>`

The field looks like this:

```php
fieldName(fieldArgs)[@alias]?<fieldDirective(directiveArgs)>
```

To make it clearer to visualize, the query can be split into several lines:

```php
fieldName(
  fieldArgs
)[@alias]?<
  fieldDirective(
    directiveArgs
  )
>
```

> **Note:**<br/>Firefox already handles the multi-line query: Copy/pasting it into the URL bar works perfectly. To try it out, copy `https://newapi.getpop.org/api/graphql/` followed by the query presented in each example into Firefox's URL bar, and voilà, the query should execute.
> 
> Chrome and Safari do not work as well: They require to strip all the whitespaces and line returns before pasting the query into the URL bar.
> 
> Conclusion: use Firefox!

To retrieve several fields in the same query, we join them using `,`:

```php
fieldName1@alias1,
fieldName2(
  fieldArgs2
)[@alias2]?<
  fieldDirective2
>
```

### Retrieving properties from a node

Separate the properties to fetch using `|`.

_**In GraphQL**:_

```graphql
query {
  id
  fullSchema
}
```

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=id|fullSchema)):_

```php
/?query=
  id|
  fullSchema
```

### Retrieving nested properties

To fetch relational or nested data, describe the path to the property using `.`.

_**In GraphQL**:_

```graphql
query {
  posts {
    author {
      id
    }
  }
}
```

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.author.id)):_

```php
/?query=
  posts.
    author.
      id
```

We can use `|` to bring more than one property when reaching the node:

_**In GraphQL**:_

```graphql
query {
  posts {
    author {
      id
      name
      url
    }
  }
}
```

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.author.id|name|url)):_

```php
/?query=
  posts.
    author.
      id|
      name|
      url
```

Symbols `.` and `|` can be mixed together to also bring properties along the path:

_**In GraphQL**:_

```graphql
query {
  posts {
    id
    title
    author {
      id
      name
      url
    }
  }
}
```

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|author.id|name|url)):_

```php
/?query=
  posts.
    id|
    title|
    author.
      id|
      name|
      url
```

### Appending fields

Combine multiple fields by joining them using `,`.

_**In GraphQL**:_

```graphql
query {
  posts {
    author {
      id
      name
      url
    }
    comments {
      id
      content
    }
  }
}
```

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.author.id|name|url,posts.comments.id|content)):_

```php
/?query=
  posts.
    author.
      id|
      name|
      url,
  posts.
    comments.
      id|
      content
```

### Appending fields with strict execution order

_This is a syntax + functionality feature._ Combine multiple fields by joining them using `;`, telling the data-loading engine to resolve the fields on the right side of the `;` only after resolving all the fields on the left side.

The closest equivalent in GraphQL is the same as the previous case with `,`, however this syntax does not modify the behavior of the server.

_**In GraphQL**:_

```graphql
query {
  posts {
    author {
      id
      name
      url
    }
    comments {
      id
      content
    }
  }
}
```

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.author.id|name|url;posts.comments.id|content)):_

```php
/?query=
  posts.
    author.
      id|
      name|
      url;
      
  posts.
    comments.
      id|
      content
```

In the GraphQL server, the previous query is resolved as this one (with `self` being used to delay when a field is resolved):

```php
/?query=
  posts.
    author.
      id|
      name|
      url,
  self.
    self.
      posts.
        comments.
        id|
        content
```

### Field arguments

Field arguments is an array of properties, to filter the results (when applied to a property along a path) or modify the output (when applied to a property on a leaf node) from the field. These are enclosed using `()`, defined using `:` to separate the property name from the value (becoming `name:value`), and separated using `,`.

Values do not need be enclosed using quotes `"..."`.

_Filtering results **in GraphQL**:_

```graphql
query {
  posts(filter:{ search: "template" }) {
    id
    title
    date
  }
}
```

_Filtering results **in PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts(filter:{search:template}).id|title|date)):_

```php
/?query=
  posts(filter: { search: template }).
    id|
    title|
    date
```

_Formatting output **in GraphQL**:_

```graphql
query {
  posts {
    id
    title
    dateStr(format: "d/m/Y")
  }
}
```

_Formatting output **in PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|dateStr(format:d/m/Y))):_

```php
/?query=
  posts.
    id|
    title|
    dateStr(format:d/m/Y)
```

### Optional property name in field arguments

Defining the argument name can be ignored if it can be deduced from the schema (for instance, the name can be deduced from the position of the property within the arguments in the schema definition).

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|dateStr(d/m/Y))):_

```php
/?query=
  posts.
    id|
    title|
    dateStr(d/m/Y)
```

### Aliases

An alias defines under what name to output the field. The alias name must be prepended with `@`:

_**In GraphQL**:_

```graphql
query {
  posts {
    id
    title
    formattedDate: dateStr(format: "d/m/Y")
  }
}
```

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|dateStr(d/m/Y)@formattedDate)):_

```php
/?query=
  posts.
    id|
    title|
    dateStr(d/m/Y)@formattedDate
```

Please notice that aliases are optional, differently than in GraphQL. [In GraphQL](https://graphql.org/learn/queries/#aliases), because the field arguments are not part of the field in the response, when querying the same field with different arguments it is required to use an alias to differentiate them. In PoP, however, field arguments are part of the field in the response, which already differentiates the fields.

_**In GraphQL**:_

```graphql
query {
  posts {
    id
    title
    date: date
    formattedDate: dateStr(format: "d/m/Y")
  }
}
```

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|dateStr|dateStr(d/m/Y))):_

```php
/?query=posts.
  id|
  title|
  dateStr|
  dateStr(d/m/Y)
```

### Bookmarks

When iterating down a field path, loading data from different sub-branches is visually appealing in GraphQL:

_**In GraphQL**:_

```graphql
query {
  users {
    posts {
      author {
        id
        name
      }
      comments {
        id
        content
      }
    }
  }
}
```

In PoP, however, the query can become very verbose, because when combining fields with `,` it starts iterating the path again all the way from the root:

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=users.posts.author.id|name,users.posts.comments.id|content)):_

```php
/?query=
  users.
    posts.
      author.
        id|
        name,
  users.
    posts.
      comments.
        id|
        content
```

Bookmarks help address this problem by creating a shortcut to a path, so we can conveniently keep loading data from that point on. To define the bookmark, its name is enclosed with `[...]` when iterating down the path, and to use it, its name is similarly enclosed with `[...]`:

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=users.posts[userposts].author.id|name,[userposts].comments.id|content)):_

```php
/?query=
  users.
    posts[userposts].
      author.
        id|
        name,
    [userposts].
      comments.
        id|
        content
```

### Bookmark with Alias

When we need to define both a bookmark to a path, and an alias to output the field, these 2 must be combined: The alias, prepended with `@`, is placed within the bookmark delimiters `[...]`.

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=users.posts[@userposts].author.id|name,[userposts].comments.id|content)):_

```php
/?query=
  users.
    posts[@userposts].
      author.
        id|
        name,
    [userposts].
      comments.id|
      content
```

### Variables

Variables can be used to input values to field arguments. While [in GraphQL](https://graphql.org/learn/queries/#variables) the values to resolve to are defined within the body (in a separate dictionary than the query), in PoP these are retrieved from the request (`$_GET` or `$_POST`). 

The variable name must be prepended with `$`, and its value in the request can be defined either directly under the variable name, or under entry `variables` and then the variable name. 

_API call **in GraphQL**:_

```json
{
  "query":"query ($format: String) {
    posts {
      id
      title
      dateStr(format: $format)
    }
  }",
  "variables":"{
    \"format\":\"d/m/Y\"
  }"
}
```

_**In PoP** (View in browser: [query 1](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|dateStr($format)&format=d/m/Y), [query 2](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|dateStr($format)&variables[format]=d/m/Y)):_

```php
1. /?
  format=d/m/Y&
  query=
    posts.
      id|
      title|
      dateStr($format)

2. /?
  variables[format]=d/m/Y&
  query=
    posts.
      id|
      title|
      dateStr($format)
```

### Fragments

Fragments enable to re-use query sections. Similar to variables, their resolution is defined in the request (`$_GET` or `$_POST`). Unlike [in GraphQL](https://graphql.org/learn/queries/#fragments), the fragment does not need to indicate on which schema type it operates.

The fragment name must be prepended with `--`, and the query they resolve to can be defined either directly under the fragment name, or under entry `fragments` and then the fragment name. 

While a fragment can contain `|` (to split fields), it cannot contain `;` (to split operations) or `,` (to split queries), to avoid confusion (since these are computed from the root of the query, not the fragment).

_**In GraphQL**:_

```graphql
query {
  users {
    ...userData
    posts {
      comments {
        author {
          ...userData
        }
      }
    }
  }
}

fragment userData on User {
  id
  name
  url
}
```

_**In PoP** (View in browser: [query 1](https://nextapi.getpop.org/api/graphql/?query=users.--userData|posts.comments.author.--userData&userData=id|name|url), [query 2](https://nextapi.getpop.org/api/graphql/?query=users.--userData|posts.comments.author.--userData&fragments[userData]=id|name|url)):_

```php
1. /?
userData=
  id|
  name|
  url&
query=
  users.
    --userData|
    posts.
      comments.
        author.
          --userData

2. /?
fragments[userData]=
  id|
  name|
  url&
query=
  users.
    --userData|
    posts.
      comments.
        author.
          --userData
```

### Directives

A directive enables to modify if/how the operation to fetch data is executed. Each field accepts many directives, each of them receiving its own arguments to customize its behaviour. The set of directives is surrounded by `<...>`, the directives within must be separated by `,`, and their arguments follows the same syntax as field arguments: they are surrounded by `(...)`, and its pairs of `name:value` are separated by `,`.

_**In GraphQL**:_

```graphql
query {
  posts {
    id
    title
    featuredImage @include(if: $addFeaturedImage) {
      id
      src
    }
  }
}
```

_**In PoP** (View in browser: [query 1](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|featuredImage<include(if:$include)>.id|src&include=true), [query 2](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|featuredImage<include(if:$include)>.id|src&include=false), [query 3](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|featuredImage<skip(if:$skip)>.id|src&skip=true), [query 4](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|featuredImage<skip(if:$skip)>.id|src&skip=false)):_

```php
1. /?
include=true&
query=
  posts.
    id|
    title|
    featuredImage<
      include(if:$include)
    >.
      id|
      src

2. /?
include=false&
query=
  posts.
    id|
    title|
    featuredImage<
      include(if:$include)
    >.
      id|
      src

3. /?
skip=true&
query=
  posts.
    id|
    title|
    featuredImage<
      skip(if:$skip)
    >.
      id|
      src

4. /?
skip=false&
query=
  posts.
    id|
    title|
    featuredImage<
      skip(if:$skip)
    >.
      id|
      src
```

### Operators and Helpers

Standard operations, such as `and`, `or`, `if`, `isNull`, `contains`, `sprintf` and many others, can be made available on the API as fields. Then, the operator name stands for the field name, and it can accept all the other elements in the same format (arguments, aliases, etc). 

To pass an argument value as an array, we enclose it between `[]` and split its items with `,`. The format can be just `value` (numeric array) or `key:value` (indexed array).

_**In PoP** (View in browser: <a href="https://nextapi.getpop.org/api/graphql?query=not(true)">query 1</a>, <a href="https://nextapi.getpop.org/api/graphql?query=or([1,0])">query 2</a>, <a href="https://nextapi.getpop.org/api/graphql?query=and([1,0])">query 3</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=if(true,%20Show%20this%20text,%20Hide%20this%20text)">query 4</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=equals(first%20text,%20second%20text)">query 5</a>, <a href="https://nextapi.getpop.org/api/graphql?query=isNull(),isNull(something)">query 6</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=sprintf(%s%20is%20%s,%20[PoP%20API,cool])">query 7</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=echo([name:%20PoP%20API,status:%20cool])">query 8</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=arrayValues([name:%20PoP%20API,status:%20cool])">query 9</a>):_

```php
1. /?query=not(true)

2. /?query=or([1, 0])

3. /?query=and([1, 0])

4. /?query=if(true, Show this text, Hide this text)

5. /?query=equals(first text, second text)

6. /?query=isNull(),isNull(something)

7. /?query=sprintf(
  %s is %s, [
    PoP API, 
    cool
  ])

8. /?query=echo([
  name: PoP API,
  status: cool
])

9. /?query=arrayValues([
  name: PoP API,
  status: cool
])
```

In the same fashion, helper functions can provide any required information, also behaving as fields. For instance, helper `context` provides the values in the system's state, and helper `var` can retrieve any specific variable from the system's state.

_**In PoP** (View in browser: <a href="https://nextapi.getpop.org/api/graphql?query=context">query 1</a>, <a href="https://nextapi.getpop.org/api/graphql?query=var(route)|var(target)@target|var(datastructure)">query 2</a>):_

```php
1. /?query=context

2. /?query=
  var(route)|
  var(target)@target|
  var(datastructure)
```

### Composable fields

The real benefit from having operators comes when they can receive the output from a field as their input. Since an operator is a field by itself, this can be generalized into “composable fields”: Passing the result of any field as an argument value to another field. 

In order to distinguish if the input to the field is a string or the name of a field, the field must have field arguments brackets `(...)` (if no arguments, then simply `()`). For instance, `"id"` means the string "id", and `"id()"` means to execute and pass the result from field "id".

_**In PoP** (View in browser: <a href="https://nextapi.getpop.org/api/graphql/?query=posts.hasComments|not(hasComments())">query 1</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=posts.hasComments|hasFeaturedImage|or([hasComments(),hasFeaturedImage()])">query 2</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=var(fetching-site),posts.hasFeaturedImage|and([hasFeaturedImage(), var(fetching-site)])">query 3</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=posts.if(hasComments(),sprintf(Post with title '%s' has %s comments,[title(), commentCount()]),sprintf(Post with ID %s was created on %s, [id(),dateStr(d/m/Y)]))@postDesc">query 4</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=users.name|equals(name(), leo)">query 5</a>, <a href="https://nextapi.getpop.org/api/graphql/?query=posts.featuredImage|isNull(featuredImage())">query 6</a>):_

```php
1. /?query=
  posts.
    hasComments|
    not(hasComments())

2. /?query=
  posts.
    hasComments|
    hasFeaturedImage|
    or([
      hasComments(),
      hasFeaturedImage()
    ])

3. /?query=
  var(fetching-site)|
  posts.
    hasFeaturedImage|
    and([
      hasFeaturedImage(),
      var(fetching-site)
    ])

4. /?query=
  posts.
  if (
    hasComments(),
    sprintf(
      Post with title '%s' has %s comments, [
      title(), 
      commentCount()
    ]),
    sprintf(
      Post with ID %s was created on %s, [
      id(),
      dateStr(d/m/Y)
    ])
  )@postDesc

5. /?query=users.
  name|
  equals(
    name(), 
    leo
  )

6. /?query=
  posts.
    featuredImage|
    isNull(featuredImage())
```

In order to include characters `(` and `)` as part of the query string, and avoid treating the string as a field to be executed, we must enclose it using quotes: `"..."`.

_**In PoP** (<a href='https://nextapi.getpop.org/api/graphql/?query=posts.sprintf("This post has %s comment(s)",[commentCount()])@postDesc'>View query in browser</a>):_

```php
/?query=
  posts.
    sprintf(
      "This post has %s comment(s)", [
      commentCount()
    ])@postDesc
```

### Composable fields with directives

Composable fields enable to execute an operation against the queried object itself. Making use of this capability, directives in PoP become much more useful, since they can evaluate their conditions against each and every object independently. This feature can give raise to a myriad of new features, such as client-directed content manipulation, fine-grained access control, enhanced validations, and many others.

For instance, the GraphQL spec [requires](https://graphql.org/learn/queries/#directives) to support directives `include` and `skip`, which receive a parameter `if` with a boolean value. While GraphQL expects this value to be provided through a variable (as shown in section [Directives](#directives) above), in PoP it can be retrieved from the object.

_**In PoP** (View in browser: [query 1](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|featuredImage<include(if:not(isNull(featuredImage())))>.id|src), [query 2](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|featuredImage<skip(if:isNull(featuredImage()))>.id|src)):_

```php
1. /?query=
  posts.
    id|
    title|
    featuredImage<
      include(if:not(isNull(featuredImage())))
    >.
      id|
      src

2. /?query=
  posts.
    id|
    title|
    featuredImage<
      skip(if:isNull(featuredImage()))
    >.
      id|
      src
```

### Skip output if null

Whenever the value from a field is null, its nested fields will not be retrieved. For instance, consider the following case, in which field `"featuredImage"` sometimes is null:

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|featuredImage.id|src)):_

```php
/?query=
  posts.
    id|
    title|
    featuredImage.
      id|
      src
```

As we have seen in section [Composable fields with directives](#composable-fields-with-directives) above, by combining directives `include` and `skip` with composable fields, we can decide to not output a field when its value is null. However, the query to execute this behaviour includes a directive added in the middle of the query path, making it very verbose and less legible. Since this is a very common use case, it makes sense to generalize it and incorporate a simplified version of it into the syntax. 

For this, PoP introduces symbol `?`, to be placed after the field name (and its field arguments, alias and bookmark), to indicate "if this value is null, do not output it". 

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|featuredImage?.id|src)):_

```php
/?query=
  posts.
    id|
    title|
    featuredImage?.
      id|
      src
```

### Composable directives and Expressions

Directives can be nested: An outer directive can modify the behaviour of its inner, nested directive(s). It can pass values across to its composed directives through “expressions”, variables surrounded with `%...%` which can be created on runtime (coded as part of the query), or be defined in the directive itself.

In the example below, directive `<forEach>` iterates through the elements of the array, for its composed directive `<applyFunction>` to do something with each of them. It passes the array item through pre-defined expression `%{value}%` (coded within the directive).

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=echo(%5B%5Bbanana,apple%5D,%5Bstrawberry,grape,melon%5D%5D)@fruitJoin%3CforEach%3CapplyFunction(function:arrayJoin,addArguments:%5Barray:%{value}%,separator:%22---%22%5D)%3E%3E)):_

```php
/?query=
  echo([
    [banana, apple],
    [strawberry, grape, melon]
  ])@fruitJoin<
    forEach<
      applyFunction(
        function: arrayJoin,
        addArguments: [
          array: %{value}%,
          separator: "---"
        ]
      )
    >
  >
```

In the example below, directive `<advancePointerInArrayOrObject>` communicates to directive `<translate>` the language to translate to through expression `%{toLang}%`, which is defined on-the-fly.

_**In PoP** (<a href="https://nextapi.getpop.org/api/graphql/?query=echo([{text:Hello my friends,translateTo:fr},{text:How do you like this software so far?,translateTo:es}])@translated<forEach<advancePointerInArrayOrObject(path:text,appendExpressions:{toLang:extract(%{value}%,translateTo)})<translateMultiple(from:en,to:%{toLang}%,oneLanguagePerField:true)>>>">View query in browser</a>):_

```php
/?query=
  echo([
    {
      text: Hello my friends,
      translateTo: fr
    },
    {
      text: How do you like this software so far?,
      translateTo: es
    }
  ])@translated<
    forEach<
      advancePointerInArrayOrObject(
        path: text,
        appendExpressions: {
          toLang:extract(%{value}%,translateTo)
        }
      )<
        translateMultiple(
          from: en,
          to: %{toLang}%,
          oneLanguagePerField: true
        )
      >
    >
  >
```

### Combining elements

Different elements can be combined, such as the following examples. 

A fragment can contain nested paths, variables, directives and other fragments:

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts(pagination:{limit:$limit}).--postData|author.posts(pagination:{limit:$limit}).--postData&postData=id|title|--nestedPostData|dateStr(format:$format)&nestedPostData=comments<include(if:$include)>.id|content&format=d/m/Y&include=true&limit=3)):_

```php
/?
postData=
  id|
  title|
  --nestedPostData|
  dateStr(format:$format)&
nestedPostData=
  comments<
    include(if:$include)
  >.
    id|
    content&
format=d/m/Y&
include=true&
limit=3&
order=title&
query=
  posts(
    pagination: {
      limit:$limit
    },
    sort: {
      order:$order
    }
  ).
    --postData|
    author.
      posts(
        pagination: {
          limit:$limit
        }
      ).
        --postData
```

A fragment can contain directives, which are transferred into the fragment resolution fields:

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|--props<include(if:hasComments())>&fragments[props]=title|date)):_

```php
/?
fragments[props]=
  title|
  date& 
query=
  posts.
    id|
    --props<
      include(if:hasComments())
    >
```

If the field in the fragment resolution field already has its own directives, these are applied; otherwise, the directives from the fragment definition are applied:

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|--props<include(if:hasComments())>&fragments[props]=title|url<include(if:not(hasComments()))>)):_

```php
/?
fragments[props]=
  title|
  url<
    include(if:not(hasComments()))
  >&
query=
  posts.
    id|
    --props<
      include(if:hasComments())
    >
```

A fragment can contain an alias, which is then transferred to all fragment resolution fields as an enumerated alias (`@aliasName1`, `@aliasName2`, etc):

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|--props@prop&fragments[props]=title|url|featuredImage)):_

```php
/?
fragments[props]=
  title|
  url|
  featuredImage&
query=
  posts.
    id|
    --props@prop
```

<!-- Combined with directives, we can query several fields at the same time without having them override their results:

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|--props@original|--props@translated<translate(from:en,to:es)>&fragments[props]=title|content)):_

```php
/?query=posts.id|--props@original|--props@translated<translate(from:en,to:es)>&fragments[props]=title|content
``` -->

A fragment can contain the "Skip output if null" symbol, which is then transferred to all fragment resolution fields:

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|--props?&fragments[props]=title|url|featuredImage)):_

```php
/?
fragments[props]=
  title|
  url|
  featuredImage&
query=
  posts.
    id|
    --props?
```

Combining both directives and the skip output if null symbol with fragments:

_**In PoP** ([View query in browser](https://nextapi.getpop.org/api/graphql/?query=posts.id|hasComments|--props?<include(if:hasComments())>&fragments[props]=title|url<include(if:hasComments())>|featuredImage)):_

```php
/?
fragments[props]=
  title|
  url<
    include(if:hasComments())
  >|
  featuredImage&
query=
  posts.
    id|
    hasComments|
    --props?<
      include(if:hasComments())
    >
```

<!--
## Query examples

Field arguments:

- Order posts by title: [posts(order:title|asc)](https://nextapi.getpop.org/api/graphql/?query=posts(order:title|asc).id|title|url|date)
- Search "template" and limit it to 3 results: [posts(searchfor:template,limit:3)](https://nextapi.getpop.org/api/graphql/?query=posts(searchfor:template,limit:3).id|title|url|date)
- Format a date: [posts.date(format:d/m/Y)](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|url|date(format:d/m/Y))

Alias:

- [posts(order:title|asc)@orderedposts](https://nextapi.getpop.org/api/graphql/?query=posts(order:title|asc)@orderedposts.id|title|url|date)
- [posts.date(format:d/m/Y)@formatteddate](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|url|date(format:d/m/Y)@formatteddate)

Bookmark:

- [posts.comments[comments].author.id|name,[comments].post.id|title](https://nextapi.getpop.org/api/graphql/?query=posts.comments[comments].author.id|name,[comments].post.id|title)



Bookmark with alias:

- [posts.comments[@postcomments].author.id|name,[postcomments].post.id|title](https://nextapi.getpop.org/api/graphql/?query=posts.comments[@postcomments].author.id|name,[postcomments].post.id|title)



Variables:

- [posts(searchfor:$term,limit:$limit).id|title&variables[limit]=3&term=template](https://nextapi.getpop.org/api/graphql/?query=posts(searchfor:$term,limit:$limit).id|title&variables[limit]=3&term=template)

Fragments:

- [posts(limit:2).--fr1,users(id:1).posts.--fr1&fragments[fr1]=id|author.posts(limit:1).id|title](https://nextapi.getpop.org/api/graphql/?query=posts(limit:2).--fr1,users(id:1).posts.--fr1&fragments[fr1]=id|author.posts(limit:1).id|title)


Directives:

- [posts.id|title|url<include(if:$include)>&variables[include]=true](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|url<include(if:$include)>&variables[include]=true)
- [posts.id|title|url<include(if:$include)>&variables[include]=](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|url<include(if:$include)>&variables[include]=)

-->

## PHP versions

Requirements:

- PHP 8.0+ for development
- PHP 7.1+ for production

### Supported PHP features

Check the list of [Supported PHP features in `leoloso/PoP`](https://github.com/leoloso/PoP/blob/master/docs/supported-php-features.md)

### Preview downgrade to PHP 7.1

Via [Rector](https://github.com/rectorphp/rector) (dry-run mode):

```bash
composer preview-code-downgrade
```

## Standards

[PSR-1](https://www.php-fig.org/psr/psr-1), [PSR-4](https://www.php-fig.org/psr/psr-4) and [PSR-12](https://www.php-fig.org/psr/psr-12).

To check the coding standards via [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer), run:

``` bash
composer check-style
```

To automatically fix issues, run:

``` bash
composer fix-style
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```php bash
$ composer test
```

## Report issues

To report a bug or request a new feature please do it on the [PoP monorepo issue tracker](https://github.com/leoloso/PoP/issues).

## Contributing

We welcome contributions for this package on the [PoP monorepo](https://github.com/leoloso/PoP) (where the source code for this package is hosted).

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

GNU General Public License v2 (or later). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/getpop/graphql-parser.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/graphql-parser/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/graphql-parser.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/graphql-parser.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/graphql-parser.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/graphql-parser
[link-travis]: https://travis-ci.org/getpop/graphql-parser
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/graphql-parser/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/graphql-parser
[link-downloads]: https://packagist.org/packages/getpop/graphql-parser
[link-author]: https://github.com/leoloso
[link-contributors]: ../../../../../../contributors
