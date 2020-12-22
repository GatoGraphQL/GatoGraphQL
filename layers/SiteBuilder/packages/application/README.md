# Application

[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)

<!--
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Create a component-based website

## Install

Via Composer

``` bash
composer require getpop/application
```

## Usage

Initialize the component:

``` php
\PoP\Root\ComponentLoader::initializeComponents([
    \PoP\Application\Component::class,
]);
```

## Main Concepts

### Multidomain

PoP has been built to support decentralization: modules can fetch their data from a different domain/subdomain from which the application is hosted. For instance, an application can have its components retrieved from subdomains:

![Modules can have their data fetched from different domains and subdomains](https://uploads.getpop.org/wp-content/uploads/2017/02/application-wireframe.png)

A single component is also able to have many sources of data, each of them coming from a different domain/subdomain. For instance, the [events calendar in SukiPoP.com](https://sukipop.com/en/calendar/) displays events from several external sites in a unique calendar, painting events with a different color according to the source domain:

![Multidomain events calendar](https://uploads.getpop.org/wp-content/uploads/2018/12/multidomain-events-calendar.png)

## Architecture Design and Implementation

### Dataloading

#### Lazy-Loading

We can instruct a dataloading module to be lazy-loaded (i.e. instead of fetching its database data immediately, it is fetched on a subsequent request from the client) simply by setting its prop `"lazy-load"` to `true`:

```php
function initModelProps($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:

      // Set the content lazy
      $this->setProp($module, $props, 'lazy-load', true);
      break;
  }

  parent::initModelProps($module, $props);
}
```

Being a prop, this value can be set either by the dataloading module itself, or by any of its ancestor modules:

```php
function initModelProps($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLESWRAPPER:

      // Set the content lazy
      $this->setProp([MODULE_AUTHORARTICLES], $props, 'lazy-load', true);
      break;
  }

  parent::initModelProps($module, $props);
}
```

Among others, the following are several uses cases for lazy-loading the data for a module:

- Modules which are displayed on several pages (eg: a "latest posts" widget on a sidebar) can have its data cached in the client (eg: through Service Workers, localStorage, etc) and, by lazy-loading, this data is not fetched again on the server on each request
- Fetching data from a different domain
- Improve apparent loading speed by lazy-loading data for below-the-fold modules (eg: a post's comments)
- Fetching data with user state on a page without user state ([as outlined here](https://www.smashingmagazine.com/2018/12/caching-smartly-gutenberg/))

### Multidomain

By default, a module will fetch its data from the domain where the application is hosted. To change this to a different domain(s) or subdomain(s) is done by setting prop `"dataload-multidomain-sources"` on the module:

```php
function initModelProps($module, &$props) {
    
  switch ($module[1]) {
    case self::MODULE_SOMENAME:

      $this->setProp(
        $module, 
        $props, 
        'dataload-multidomain-sources', 
        'https://anotherdomain.com'
      );
      break;
  }

  parent::initModelProps($module, $props);
}
```

We can also pass an array of domains, in which case the module will fetch its data from all of them:

```php
function initModelProps($module, &$props) {
    
  switch ($module[1]) {
    case self::MODULE_SOMENAME:

      $this->setProp(
        $module, 
        $props, 
        'dataload-multidomain-sources', 
        array(
          'https://anotherdomain1.com',
          'https://subdomain.anotherdomain2.com',
          'https://www.anotherdomain3.com',
        );
      break;
  }

  parent::initModelProps($module, $props);
}
```

When fetching data from several sources, each source will keep its own state in the [QueryInputOutputHandler](#queryhandler). Then, it is able to query different amounts of data from different domains (eg: 3 results from domain1.com and 6 results from domain2.com), and stop querying from a particular domain when it has no more results.

Because the external application may have different components installed, it is not guaranteed that fetching data from the external application by simply adding `?output=json` will bring the data required by the origin application. To solve this issue, when querying data from an external application, PoP will use the [custom-querying API](#Custom-Querying-API) to fetch exactly the required data fields (this works for fetching database data, not configuration). If we have control on the external application and we can guarantee that both sites have the same components installed, then we can define constant `EXTERNAL_SITES_RUN_SAME_SOFTWARE` as true, which will allow to fetch database and configuration data through the regular `?output=json` request.

---
---
---

## PHP versions

Requirements:

- PHP 7.4+ for development
- PHP 7.1+ for production

### Supported PHP features

Same as the [Supported PHP features for `getpop/root`](https://github.com/getpop/root/#supported-php-features)

### Downgrading code to PHP 7.1

Via [Rector](https://github.com/rectorphp/rector) (dry-run mode):

```bash
composer downgrade-code
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
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/getpop/application.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/application/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/application.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/application.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/application.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/application
[link-travis]: https://travis-ci.org/getpop/application
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/application/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/application
[link-downloads]: https://packagist.org/packages/getpop/application
[link-author]: https://github.com/leoloso
[link-contributors]: ../../contributors
