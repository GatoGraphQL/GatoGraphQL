# GraphQL API

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Extended/Upgraded implementation of GraphQL. This implementation is a package to be installed on top of the [PoP API](https://github.com/pop-api/api).

## Install

Via Composer

``` bash
composer require pop-api/api-graphql
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`API/packages/api-graphql`](https://github.com/leoloso/PoP/tree/master/layers/API/packages/api-graphql).

To enable pretty API endpoint `/api/graphql/`, follow the instructions [here](https://github.com/pop-api/api#enable-pretty-permalinks)

> Note: if you wish to install a fully-working API, please follow the instructions under [Bootstrap a PoP API for WordPress](https://github.com/leoloso/PoP-API-WP) (even though CMS-agnostic, only the WordPress adapters have been presently implemented).

<!--
Add the following code in the `.htaccess` file to enable API endpoint `/api/graphql/`:

```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /

# Rewrite from /some-url/api/graphql/ to /some-url/?scheme=api&datastructure=graphql
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^(.*)/api/graphql/?$ /$1/?scheme=api&datastructure=graphql [L,P,QSA]

# b. Homepage single endpoint (root)
# Rewrite from api/graphql/ to /?scheme=api&datastructure=graphql
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^api/graphql/?$ /?scheme=api&datastructure=graphql [L,P,QSA]
</IfModule>
```
-->

## Usage

Initialize the component:

``` php
\PoP\Root\App::stockAndInitializeComponentClasses([([
    \PoPAPI\GraphQLAPI\Component::class,
]);
```

<!--
## Usage

### Syntax

The query syntax used is described in package [Field Query](https://github.com/getpop/field-query).

## Implementation based on components, not on schemas

Whereas a standard implementation of [GraphQL](https://graphql.org) is based on the concept of [schemas and types](https://graphql.org/learn/schema/) implemented through the SDL (Schema Definition Language), GraphQL API for PoP is, instead, [implemented using components](https://www.smashingmagazine.com/2019/01/introducing-component-based-api/).

This architectural decision has several advantages over a schema-based implementation, explained in the sections below.

## Automatically-generated schema

The schema is **automatically-generated from the component model itself**, simply by coding classes following OOP principles. 

As a consequence, there is no need to manually define the hundreds (or even thousands) of properties on the schema, which leads to increased productivity.

Similar to GraphQL, the schema can be inspected through field `"fullSchema"`:

- [/api/graphql/?query=fullSchema](https://nextapi.getpop.org/api/graphql/?query=fullSchema)

## Syntax supporting URL-based queries

GraphQL API for PoP supports a [different syntax](https://github.com/getpop/field-query) than the one defined in the [GraphQL spec](https://graphql.github.io/graphql-spec/), which in addition to supporting all the expected features (arguments, variables, directives, etc), also grants GraphQL the following superpowers:

- Server-side caching
- Operators and Helper fields
- Composable fields

Please refer to the [Field Query](https://github.com/getpop/field-query) documentation to see examples on these superpowers.

## Fast speed, robust security

Resolving the query is fast: Wheareas the <a href="https://blog.acolyer.org/2018/05/21/semantics-and-complexity-of-graphql/">typical GraphQL implementation</a> has [complexity time](https://rob-bell.net/2009/06/a-beginners-guide-to-big-o-notation/) of `O(2^n)` in worst case, and `O(n^c)` to find out the query complexity, GraphQL API for PoP has complexity of `O(n^2)` in worst case, and `O(n)` in average case (where `n` is the number of nodes, both branches and leaves). 

As a consequence of this increased speed to resolve the query, DoS (Denial of Service) attacks are less effective, allowing to avoid having to spend time and energy in analyzing the query complexity.

## Natively decentralized/federated

The component-based architecture natively allows the data model to be split and worked upon by different, disconnected teams, without the need to set-up special tooling.

Additionally, field resolvers can be created on a field-by-field basis, based on the needs from the team/project/client, not on the API schema definition. This feature enables rapid iteration: Test new features, provide quick fixes, deprecate fields, and others.

For instance, let's say we want to add a field argument `length` on the `excerpt` field, but release it for testing first, before deciding to keep it or not. Then, we create a new field resolver that is enabled only when a property `branch` has the value `"experimental"`:

_**Standard behaviour:**_<br/>
[/?query=posts.id|title|excerpt](https://nextapi.getpop.org/api/graphql/?query=posts.id|title|excerpt)

_**New feature not yet available:**_<br/>
<a href="https://nextapi.getpop.org/api/graphql/?query=posts.id|title|excerpt(length:30)">/?query=posts.id|title|excerpt(length:30)</a>

_**New feature available under "experimental" branch:**_<br/>
<a href="https://nextapi.getpop.org/api/graphql/?query=posts.id|title|excerpt(branch:experimental,length:30)">/?query=posts.id|title|excerpt(length:30,branch:experimental)</a>

## Query data on resources, the REST way

In addition to querying data from the single endpoint `/api/graphql/` (which represents the root), it is possible to query data on specific resources, as defined by their URL.

Or, in other words, you can use a GraphQL query to retrieve data from a REST endpoint.

For instance, we can query data for a [single post](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/) or a [collection of posts](https://nextapi.getpop.org/posts/) by appending `/api/graphql/` to the URL, and adding the `query` URL parameter:

- [{single-post-url}/?query=id|title|author.id|name](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/graphql/?query=id|title|author.id|name)
- [{post-list-url}/?query=id|title|author.id|name](https://nextapi.getpop.org/posts/api/graphql/?query=id|title|author.id|name)

## Query examples

Please refer to the [Field Query](https://github.com/getpop/field-query) documentation to see plenty of query examples.

## More information

Please refer to package [API](https://github.com/pop-api/api), on which the GraphQL API is based, and which contains plenty of extra documentation.
-->

## Features

## Everything from the API layer, adapted to GraphQL

The [API](https://github.com/leoloso/PoP/tree/master/layers/API/) layer provides plenty of features, based on the [field-query](https://github.com/leoloso/PoP/tree/master/layers/Engine/packages/field-query) syntax.

This package adapts all those features to GraphQL.

### Automatic namespacing of types

Namespaces ([proposed to be added to the GraphQL spec](https://github.com/graphql/graphql-spec/issues/163)) help manage the complexity of the schema. This can avoid different types having the same name, which can happen when embedding components from a 3rd party.

This is how the normal schema looks like [in the GraphQL Voyager](https://newapi.getpop.org/graphql-interactive/):

![Interactive schema](https://raw.githubusercontent.com/pop-api/api-graphql/master/assets/images/normal-interactive-schema.png)

This is how it looks in [its namespaced version](https://newapi.getpop.org/graphql-interactive/?use_namespace=1):

![Namespaced interactive schema](https://raw.githubusercontent.com/pop-api/api-graphql/master/assets/images/namespaced-interactive-schema.png)

### Field/directive-based versioning

Fields and directives can be independently versioned, and the version to use can be specified in the query through the field/directive argument `versionConstraint`. 

To select the version for the field/directive, we use the same [semver version constraints employed by Composer](https://getcomposer.org/doc/articles/versions.md#writing-version-constraints).

In [this query](https://newapi.getpop.org/graphiql/?query=query%20%7B%0A%20%20olderVersion%3AuserServiceURLs(versionConstraint%3A%220.1.0%22)%0A%20%20newerVersion%3AuserServiceURLs(versionConstraint%3A%220.2.0%22)%0A%7D), field `userServiceURLs` has 2 versions, `0.1.0` and `0.2.0`:

![Querying a field using by version](https://raw.githubusercontent.com/pop-api/api-graphql/master/assets/images/versioning-field-directives-1.jpg)

Let's use constraints with `^` and `>` to select the version. In [this query](https://newapi.getpop.org/graphiql/?query=query%20%7B%0A%20%20%23This%20will%20produce%20version%200.1.0%0A%20%20firstVersion%3AuserServiceURLs(versionConstraint%3A%22%5E0.1%22)%0A%20%20%23%20This%20will%20produce%20version%200.2.0%0A%20%20secondVersion%3AuserServiceURLs(versionConstraint%3A%22%3E0.1%22)%0A%20%20%23%20This%20will%20produce%20version%200.2.0%0A%20%20thirdVersion%3AuserServiceURLs(versionConstraint%3A%22%5E0.2%22)%0A%7D), constraint `"^0.1"` is resolved to version `"0.1.0"`, but constraint `">0.1"` is resolved to version `"0.2.0"`:

![Querying a field using version constraints](https://raw.githubusercontent.com/pop-api/api-graphql/master/assets/images/versioning-field-directives-2.jpg)

[This query](https://newapi.getpop.org/graphiql/?query=query%20%7B%0A%20%20post(by%3A{id%3A1})%20%7B%0A%20%20%20%20titleCase%3Atitle%40makeTitle(versionConstraint%3A%22%5E0.1%22)%0A%20%20%20%20upperCase%3Atitle%40makeTitle(versionConstraint%3A%22%5E0.2%22)%0A%20%20%7D%0A%7D) demonstrates it for directives:

![Querying a directive using version constraints](https://raw.githubusercontent.com/pop-api/api-graphql/master/assets/images/versioning-field-directives-3.jpg)

Adding the `versionConstraint` parameter in the <a href='https://newapi.getpop.org/graphiql/?versionConstraint=^0.1&query=query {%0A%20 userServiceURLs%0A}'>GraphQL endpoint itself</a> will implicitly define that version constraint in all fields, and any field can still override this default value with its own `versionConstraint`, as in <a href='https://newapi.getpop.org/graphiql/?versionConstraint=^0.1&query=query {%0A%20 %23This will produce version 0.1.0%0A%20 implicitVersion%3A userServiceURLs%0A%20 %23This will produce version 0.2.0%0A%20 explicitVersion%3A userServiceURLs(versionConstraint%3A"^0.2")%0A}'>this query</a>:

![Overriding a default version constraint](https://raw.githubusercontent.com/pop-api/api-graphql/master/assets/images/versioning-field-directives-4.jpg)

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

[ico-version]: https://img.shields.io/packagist/v/pop-api/api-graphql.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/pop-api/api-graphql/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/pop-api/api-graphql.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/pop-api/api-graphql.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pop-api/api-graphql.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/pop-api/api-graphql
[link-travis]: https://travis-ci.org/pop-api/api-graphql
[link-scrutinizer]: https://scrutinizer-ci.com/g/pop-api/api-graphql/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/pop-api/api-graphql
[link-downloads]: https://packagist.org/packages/pop-api/api-graphql
[link-author]: https://github.com/leoloso
[link-contributors]: ../../../../../../contributors
