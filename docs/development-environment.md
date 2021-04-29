# Setting-up the development environment

The project uses [Lando](https://lando.dev/) to spin the webserver used for development, with WordPress installed and the GraphQL API plugin activated, and symlinking all packages to the source code in the monorepo.

Please make sure you have Lando installed, with version `3.0.26` or upwards (install the latest version from [here](https://github.com/lando/lando/releases/)).

The first time, to install the server, execute:

```bash
composer build-server
```

From then on, to start the server, execute:

```bash
composer start-server
```

The site will be available under `http://graphql-api.lndo.site`.

To access the [wp-admin](http://graphql-api.lndo.site/wp-admin/):

- User: `admin`
- Password: `admin`

## Disable caching/purge the cache, during development

By default, the DEV webserver will have caching enabled. Cached elements include:

- The service containers (from Symfony's Dependency Injection)
- The generated configuration (mapping the component model to queries)
- The calculated GraphQL schema (only when module [Schema Cache](../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/docs/en/modules/schema-cache.md) is enabled)

When developing, we must either disable the caching, or purge the cache, to test our changes.

### Enable/disable caching

Caching is set via constant `GRAPHQL_API_CACHE_CONTAINERS` in `wp-config.php`, with either values `true` or `false`.

To define this constant, we can execute the following Composer scripts:

```bash
$ composer disable-cache
$ composer enable-cache
```

### Purge the cache

Cached files are stored under the plugin's `cache` subfolder.

To purge them, simply delete this folder, or execute the following Composer script:

```bash
composer purge-cache
```

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

- [Lando](https://lando.dev/)
