# Cache Control

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Add HTTP caching to the response

## Install

Via Composer

``` bash
composer require getpop/cache-control
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`Engine/packages/cache-control`](https://github.com/leoloso/PoP/tree/master/layers/Engine/packages/cache-control).

## Usage

Initialize the component:

``` php
\PoP\Root\App::stockAndInitializeComponentClasses([([
    \PoP\CacheControl\Component::class,
]);
```

## How it works

It adds a mandatory directive `<cacheControl>` to all fields, which has a max-age value set for each field.

The response will send a `Cache-Control` header with the lowest max-age from all the requested fields, or `no-store` if any field has max-age: 0.

## Examples

> **Note:**<br/>Click on the following links below, and inspect the response headers using Chrome or Firefox's developer tools' Network tab.

Operators have a max-age of 1 year:

```php
/?query=
  echo(Hello world!)
```

[<a href="https://newapi.getpop.org/api/graphql/?query=echo(Hello+world!)">View query results</a>]

By default, fields have a max-age of 1 hour:

```php
/?query=
  echo(Hello world!)|
  posts.
    title
```

[<a href="https://newapi.getpop.org/api/graphql/?query=echo(Hello+world!)|posts.title">View query results</a>]

Composed fields are also taken into account when computing the lowest max-age:

```php
/?query=
  echo(posts())
```

[<a href="https://newapi.getpop.org/api/graphql/?query=echo(posts())">View query results</a>]

`"time"` field is not to be cached (max-age: 0):

```php
/?query=
  time
```

[<a href="https://newapi.getpop.org/api/graphql/?query=time">View query results</a>]

Ways to not cache a response:

a. Add field `"time"` to the query:

```php
/?query=
  time|
  echo(Hello world!)|
  posts.
    title
```

[<a href="https://newapi.getpop.org/api/graphql/?query=time|echo(Hello+world!)|posts.title">View query results</a>]

b. Override the default `maxAge` configuration for a field, by adding argument `maxAge: 0` to directive `<cacheControl>`:

```php
/?query=
  echo(Hello world!)|
  posts.
    title<cacheControl(maxAge:0)>
```

[<a href="https://newapi.getpop.org/api/graphql/?query=echo(Hello+world!)|posts.title<cacheControl(maxAge:0)>">View query results</a>]

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

To execute [PHPUnit](https://phpunit.de/), run:

``` bash
composer test
```

## Static Analysis

To execute [PHPStan](https://github.com/phpstan/phpstan), run:

``` bash
composer analyse
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

[ico-version]: https://img.shields.io/packagist/v/getpop/cache-control.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/cache-control/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/cache-control.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/cache-control.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/cache-control.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/cache-control
[link-travis]: https://travis-ci.org/getpop/cache-control
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/cache-control/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/cache-control
[link-downloads]: https://packagist.org/packages/getpop/cache-control
[link-author]: https://github.com/leoloso
[link-contributors]: ../../../../../../contributors
