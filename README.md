![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# PoP

This is a monorepo containing all layers from the PoP project:

[Engine](layers/Engine):<br/>A server-side component model in PHP.

[Schema](layers/Schema):<br/>The definitions for data entities (posts, users, comments, etc).

[API](layers/API):<br/>Packages to access the schema data through an API, including REST and GraphQL.

[GraphQL by PoP](layers/GraphQLByPoP):<br/>Implementation of a CMS-agnostic GraphQL server in PHP ([graphql-by-pop.com](https://graphql-by-pop.com)).

[GraphQL API for WordPress ecosystem](layers/GraphQLAPIForWP):<br/>Implementation of the CMS-agnostic GraphQL server for WordPress. This layer comprises:

- The main plugin, [GraphQL API for WordPress](layers/GraphQLAPIForWP/plugins/graphql-api-for-wp) ([graphql-api.com](https://graphql-api.com)).
- An [extension demo](layers/GraphQLAPIForWP/plugins/extension-demo) plugin, demonstrating how to extend the GraphQL schema.

[Site Builder](layers/SiteBuilder):<br/>Packages to build a website using the component-model architecture (WIP).

[Wassup](layers/Wassup):<br/>Implementation of a PoP website for WordPress (powering [MESYM](https://www.mesym.com) and [TPP Debate](https://my.tppdebate.org) - WIP).

## Requirements

- PHP 8.0+ for development
- PHP 7.1+ for production

## Install 

Clone the monorepo:

```bash
git clone https://github.com/leoloso/PoP.git
```

And then install the dependencies, via Composer

```bash
$ cd PoP
$ composer install
```

## Table of Contents

1. [Setting-up the development environment](docs/development-environment.md)
2. [Layer dependency graph](docs/layer-dependency-graph.md)
3. [Supported PHP features](docs/supported-php-features.md)
4. [How is the GraphQL server CMS-agnostic](docs/cms-agnosticism.md)
5. [Why are there so many packages in the repo](docs/splitting-packages.md)
6. [Why a monorepo](docs/why-monorepo.md)
7. [How transpiling works](docs/how-transpiling-works.md)
8. [How scoping works](docs/how-scoping-works.md)
9. [Installing the GraphQL API for WordPress plugin](docs/installing-graphql-api-for-wordpress.md)

## Resources

- [GraphQL API for WordPress demo](https://youtu.be/LnyNyT2RwwI)
- [Comparing the GraphQL API for WordPress vs WPGraphQL](https://graphql-api.com/blog/graphql-api-vs-wpgraphql-the-fight/)
- [Making GraphQL Work In WordPress](https://www.smashingmagazine.com/2021/04/making-graphql-work-in-wordpress/)

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

## Testing

To execute [PHPUnit](https://phpunit.de/), run:

``` bash
composer test
```

## Static analysis

To execute [PHPStan](https://github.com/phpstan/phpstan), run:

``` bash
composer analyse
```

## Previewing code downgrade

Via [Rector](https://github.com/rectorphp/rector) (dry-run mode):

```bash
composer preview-code-downgrade
```

## Report issues

Use the [issue tracker](https://github.com/leoloso/PoP/issues) to report a bug or request a new feature for all packages in the monorepo.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

GNU General Public License v2 (or later). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/leoloso
[link-contributors]: ../../contributors
