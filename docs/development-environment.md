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

Build the Lando webserver. The first time, execute:

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

### Cached items

The Cached elements include:

1. The service containers (from Symfony's Dependency Injection)
2. The generated configuration, which maps the component model to queries (when module [Configuration Cache](../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/docs/en/modules/configuration-cache.md) is enabled)
3. The calculated GraphQL schema (when module [Schema Cache](../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/docs/en/modules/schema-cache.md) is enabled)

## Debugging

XDebug is disabled by default. To enable it, create Lando config file `.lando.local.yml` with this content:

```yaml
config:
  xdebug: true
```

And then rebuild the server:

```bash
composer rebuild-server
```

## Additional resources

- [Composer](https://getcomposer.org)
- [Lando](https://lando.dev/)
