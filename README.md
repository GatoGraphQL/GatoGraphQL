![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# PoP

This is a monorepo containing all layers from the PoP project:

[Engine](layers/Engine):<br/>A server-side component model in PHP.

[Schema](layers/Schema):<br/>The CMS-agnostic definition for all the data entities (posts, users, comments, etc), and the implementation of the contracts for specific CMSs.

[API](layers/API):<br/>Packages to access the schema data through an API, including REST and GraphQL.

[GraphQL by PoP](layers/GraphQLByPoP):<br/>Implementation of a CMS-agnostic GraphQL server in PHP, living in [graphql-by-pop.com](https://graphql-by-pop.com).

[GraphQL API for WordPress](layers/GraphQLAPIForWP):<br/>Implementation of the CMS-agnostic GraphQL server for WordPress, involving [the main plugin](layers/GraphQLAPIForWP/plugins/graphql-api-for-wp) and extensions.

[Site Builder](layers/SiteBuilder):<br/>Packages to build a website using the component-model architecture.

[Wassup](layers/Wassup):<br/>Implementation of a PoP website (check [MESYM](https://www.mesym.com) and [TPP Debate](https://my.tppdebate.org)).

## Dependency graph

This is how the layers in PoP relate to each other:

![Dependency graph](assets/img/dependency-graph.svg)

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

- WordPress plugin: GPLv2
- Everything else: MIT

[link-author]: https://github.com/leoloso
[link-contributors]: ../../contributors
