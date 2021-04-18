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

### Debugging

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
