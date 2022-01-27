# REST API

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

It enables to add REST endpoints to retrieve data for any URL-based resource. It is based on the [PoP API](https://github.com/pop-api/api) package.

## Install

Via Composer

``` bash
composer require pop-api/api-rest
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`API/packages/api-rest`](https://github.com/leoloso/PoP/tree/master/layers/API/packages/api-rest).

<!-- To enable pretty API endpoint `/api/rest/`, follow the instructions [here](https://github.com/pop-api/api#enable-pretty-permalinks) -->

### Enable pretty permalinks

#### Apache

Make sure the Apache server has modules [`proxy`](https://httpd.apache.org/docs/current/mod/mod_proxy.html) and [`proxy_http`](https://httpd.apache.org/docs/current/mod/mod_proxy_http.html) installed and enabled. This enables configuration `"P"` in `"[L,P,QSA]"` from the rewrite rule below.

Add the following code in the `.htaccess` to support API endpoint `/api/rest/` at the end of the resource page URL:

```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /

# Rewrite from /some-url/api/rest/ to /some-url/?scheme=api&datastructure=rest
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^(.*)/api/rest/?$ /$1/?scheme=api&datastructure=rest [L,P,QSA]
</IfModule>
```

#### Nginx

Add the following code in the Nginx configuration's `server` entry, to enable API endpoint `/api/rest/`. Please notice that the resolver below is the one for Docker; replace this value for your environment.

```nginx
location ~ ^(.*)/api/rest/?$ {
    # Resolver for Docker. Change to your own
    resolver 127.0.0.11 [::1];
    # If adding $args and it's empty, it does a redirect.
    # Then, add $args only if not empty
    set $redirect_uri "$scheme://$server_name$1/?scheme=api&datastructure=rest";
    if ($args) {
        set $redirect_uri "$scheme://$server_name$1/?$args&scheme=api&datastructure=rest";
    }
    proxy_pass $redirect_uri;
}
```

<!-- ```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /

# Rewrite from /some-url/api/rest/ to /some-url/?scheme=api&datastructure=rest
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^(.*)/api/rest/?$ /$1/?scheme=api&datastructure=rest [L,P,QSA]

# b. Homepage single endpoint (root)
# Rewrite from api/rest/ to /?scheme=api&datastructure=rest
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^api/rest/?$ /?scheme=api&datastructure=rest [L,P,QSA]
</IfModule>
``` -->

## Usage

Initialize the component:

``` php
\PoP\Root\App::stockAndInitializeComponentClasses([([
    \PoPAPI\RESTAPI\Component::class,
]);
```

Append `/api/rest/` to the URL to fetch the pre-defined fields, and optionally add a `query` URL parameter to retrieve specific data fields using [this syntax](https://github.com/getpop/field-query).

Examples:

_**Single post, default fields**_:<br/>
[{single-post-url}/api/rest/](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/rest/)

_**Single post, custom fields**_:<br/>
[{single-post-url}/api/rest/?query=id|title|author.id|name](https://nextapi.getpop.org/2013/01/11/markup-html-tags-and-formatting/api/rest/?query=id|title|author.id|name)

_**Collection of posts, default fields**_:<br/>
[{post-list-url}/api/rest/](https://nextapi.getpop.org/posts/api/rest/)

_**Collection of posts, custom fields**_:<br/>
[{post-list-url}/api/rest/?query=id|title|author.id|name](https://nextapi.getpop.org/posts/api/rest/?query=id|title|author.id|name)

<!-- ## More information

Please refer to package [API](https://github.com/pop-api/api), on which the REST API is based, and which contains plenty of extra documentation. -->

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

[ico-version]: https://img.shields.io/packagist/v/pop-api/api-rest.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/pop-api/api-rest/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/pop-api/api-rest.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/pop-api/api-rest.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pop-api/api-rest.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/pop-api/api-rest
[link-travis]: https://travis-ci.org/pop-api/api-rest
[link-scrutinizer]: https://scrutinizer-ci.com/g/pop-api/api-rest/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/pop-api/api-rest
[link-downloads]: https://packagist.org/packages/pop-api/api-rest
[link-author]: https://github.com/leoloso
[link-contributors]: ../../../../../../contributors
