# WordPress Data Parser

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Fork of the [WordPress Importer plugin](https://wordpress.org/plugins/wordpress-importer/), to enable providing WordPress data to seed PHPUnit tests.

## Install

Via Composer

``` bash
composer require pop-backbone/wp-data-parser
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`Backbone/packages/wp-data-parser`](https://github.com/leoloso/PoP/tree/master/layers/Backbone/packages/wp-data-parser).

## Description

The original [WordPress Importer plugin](https://wordpress.org/plugins/wordpress-importer/) imports the following content from a WordPress export file:

- Posts, pages and other custom post types
- Comments and comment meta
- Custom fields and post meta
- Categories, tags and terms from custom taxonomies and term meta
- Authors

This forked package does not require WordPress to be installed. It re-uses the same logic from the plugin to parse the export file from WordPress, but then it returns the parsed data using generic objects (i.e. it returns `array`, not `WP_Post`).

## Use

```php
$wpDataXMLExportFile = __DIR__ . '/sample-data.wordpress.xml';
$wpDataParser = new WPDataParser();
$parsedData = $wpDataParser->parse($wpDataXMLExportFile);
```
