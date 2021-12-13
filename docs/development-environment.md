# Setting-up the development environment

These are the instructions on how to set-up a local development environment.
## Requirements

- PHP 8.0+
- Composer
- Lando 3.0.26+

[Lando](https://lando.dev/) is a Docker-based tool. It is used to spin the webserver for development, providing:

- WordPress installed
- the GraphQL API plugin installed and activated
- symlinking to the source code

## Install

Clone the monorepo:

```bash
git clone https://github.com/leoloso/PoP.git
```

Install the dependencies, via Composer:

```bash
$ cd PoP
$ composer install
```

Build the Lando webserver:

```bash
composer build-server
```

## Site URL

The site will be available under `http://graphql-api.lndo.site`.

To access the [wp-admin](http://graphql-api.lndo.site/wp-admin/):

- User: `admin`
- Password: `admin`

## Starting the Lando webserver

To start the server, execute:

```bash
composer start-server
```

## Caching

By default, the webserver will have global caching enabled.

To test a change during development, we must manually purge the cache (recommended option to keep the GraphQL server running fast), or directly disable caching.

### Purging the cache

Cached files are stored under the plugin's `cache` subfolder.

To purge them, simply delete this folder, or execute the following Composer script:

```bash
composer purge-cache
```

### Disable caching

Caching is disabled by setting constant `GRAPHQL_API_DISABLE_CACHING` in `wp-config.php` to `true`:

```php
define( 'GRAPHQL_API_DISABLE_CACHING', 'true' );
```

To define this constant, we can execute the following Composer scripts:

```bash
$ composer disable-caching
$ composer enable-caching
```

## Debugging

XDebug is enabled by default, but must be triggered for the specific request. To do so, append param `XDEBUG_TRIGGER` to the URL:

- In the `wp-admin`, in the GraphiQL or Interactive schema client URLs
- To any custom endpoint
- To any persisted query

## Additional resources

- [Composer](https://getcomposer.org)
- [Lando](https://lando.dev/)
