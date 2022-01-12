# PHP Hooks

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Implementation of `PoP\Hooks` using package `pop-backbone/php-hooks`, a fork of [`bainternet/php-hooks`](https://packagist.org/packages/bainternet/php-hooks), which is itself a fork from the WordPress hooks system, making it independent from WordPress.

This package is to be used for PHPUnit, to avoid having to load WordPress.

## Install

Via Composer

``` bash
composer require getpop/hooks-php
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`Engine/packages/hooks-php`](https://github.com/leoloso/PoP/tree/master/layers/Engine/packages/hooks-php).

## Usage

Initialize the component:

``` php
\PoP\Root\App::stockAndInitializeComponentClasses([([
    \PoP\HooksPHP\Component::class,
]);
```

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

[ico-version]: https://img.shields.io/packagist/v/getpop/hooks-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/hooks-php/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/hooks-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/hooks-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/hooks-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/hooks-php
[link-travis]: https://travis-ci.org/getpop/hooks-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/hooks-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/hooks-php
[link-downloads]: https://packagist.org/packages/getpop/hooks-php
[link-author]: https://github.com/leoloso
[link-contributors]: ../../../../../../contributors
