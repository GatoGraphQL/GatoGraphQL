<p align="center"><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/master/assets/GatoGraphQL-logo.webp"/></p>

![Unit tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/unit_tests.yml/badge.svg)<!-- @todo Re-enable executing integration tests for PROD in CI --><!-- @see https://github.com/GatoGraphQL/GatoGraphQL/issues/2253 --><!-- ![Integration tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/integration_tests.yml/badge.svg) -->
![Downgrade PHP tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/downgrade_php_tests.yml/badge.svg)
![Scoping tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/scoping_tests.yml/badge.svg)
![Generate plugins](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/generate_plugins.yml/badge.svg)
<!-- ![PHPStan](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/phpstan.yml/badge.svg) -->

# Gato GraphQL

Gato GraphQL is a **tool for interacting with data in your WordPress site**. You can think of it as a Swiss Army knife for dealing with data, as it allows to retrieve, manipulate and store again any piece of data, in any desired way, using the [GraphQL language](https://graphql.org/).

With Gato GraphQL, you can:

- Query data to create headless sites
- Expose public and private APIs
- Map JS components to Gutenberg blocks
- Synchronize content across sites
- Automate tasks
- Complement WP-CLI to execute admin tasks
- Search/replace content for site migrations
- Send notifications when something happens (new post published, new comment added, etc)
- Interact with cloud services
- Convert the data from a 3rd-party API into the required format
- Translate content in the site
- Update thousands of posts with a single action
- Insert or remove Gutenberg blocks in bulk
- Validate that a new post contains a mandatory block
- And much more...

Check out the [Tutorial section in gatographql.com](https://gatographql.com/tutorial/intro) which demonstrates how to implement these use cases using the plugin.

## Screenshots

GraphiQL client to execute queries in the wp-admin:

![GraphiQL client to execute queries in the wp-admin](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-1.png)

Interactively browse the GraphQL schema, exploring all connections among entities:

![Interactively browse the GraphQL schema, exploring all connections among entities](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-2.png)

The GraphiQL client for the single endpoint is exposed to the Internet:

![The GraphiQL client for the single endpoint is exposed to the Internet](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-3.png)

Interactively browse the GraphQL schema exposed for the single endpoint:

![Interactively browse the GraphQL schema exposed for the single endpoint](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-4.png)

Persisted queries are pre-defined and stored in the server:

![Persisted queries are pre-defined and stored in the server](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-5.png)

Requesting a persisted query URL will retrieve its pre-defined GraphQL response:

![Requesting a persisted query URL will retrieve its pre-defined GraphQL response](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-6.png)

We can create multiple custom endpoints, each for a different target:

![We can create multiple custom endpoints, each for a different target](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-7.png)

Endpoints are configured via Schema Configurations:

![Endpoints are configured via Schema Configurations](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-8.png)

We can create many Schema Configurations, customizing them for different users or applications:

![We can create many Schema Configurations, customizing them for different users or applications](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-9.png)

Custom endpoints and Persisted queries can be public, private and password-protected:

![Custom endpoints and Persisted queries can be public, private and password-protected](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-10.png)

Manage custom endpoints and persisted queries by adding categories to them:

![Manage custom endpoints and persisted queries by adding categories to them](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-11.png)

We can configure exactly what custom post types, options and meta keys can be queried:

![We can configure exactly what custom post types, options and meta keys can be queried](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-12.png)

Configure every aspect from the plugin via the Settings page:

![Configure every aspect from the plugin via the Settings page](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-13.png)

Modules with different functionalities and schema extensions can be enabled and disabled:

![Modules with different functionalities and schema extensions can be enabled and disabled](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-14.png)

Augment the plugin functionality and GraphQL schema via extensions:

![Augment the plugin functionality and GraphQL schema via extensions](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-15.png)

The Tutorial section explains how to achieve many objectives, exploring all the elements from the GraphQL schema:

![The Tutorial section explains how to achieve many objectives, exploring all the elements from the GraphQL schema](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-16.png)

## Development

Please see [DEVELOPMENT](DEVELOPMENT.md).

## Monorepo documentation

`GatoGraphQL/GatoGraphQL` is a monorepo containing the several layers required for Gato GraphQL. Check [Monorepo_README.md](Monorepo_README.md) for documentation of the different projects.

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

## Release notes

<summary>Click to expand the Release notes</summary>

<details>

- **[16.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/16.0/en.md)** (current)
- [15.3](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/15.3/en.md)
- [15.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/15.2/en.md)
- [15.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/15.1/en.md)
- [15.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/14.0/en.md)
- [14.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/14.0/en.md)
- [13.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/13.2/en.md)
- [13.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/13.1/en.md)
- [13.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/13.0/en.md)
- [12.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/12.2/en.md)
- [12.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/12.1/en.md)
- [12.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/12.0/en.md)
- [11.3](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/11.3/en.md)
- [11.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/11.2/en.md)
- [11.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/11.1/en.md)
- [11.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/11.0/en.md)
- [10.5](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/10.5/en.md)
- [10.4](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/10.4/en.md)
- [10.3](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/10.3/en.md)
- [10.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/10.2/en.md)
- [10.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/10.1/en.md)
- [10.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/10.0/en.md)
- [9.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/9.0/en.md)
- [8.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/8.0/en.md)
- [7.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/7.0/en.md)
- [6.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/6.0/en.md)
- [5.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/5.0/en.md)
- [4.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/4.2/en.md)
- [4.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/4.1/en.md)
- [4.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/4.0/en.md)
- [3.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/3.0/en.md)
- [2.6](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.6/en.md)
- [2.5](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.5/en.md)
- [2.4](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.4/en.md)
- [2.3](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.3/en.md)
- [2.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.2/en.md)
- [2.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.1/en.md)
- [2.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.0/en.md)
- [1.5](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.5/en.md)
- [1.4](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.4/en.md)
- [1.3](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.3/en.md)
- [1.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.2/en.md)
- [1.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.1/en.md)
- [1.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.0/en.md)
- [0.10](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.10/en.md)
- [0.9](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.9/en.md)
- [0.8](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.8/en.md)
- [0.7](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.7/en.md)
- [0.6](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.6/en.md)

</details>

## Change log

Please see [CHANGELOG](layers/GatoGraphQLForWP/plugins/gatographql/CHANGELOG.md) for more information on what has changed recently.

## Testing

To execute [PHPUnit](https://phpunit.de/), run:

``` bash
composer test
```

## Static Analysis

To execute [PHPStan](https://github.com/phpstan/phpstan), run:

``` bash
composer analyse
```

<!-- ## Downgrading code

To visualize how [Rector](https://github.com/rectorphp/rector) will downgrade the code to PHP 7.4:

```bash
composer preview-code-downgrade
``` -->

## Report issues

To report a bug or request a new feature please do it on the [GatoGraphQL monorepo issue tracker](https://github.com/GatoGraphQL/GatoGraphQL/issues).

## Security issues

You can report security bugs through the Patchstack Vulnerability Disclosure Program. The Patchstack team help validate, triage and handle any security vulnerabilities. [Report a security vulnerability](https://patchstack.com/database/vdp/9e5fbfdf-3f7c-4ab9-bcee-87a59e98564b).

## Contributing

We welcome contributions for this package on the [GatoGraphQL monorepo](https://github.com/GatoGraphQL/GatoGraphQL) (where the source code for this package is hosted).

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]

## License

GPLv2 or later. Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-GPL%20(%3E%3D%202)-brightgreen.svg?style=flat-square
[ico-release]: https://img.shields.io/github/release/GatoGraphQL/GatoGraphQL.svg
[ico-downloads]: https://img.shields.io/github/downloads/GatoGraphQL/GatoGraphQL/total.svg

[link-author]: https://github.com/leoloso
[latest-release-url]: https://gatographql.com/releases/latest
