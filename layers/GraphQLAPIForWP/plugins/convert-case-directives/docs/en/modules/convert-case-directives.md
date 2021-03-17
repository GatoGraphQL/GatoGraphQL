# Convert Case Directives

Set of directives to manipulate strings:

- `@upperCase`: converts the text to uppercase => `"HELLO FRIENDS"`
- `@lowerCase`: converts the text to lowercase => `"hello friends"`
- `@titleCase`: converts the text to title case => `"Hello Friends"`

## Usage

For instance, if these query produces the results below:

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
    title @upperCase
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
