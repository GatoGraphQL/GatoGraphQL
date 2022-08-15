# Resource Loader

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Load resources for the website

## Install

Via Composer

``` bash
composer require getpop/resourceloader
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`SiteBuilder/packages/resourceloader`](https://github.com/leoloso/PoP/tree/master/layers/SiteBuilder/packages/resourceloader).

## Usage

Initialize the component:

``` php
\PoP\Root\App::stockAndInitializeModuleClasses([([
    \PoP\ResourceLoader\Module::class,
]);
```

## Architecture foundations

Layouts are rendered through custom-built reactivity, based on observing a unique JavaScript object (which contains database and configuration data).

The view is implemented through [Handlebars](https://handlebarsjs.com/) templates, which can be loaded both in the client (through the Handlebars runtime) and in the server (through PHP library [LightnCandy](https://github.com/zordius/lightncandy)). This approach is isomorphic: the same code works on both environments.

Implementation coming soon.

## Main Concepts

### Rendering through JavaScript templates

Will be added soon...

### Isomorphic Server-Side Rendering

Will be added soon...

### Reactivity

Will be added soon...

## Architecture Design and Implementation

#### Dataloading

#### Dataloading Modules

### Handlebars

Will be added soon...

### LightnCandy

Will be added soon...

### Code Splitting

Will be added soon...

### Progressive-Web App

Will be added soon...

### Single-Page Application

Will be added soon...

### Content CDN

Will be added soon...

### A/B Testing

Will be added soon...

### Form Input Modules

Will be added soon...

### Client-side Rendering

Will be added soon...

### JavaScript templates through Handlebars

Will be added soon...

### Executing JavaScript functions

Will be added soon...

### Resources

Will be added soon...

### Asset-bundling

Will be added soon...

### Progressive Booting

Will be added soon...

### Links in body

Will be added soon...

### State Management

Will be added soon...

### Data Cache, Configuration Cache and Replication

Will be added soon...

### Reactivity

Will be added soon...

## Server-Side Rendering

Will be added soon...

### Isomorphism

Will be added soon...

### JavaScript templates into PHP through LightnCandy

Will be added soon...

### Rendering a Webpage as a Transactional Email

Will be added soon...

<!-- ## Examples

### Application extending from the API

> Note: The examples below are currently not deployed... Will do so soon...

The native API can be extended by adding the other layers (configuration, view) to create the application:

- [The homepage](https://nextapi.getpop.org/?output=json&mangled=none&dataoutputmode=combined), [a single post](https://nextapi.getpop.org/posts/a-lovely-tango/?output=json&mangled=none&dataoutputmode=combined), [an author](https://nextapi.getpop.org/u/leo/?output=json&mangled=none&dataoutputmode=combined), [a list of posts](https://nextapi.getpop.org/posts/?output=json&mangled=none&dataoutputmode=combined) and [a list of users](https://nextapi.getpop.org/users/?output=json&mangled=none&dataoutputmode=combined)
- [An event, filtering from a specific module](https://nextapi.getpop.org/events/coldplay-in-london/?output=json&mangled=none&componentFilter=componentPaths&componentPaths[]=pagesectiongroup.pagesection-body.block-singlepost.block-single-content&dataoutputmode=combined)
- A tag, [filtering modules which require user state](https://nextapi.getpop.org/tags/internet/?output=json&mangled=none&componentFilter=userstate&dataoutputmode=combined) and [filtering to bring only a page from a Single-Page Application](https://nextapi.getpop.org/tags/internet/?output=json&mangled=none&componentFilter=page&dataoutputmode=combined)
- [An array of locations, to feed into a typeahead](https://nextapi.getpop.org/locations/?output=json&mangled=none&componentFilter=mainContentComponent&dataoutputmode=combined&datastructure=results)
- Alternative models for the "Who we are" page: [Normal](https://nextapi.getpop.org/who-we-are/?output=json&mangled=none&dataoutputmode=combined), [Printable](https://nextapi.getpop.org/who-we-are/?output=json&mangled=none&thememode=print&dataoutputmode=combined), [Embeddable](https://nextapi.getpop.org/who-we-are/?output=json&mangled=none&thememode=embed&dataoutputmode=combined)
- Changing the module names: [original](https://nextapi.getpop.org/?output=json&mangled=none&dataoutputmode=combined) vs [mangled](https://nextapi.getpop.org/?output=json&dataoutputmode=combined)
- Filtering information: [only module settings](https://nextapi.getpop.org/?output=json&dataoutputitems[]=componentsettings&dataoutputmode=combined&mangled=none), [module data plus database data](https://nextapi.getpop.org/?output=json&dataoutputitems[]=databases&dataoutputitems[]=componentdata&dataoutputmode=combined&mangled=none)

---
---
--- -->

## PHP versions

Requirements:

- PHP 8.1+ for development
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

[ico-version]: https://img.shields.io/packagist/v/getpop/resourceloader.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/resourceloader/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/resourceloader.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/resourceloader.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/resourceloader.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/resourceloader
[link-travis]: https://travis-ci.org/getpop/resourceloader
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/resourceloader/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/resourceloader
[link-downloads]: https://packagist.org/packages/getpop/resourceloader
[link-author]: https://github.com/leoloso
[link-contributors]: ../../../../../../contributors
