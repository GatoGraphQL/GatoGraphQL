# How is the GraphQL server CMS-agnostic

[GraphQL by PoP](../layers/GraphQLByPoP) is a CMS-agnostic GraphQL server. What does that mean? For that matter, [`webonyx/graphql-php`](https://github.com/webonyx/graphql-php) also is CMS-agnostic. So how are they different?

`webonyx/graphql-php` is CMS-agnostic, in that it is a package distributed via Composer, containing only "vanilla" PHP code. However, it is not a GraphQL server all by itself; instead, it is an implementation in PHP of the GraphQL specification, to be embedded within some GraphQL server in PHP.

Now, these implementing GraphQL servers, such as Lighthouse or WPGraphQL, are not CMS-agnostic. We can't run Lighthouse on WordPress, or WPGraphQL on Laravel.

It is in this sense that GraphQL by PoP is CMS-agnostic: it is the "almost-final" GraphQL server, almost ready to run with any CMS or framework, whether Laravel, WordPress, or any other.

## Additional resources

- [GraphQL by PoP - CMS-agnosticism](https://graphql-by-pop.com/docs/architecture/cms-agnosticism.html)
- [GraphQL by PoP - Building a CMS-agnostic API](https://graphql-by-pop.com/guides/building-cms-agnostic-api.html)
