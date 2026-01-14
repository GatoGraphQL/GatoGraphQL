# GraphQL Voyager

<!--
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
-->

[GraphQL Voyager](https://github.com/APIs-guru/graphql-voyager) client, to be integrated with GraphQL by PoP

## Install

Via Composer

``` bash
$ composer require graphql-by-pop/graphql-voyager
```

In order to copy the GraphQL Voyager client under a specific path, install package [Composer Installers Extender](https://github.com/oomphinc/composer-installers-extender):

``` bash
$ composer require oomphinc/composer-installers-extender
```

Then, add package type `"graphql-voyager"` in section `"extra.installer-types"`, and the custom path to the package in section `"extra.installer-paths"`. For instance, this configuration will add the GraphQL Voygar client under URL `/graphql-interactive/`:

``` javascript
{
    ...
    "extra": {
        "installer-types": ["graphql-voyager"],
        "installer-paths": {
            "graphql-interactive/": [
                "graphql-by-pop/graphql-voyager"
            ]
        }
    }
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/leoloso/pop-graphql-voyager.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/leoloso/pop-graphql-voyager/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/leoloso/pop-graphql-voyager.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/leoloso/pop-graphql-voyager.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/leoloso/pop-graphql-voyager.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/leoloso/pop-graphql-voyager
[link-travis]: https://travis-ci.org/leoloso/pop-graphql-voyager
[link-scrutinizer]: https://scrutinizer-ci.com/g/leoloso/pop-graphql-voyager/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/leoloso/pop-graphql-voyager
[link-downloads]: https://packagist.org/packages/leoloso/pop-graphql-voyager
[link-author]: https://github.com/leoloso
[link-contributors]: ../../contributors
