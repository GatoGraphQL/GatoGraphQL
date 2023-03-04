![GraphQL API with superpowers](https://graphql-by-pop.com/assets/superheroes.png)

# GraphQL by PoP

CMS-agnostic GraphQL server in PHP. Visit the site: [graphql-by-pop.com](https://graphql-by-pop.com)

## Install

GraphQL by PoP is currently available for WordPress. Follow [these instructions](https://graphql-by-pop.com/docs/getting-started/installation/wordpress.html) to install it.

## What it is

Implementation of a [GraphQL](https://graphql.org) server, fully compliant of the [spec](https://spec.graphql.org/). The core development is under the [GraphQL server](packages/graphql-server) package.

### Fetching data through GraphQL

Send a GraphQL query to your API and get exactly the data you need.

```graphql
query PostsWithComments {
  posts {
    title
    comments {
      date
      content
      author {
        name
      }
    }
  }
}
```

The response from the API always mirrors the GraphQL query.

```json
{
  "data": {
    "posts": [
      {
        "title": "Hello world!",
        "comments": [
          {
            "date": "2020-04-08",
            "content": "Check us out!",
            "author": {
              "name": "Leo"
            }
          }
        ]
      }
    ]
  }
}
```

## Features
 
- **Fast:** Queries are resolved in linear time on the number of types involved
- **Simple:** Resolvers deal with IDs (instead of objects), removing the need to implement batching/deferred
- **Extensible:** Types have their resolvers attached by injection, and resolvers can override each other
- **Powerful:** The engine is based on executing directives, allowing any type of custom functionality

## Architectural decisions

### Code-first approach

The schema is generated from code. Work with your teammates concurrently on the schema without conflicts, without tooling, and without bureaucracy.

### Dynamic schema

The resolvers attach themselves to the types. Each field can be handled by different resolvers, and the chosen one is selected on runtime, depending on the context.

### Public/private schema managed through Access Control Lists

From a single source of truth, expose different schemas for different users, managing it through Access Control Lists based on the user being logged-in or not, roles, capabilities, IP, or custom rules.

### Field/directive-based versioning

In addition to evolving the GraphQL schema, every field and directive can be independently versioned, and the specific version to use is chosen through field/directive arguments in the query.

### Robust caching

Cache the response from the query in several layers (server, CDN, etc) using standard HTTP caching, defining the max age field by field. Cache the results from expensive operations in disk or memory, defining the expiry time field by field.

### Nested mutations

Perform mutations on every type, not just on the root type, and have a mutation be executed on the result from another mutation. The schema gets neater and slimmer!

### Automatic namespacing

No need to worry if different teams or 3rd party providers using the same names for their types. Create neater schemas by removing the 'MyCompanyName' prefix from your types, you won't need it.

### Proactive warnings/deprecations

Issues with the query are shown in the response to the query, and not just when doing introspection. Avoid your users from never finding out that your schema has been upgraded!

## Documentation and Resources

Please check them on [graphql-by-pop.com](https://graphql-by-pop.com/):

- [Docs](https://graphql-by-pop.com/docs/)
- [Resources](https://graphql-by-pop.com/resources/)