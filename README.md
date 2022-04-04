# PoP

PoP is a monorepo containing several projects.

## The GraphQL API for WordPress plugin

<p align="center"><img src="https://graphql-api.com/assets/graphql-api-logo-with-name.png" width="450" /></p>

**GraphQL API for WordPress** is a forward-looking and powerful GraphQL server for WordPress.

- Website: [graphql-api.com](https://graphql-api.com)
- [Download](https://github.com/leoloso/PoP/releases/latest/download/graphql-api.zip)
- [Plugin source code](layers/GraphQLAPIForWP/plugins/graphql-api-for-wp)
- [Development](docs/development-environment.md)

Plugins can extend the GraphQL schema, to fetch their own data.

- [Extension demo source code](layers/GraphQLAPIForWP/plugins/extension-demo)

## GraphQL By PoP

<p align="center"><img src="https://graphql-by-pop.com/assets/superheroes.png" width="450" /></p>

**GraphQL by PoP** is a CMS-agnostic GraphQL server in PHP.

- Website: [graphql-by-pop.com](https://graphql-by-pop.com)
- [Source code](layers/GraphQLByPoP)

## PoP - set of PHP components

<p align="center"><img src="https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png" width="450" /></p>

**PoP** is a set of libraries which provide a server-side component model in PHP, and the foundation to implement applications with it.

- Website: [getpop.org](https://getpop.org)
- Source code:
  - [Backbone](layers/Backbone): Libraries providing the architectural scaffolding.
  - [Engine](layers/Engine): The engine of the application.
  - [Schema](layers/Schema): Self-sufficient schema elements, such as directives.
  - [CMSSchema](layers/CMSSchema): Schema elements abstracted away from the CMS through interfaces, and to be satisfied for some particular CMS.
  - [WPSchema](layers/WPSchema): WordPress-specific schema elements.
  - [API](layers/API): Packages to access the schema data through an API, including REST and GraphQL.

## Site Builder (WIP)

**Site Builder** is a set of PHP components to build a website using PoP's component-model architecture.

- [Source code](layers/SiteBuilder)

Similar to WordPress, it accepts themes.

- [Wassup](layers/Wassup): theme powering sites [MESYM](https://www.mesym.com) and [TPP Debate](https://my.tppdebate.org)

---

<!-- ## Requirements

- PHP 8.1+ for development
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
``` -->

## Table of Contents

1. [Setting-up the development environment](docs/development-environment.md)
2. [Repo visualization](docs/repo-visualization.md)
3. [Layer dependency graph](docs/layer-dependency-graph.md)
4. [Supported PHP features](docs/supported-php-features.md)
5. [How is the GraphQL server CMS-agnostic](docs/cms-agnosticism.md)
6. [Why are there so many packages in the repo](docs/splitting-packages.md)
7. [Why a monorepo](docs/why-monorepo.md)
8. [How transpiling works](docs/how-transpiling-works.md)
9. [How scoping works](docs/how-scoping-works.md)
10. [Installing the GraphQL API for WordPress plugin](docs/installing-graphql-api-for-wordpress.md)

<!-- ## Resources

- [GraphQL API for WordPress demo](https://youtu.be/LnyNyT2RwwI)
- [Comparing the GraphQL API for WordPress vs WPGraphQL](https://graphql-api.com/blog/graphql-api-vs-wpgraphql-the-fight/)
- [Making GraphQL Work In WordPress](https://www.smashingmagazine.com/2021/04/making-graphql-work-in-wordpress/) -->

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
