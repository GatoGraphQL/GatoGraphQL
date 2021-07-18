# GraphiQL

<!--
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]
-->

[GraphiQL](https://github.com/graphql/graphiql/tree/main/packages/graphiql) client, to be integrated with GraphQL by PoP

## Install

Via Composer

``` bash
$ composer require graphql-by-pop/graphiql
```

In order to copy the GraphiQL under a specific path, install package [Composer Installers Extender](https://github.com/oomphinc/composer-installers-extender):

``` bash
$ composer require oomphinc/composer-installers-extender
```

Then, add package type `"graphiql-client"` in section `"extra.installer-types"`, and the custom path to the package in section `"extra.installer-paths"`. For instance, this configuration will add the GraphiQL client under URL `/graphiql/`:

``` javascript
{
    ...
    "extra": {
        "installer-types": ["graphiql-client"],
        "installer-paths": {
            "graphiql/": [
                "graphql-by-pop/graphiql"
            ]
        }
    }
}
```

<!--
## Usage

``` php
```
-->

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

[ico-version]: https://img.shields.io/packagist/v/leoloso/pop-graphiql.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/leoloso/pop-graphiql/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/leoloso/pop-graphiql.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/leoloso/pop-graphiql.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/leoloso/pop-graphiql.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/leoloso/pop-graphiql
[link-travis]: https://travis-ci.org/leoloso/pop-graphiql
[link-scrutinizer]: https://scrutinizer-ci.com/g/leoloso/pop-graphiql/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/leoloso/pop-graphiql
[link-downloads]: https://packagist.org/packages/leoloso/pop-graphiql
[link-author]: https://github.com/leoloso
[link-contributors]: ../../contributors
