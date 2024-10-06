# Query Functions

Manipulate the values of fields within the GraphQL query, via a collection of utilities and special directives providing meta-programming capabilities.

---

With **Field to Input**, obtain the value of a field, manipulate it, and input it into another field, all within the same query.

```graphql
query {
  posts {
    excerpt

    # Referencing previous field with name "excerpt"
    isEmptyExcerpt: _isEmpty(value: $__excerpt)

    # Referencing previous field with alias "isEmptyExcerpt"
    isNotEmptyExcerpt: _not(value: $__isEmptyExcerpt)
  }
}
```

---

With **Field Value Iteration and Manipulation**, meta directives for iterating and manipulating the value elements of array and object fields are added to the GraphQL schema:

1. `@underArrayItem`
2. `@underJSONObjectProperty`
3. `@underEachArrayItem`
4. `@underEachJSONObjectProperty`
5. `@objectClone`

`@underArrayItem` makes the nested directive be applied on a specific item from the array.

In the query below, only the first item in the array with the category names is transformed to uppercase:

```graphql
query {
  posts {
    categoryNames
      @underArrayItem(index: 0)
        @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "posts": {
      "categoryNames": [
        "NEWS",
        "sports"
      ]
    }
  }
}
```

---

With **Field on Field**, the `@applyField` directive is added to the GraphQL schema, to execute a certain field on the resolved field's value.

Applied to some field, the `@applyField` directive allows to execute another field (which is available on the same type and is applied on the same object), and either pass that resulting value along to another directive, or override the value of the field.

In the query below, the `Post.title` field for the object has value `"Hello world!"`. By adding `@applyField` to execute the field `_strUpperCase`:

```graphql
{
  post(by: { id: 1 }) {
    title
      @passOnwards(as: "input")
      @applyField(
        name: "_strUpperCase"
        arguments: {
          text: $input
        },
        setResultInResponse: true
      )
  }
}
```

...the field value is transformed to upper case, producing:

```json
{
  "data": {
    "post": {
      "title": "HELLO WORLD!"
    }
  }
}
```

---

With **Conditional Field Manipulation**, meta directives `@if` and `@unless` are added to the GraphQL schema, to conditionally execute a nested directive on the field.

`@if` executes its nested directives only if a condition has value `true`.

In this query, users `"Leo"` and `"Peter"` get their names converted to upper case, since they are in the "special user" array, while `"Martin"` does not:

```graphql
query {
  users {
    name
      @passOnwards(as: "userName")
      @applyField(
        name: "_inArray"
        arguments: {
          value: $userName
          array: ["Leo", "John", "Peter"]
        }
        passOnwardsAs: "isSpecialUser"
      )
      @if(
        condition: $isSpecialUser
      )
        @strUpperCase
  }
}
```

...producing:

```json
{
  "data": {
    "users": [
      {
        "name": "LEO"
      },
      {
        "name": "Martin"
      },
      {
        "name": "PETER"
      }
    ]
  }
}
```

---

With **Field Default Value**, the `@default` directive is added to the GraphQL schema, to set a value to null or empty fields.

In the example below, when a post does not have a featured image, field `featuredImage` returns `null`:

```graphql
{
  post(by: { id: 1 }) {
    featuredImage {
      id
      src
    }
  }
}
```

```json
{
  "data": {
    "post": {
      "featuredImage": null
    }
  }
}
```

By using `@default`, we can then retrieve some default image:

```graphql
{
  post(by: { id: 1 }) {
    featuredImage @default(value: 55) {
      id
      src
    }
  }
}
```

```json
{
  "data": {
    "post": {
      "featuredImage": {
        "id": 55,
        "src": "http://mysite.com/wp-content/uploads/my-default-image.png"
      }
    }
  }
}
```

---

With **Field Response Removal**, the `@remove` directive is added to the GraphQL schema, to remove the output of a field from the response.

In the query below, we generate the URL to send an HTTP request to, by concatenating the site domain and the REST API endpoint. As the values of these components are not of interest to us, there is no need to print them in the response, and we can `@remove` them:

```graphql
query {
  siteURL: optionValue(name: "siteurl")
    @remove

  requestURL: _sprintf(
    string: "%s/wp-json/wp/v2/comments/11/?_fields=id,content,date",
    values: [$__siteURL]
  )
    @remove

  _sendJSONObjectItemHTTPRequest(
    input: {
      url: $__requestURL
    }
  )
}
```

...producing this response (notice that fields `siteURL` and `requestURL` have been removed):

```json
{
  "data": {
    "_sendJSONObjectItemHTTPRequest": {
      "id": 11,
      "date": "2020-12-12T04:07:36",
      "content": {
        "rendered": "<p>Btw, I really like this stuff<\/p>\n"
      }
    }
  }
}
```

---

With **Response Error Trigger**, global field `_fail` and directive `@fail` are added to the GraphQL schema, to explicitly add an entry to the `errors` property in the response.

Field `_fail` adds the error always, and directive `@fail` whenever the condition under argument `condition` is met:

```graphql
query {
  _fail(message: "Some error")
  
  posts {
    featuredImage @fail(
      condition: IS_NULL,
      message: "The post does not have a featured image"
    ) {
      id
      src
    }
  }
  
  users {
    name @fail(
      condition: IS_EMPTY,
      message: "The retrieved user does not have a name"
    )
  }
}
```

<!-- ## List of bundled extensions

- [Conditional Field Manipulation](../../../../../extensions/conditional-field-manipulation/docs/modules/conditional-field-manipulation/en.md)
- [Field Default Value](../../../../../extensions/field-default-value/docs/modules/field-default-value/en.md)
- [Field on Field](../../../../../extensions/field-on-field/docs/modules/field-on-field/en.md)
- [Field Response Removal](../../../../../extensions/field-response-removal/docs/modules/field-response-removal/en.md)
- [Field To Input](../../../../../extensions/field-to-input/docs/modules/field-to-input/en.md)
- [Field Value Iteration and Manipulation](../../../../../extensions/field-value-iteration-and-manipulation/docs/modules/field-value-iteration-and-manipulation/en.md)
- [Response Error Trigger](../../../../../extensions/response-error-trigger/docs/modules/response-error-trigger/en.md) -->
