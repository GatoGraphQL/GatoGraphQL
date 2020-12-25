# PoP Engine Bootloader for WordPress

[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)

<!--
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Implementation of the Bootloader for PoP Engine for WordPress

## Install

Via Composer

``` bash
composer require getpop/engine-wp-bootloader
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`Engine/packages/engine-wp-bootloader`](https://github.com/leoloso/PoP/tree/master/layers/Engine/packages/engine-wp-bootloader).

## Usage

Install file `pop-engine-wp-bootloader.php` as a WordPress must-use plugin, by copying it under folder `/mu-plugins`.

## PHP versions

Requirements:

- PHP 7.4+ for development
- PHP 7.1+ for production

### Supported PHP features

Same as the [Supported PHP features for `getpop/root`](https://github.com/getpop/root/#supported-php-features)

### Downgrading code to PHP 7.1

Via [Rector](https://github.com/rectorphp/rector) (dry-run mode):

```bash
composer preview-code-downgrade
```

## Standards

[PSR-1](https://www.php-fig.org/psr/psr-1), [PSR-4](https://www.php-fig.org/psr/psr-4) and [PSR-12](https://www.php-fig.org/psr/psr-12).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Static Analysis

Execute [phpstan](https://github.com/phpstan/phpstan) with level 8:

``` bash
composer analyse
```

To run checks for level 0 (or any level from 0 to 8):

``` bash
./vendor/bin/phpstan analyse -l 0 src tests
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/getpop/engine-wp-bootloader.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/engine-wp-bootloader/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/engine-wp-bootloader.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/engine-wp-bootloader.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/engine-wp-bootloader.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/engine-wp-bootloader
[link-travis]: https://travis-ci.org/getpop/engine-wp-bootloader
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/engine-wp-bootloader/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/engine-wp-bootloader
[link-downloads]: https://packagist.org/packages/getpop/engine-wp-bootloader
[link-contributors]: ../../../../../../contributors
[link-author]: https://github.com/leoloso
