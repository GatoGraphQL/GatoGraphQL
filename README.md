<p align="center"><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/master/assets/GatoGraphQL-logo.png"/></p>

![Unit tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/unit_tests.yml/badge.svg)<!-- @todo Re-enable executing integration tests for PROD in CI --><!-- @see https://github.com/GatoGraphQL/GatoGraphQL/issues/2253 --><!-- ![Integration tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/integration_tests.yml/badge.svg) -->
![Downgrade PHP tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/downgrade_php_tests.yml/badge.svg)
![Scoping tests](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/scoping_tests.yml/badge.svg)
![Generate plugins](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/generate_plugins.yml/badge.svg)
<!-- ![PHPStan](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/phpstan.yml/badge.svg) -->

# Gato GraphQL

Gato GraphQL is a **tool for interacting with data in your WordPress site**. You can think of it as a Swiss Army knife for dealing with data, as it allows to retrieve, manipulate and store again any piece of data, in any desired way, using the [GraphQL language](https://graphql.org/).

With Gato GraphQL, you can:

- Query data to create headless sites
- Expose public and private APIs
- Map JS components to Gutenberg blocks
- Synchronize content across sites
- Automate tasks
- Complement WP-CLI to execute admin tasks
- Search/replace content for site migrations
- Send notifications when something happens (new post published, new comment added, etc)
- Interact with cloud services
- Convert the data from a 3rd-party API into the required format
- Translate content in the site
- Update thousands of posts with a single action
- Insert or remove Gutenberg blocks in bulk
- Validate that a new post contains a mandatory block
- And much more...

Check out the [Tutorial section in gatographql.com](https://gatographql.com/tutorial/intro) which demonstrates how to implement these use cases using the plugin.

<details>

<summary>View screenshots</summary>

GraphiQL client to execute queries in the wp-admin:

![GraphiQL client to execute queries in the wp-admin](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-1.png)

Interactively browse the GraphQL schema, exploring all connections among entities:

![Interactively browse the GraphQL schema, exploring all connections among entities](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-2.png)

The GraphiQL client for the single endpoint is exposed to the Internet:

![The GraphiQL client for the single endpoint is exposed to the Internet](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-3.png)

Interactively browse the GraphQL schema exposed for the single endpoint:

![Interactively browse the GraphQL schema exposed for the single endpoint](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-4.png)

Persisted queries are pre-defined and stored in the server:

![Persisted queries are pre-defined and stored in the server](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-5.png)

Requesting a persisted query URL will retrieve its pre-defined GraphQL response:

![Requesting a persisted query URL will retrieve its pre-defined GraphQL response](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-6.png)

We can create multiple custom endpoints, each for a different target:

![We can create multiple custom endpoints, each for a different target](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-7.png)

Endpoints are configured via Schema Configurations:

![Endpoints are configured via Schema Configurations](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-8.png)

We can create many Schema Configurations, customizing them for different users or applications:

![We can create many Schema Configurations, customizing them for different users or applications](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-9.png)

Custom endpoints and Persisted queries can be public, private and password-protected:

![Custom endpoints and Persisted queries can be public, private and password-protected](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-10.png)

Manage custom endpoints and persisted queries by adding categories to them:

![Manage custom endpoints and persisted queries by adding categories to them](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-11.png)

We can configure exactly what custom post types, options and meta keys can be queried:

![We can configure exactly what custom post types, options and meta keys can be queried](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-12.png)

Configure every aspect from the plugin via the Settings page:

![Configure every aspect from the plugin via the Settings page](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-13.png)

Modules with different functionalities and schema extensions can be enabled and disabled:

![Modules with different functionalities and schema extensions can be enabled and disabled](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-14.png)

Augment the plugin functionality and GraphQL schema via extensions:

![Augment the plugin functionality and GraphQL schema via extensions](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-15.png)

The Tutorial section explains how to achieve many objectives, exploring all the elements from the GraphQL schema:

![The Tutorial section explains how to achieve many objectives, exploring all the elements from the GraphQL schema](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-16.png)

</details>

## Development Requirements

- PHP 8.1
- [Lando](https://lando.dev/)
- [Composer](https://getcomposer.org/)

### Recommended to use

- [XDebug](https://xdebug.org/) (integrated out of the box when using [VSCode](https://code.visualstudio.com/) and the [PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug) addon for VSCode)

## Install for Development

Follow these steps:

### Clone repo locally

Clone the repo in your local drive:

```bash
git clone https://github.com/GatoGraphQL/GatoGraphQL
```

### Install Composer dependencies

Run:

```bash
$ cd {project folder}
$ cd submodules/GatoGraphQL && composer install && cd ../.. && composer install
```

## Run the webserver for DEV

A Lando webserver for development is already set-up:

- Runs on PHP 8.1
- It directly uses the source code on the repo
- XDebug is enabled

Follow these steps:

### Build the Lando webserver for DEV

Run (only the first time):

```bash
composer build-server
```

After a few minutes, the website will be available under `https://gatographql.lndo.site`.

To print the server information, including the port to connect to the MySql database (so you can visualize and edit the data in the DB using an external client, such as MySQLWorkbench), run:

```bash
composer server-info
```

<details>

<summary>What plugins are installed in the webserver? 🤔</summary>

- [Gato GraphQL](layers/GatoGraphQLForWP/plugins/gatographql/gatographql.php)
- [Gato GraphQL - Testing](layers/GatoGraphQLForWP/phpunit-plugins/gatographql-testing/gatographql-testing.php)
- [Gato GraphQL - Testing Schema](layers/GatoGraphQLForWP/plugins/testing-schema/gatographql-testing-schema.php)

(The last two are utilities to run integration tests for Gato GraphQL. Among others, they provide CPT "dummy-cpt" and custom taxonomies "dummy-category" and "dummy-tag").

</details>

### Log-in to the `wp-admin`

Credentials for `https://gatographql.lndo.site/wp-admin/`:

- Username: `admin`
- Password: `admin`

### Open the Gato GraphQL plugin in the `wp-admin`

Click on the Gato GraphQL link on the menu to open the GraphiQL client, and execute the following GraphQL query:

```graphql
{
  posts {
    id
    title
  }
}
```

If the installation of the webserver was successful, you will receive a response:

![GraphQL response in the Lando webserver](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-1.png)

### Run Integration Tests

Run:

``` bash
composer integration-test
```

### Start the Lando webserver for DEV

Building the webserver (above) is needed only the first time.

From then on, run:

```bash
composer init-server
```

## Run the webserver for PROD

A Lando webserver is set-up and configured to test the released plugins, generated by GitHub Actions.

- Runs on PHP 7.2
- You must download the generated plugins for PROD (from GitHub Actions) and install them on the webserver
- XDebug is not enabled

Follow these steps:

### Build the Lando webserver for PROD

Run (only the first time):

```bash
composer build-server-prod
```

After a few minutes, the website will be available under `https://gatographql-for-prod.lndo.site`.

(The URL is the same one as for DEV above, plus appending `-for-prod` to the domain name.)

To print the server information, run:

```bash
composer server-info-prod
```

The `wp-admin` login credentials are the same ones as for DEV.

### Start the Lando webserver for PROD

Building the webserver (above) is needed only the first time.

From then on, run:

```bash
composer init-server-prod
```

## Manage the Lando webservers

### Re-install the WordPress site (DEV and PROD)

You can at any moment re-install the WordPress site (and import the initial dataset).

On the DEV webserver:

```bash
composer reset-db
```

On PROD:

```bash
composer reset-db-prod
```

This is useful when:

- The installation when doing `build-server` was halted midway (or failed for some reason)
- Running the integration tests was not completed (modifying the DB data to a different state, so that running the tests again will fail)

### Re-build the Lando webserver for DEV

When a plugin or package folder has been renamed, you need to update the path in the `overrides` section of the [`.lando.upstream.yml`](webservers/gatographql/.lando.upstream.yml) Lando config file, and then rebuild the Lando webserver to reflect these changes.

Run:

```bash
composer rebuild-server
```

### Regenerate the Composer autoload files for DEV

When a new plugin is added to the monorepo, it must have its Composer autoload file generated, and the plugin must be symlinked to the Lando webserver.

Run:

```bash
composer rebuild-app-and-server
```

## Releasing the plugins for PROD

_(This is done by the [admin of the repo][link-author].)_

The monorepo includes scripts that completely automate the process of releasing the plugins in the monorepo.

Follow these steps:

### Tag the monorepo as `patch`, `minor` or `major`

Choose which version you will be releasing. The same version will be applied to all plugins in the monorepo.

_(Given that the current version is `0.0.0`...)_

To release version `0.0.1`, run:

```bash
composer release-patch
```

To release version `0.1.0`, run:

```bash
composer release-minor
```

To release version `1.0.0`, run:

```bash
composer release-major
```

<details>

<summary>What do these commands do? 🤔</summary>

Executing these commands will first prepare the repo for PROD:

- Update the version (in the plugin file's header, readme.txt's Stable tag, others) for all the extension plugins in the monorepo
- Update the documentation image URLs to point to that tag, under `raw.githubusercontent.com`
- Commit and push
- Git tag with the version, and push tag to GitHub

And then, it will prepare the repo for DEV again:

- Update the version to the next DEV version (next semver + `-dev`)
- Commit and push

</details>

To preview running the command without actually executing it, append `-- --dry-run`:

```bash
composer release-patch -- --dry-run
```

### Create release from tag in GitHub

After tagging the repo on the step above, we must create a release from the tag to generate the extension plugins for production.

To create the release, we must head over to the [`tags` page in the GitHub repo](https://github.com/GatoGraphQL/GatoGraphQL/tags), and click on the new tag (eg: `0.1.0`).

Then, on the tag page, click on `Create release from tag`.

![Create release from tag](assets/img/create-release-from-tag.png)

This will trigger the [`generate_plugins.yml`](https://github.com/GatoGraphQL/GatoGraphQL/actions/workflows/generate_plugins.yml) workflow, which will generate the extension plugins and attach them as assets to the tag page.

For instance, after tagging Gato GraphQL with `1.0.9`, the tag page [GatoGraphQL/GatoGraphQL/releases/tag/1.0.9](https://github.com/GatoGraphQL/GatoGraphQL/releases/tag/1.0.9) had the following assets attached to it:

- `gatographql-1.0.9.zip`
- `gatographql-testing-1.0.9.zip`
- `gatographql-testing-schema-1.0.9.zip`

### Install the extension in the PROD webserver

Once the extension plugin has been generated, install it on the PROD webserver to test it, whether manually or using WP-CLI.

Using WP-CLI, to test the released version `0.1.0`, run:

```bash
$ cd webservers/gatographql-for-prod
$ lando wp plugin install https://github.com/GatoGraphQL/GatoGraphQL/releases/latest/download/gatographql-0.1.0.zip --force --activate
$ cd ../..
```

### Query the extension in the `wp-admin`

Once you've installed the release on the Lando webserver for PROD, log-in to the `wp-admin`, access the GraphiQL client, and execute the following GraphQL query:

```graphql
{
  posts {
    id
    title
  }
}
```

If the installation of the webserver was successful, you will receive a response:

![GraphQL response in the Lando webserver](layers/GatoGraphQLForWP/plugins/gatographql/.wordpress-org/screenshot-1.png)

### Run Integration Tests for PROD

Run:

``` bash
composer integration-test-prod
```

## Development Process

### Purging the cache

When developing the plugin and testing it in the DEV webserver, whenever we create a new PHP service or modify the signature of an existing one (such as the PHP classname), we need to purge the container cache.

Run:

```bash
composer purge-cache
```

<details>

<summary>How does Gato GraphQL use a service container? 🤔</summary>

The Gato GraphQL plugin uses a service container (via the [Symfony DependencyInjection](https://symfony.com/doc/current/components/dependency_injection.html) library), to manage all services in PHP.

Services are PHP classes, and must be defined in configuration files `services.yaml` and `schema-services.yaml` to be injected into the container.

The first time the application is invoked, the container gathers all injected services and compiles them, generating a single PHP file that is loaded in the application.

Generating this file can take several seconds. To avoid waiting for this time on each request, the Gato GraphQL plugin caches this file after it has been generated the first time.

The container needs to be purged whenever a service is created, or an existing one updated or removed.

(In production, the Gato GraphQL plugin purges the cache whenever a new extension plugin is activated, or when the plugin Settings are updated. During development, it can in addition be triggered manually, by running `composer purge-cache`.)

This applies to resolvers (type resolvers, field resolvers, directive resolvers, and any other resolver that gives shape to the GraphQL schema), as these are PHP services. Whenever a resolver is added or removed, or is updated in such a way that modifies the GraphQL schema, the cached container must be purged.

Some example resolvers are:

- `String` type: [`StringScalarTypeResolver`](https://github.com/GatoGraphQL/GatoGraphQL/blob/1.0.0/layers/Engine/packages/component-model/src/TypeResolvers/ScalarType/StringScalarTypeResolver.php)
- Field `User.name` (and others): [`UserObjectTypeFieldResolver`](https://github.com/GatoGraphQL/GatoGraphQL/blob/1.0.0/layers/CMSSchema/packages/users/src/FieldResolvers/ObjectType/UserObjectTypeFieldResolver.php)
- `@skip` directive: [`SkipFieldDirectiveResolver`](https://github.com/GatoGraphQL/GatoGraphQL/blob/1.0.0/layers/Engine/packages/engine/src/DirectiveResolvers/SkipFieldDirectiveResolver.php)

</details>

### Regenerating the monorepo configuration

After adding a plugin or package to the monorepo, the configuration (containing all the packages) must be regenerated.

Run:

```bash
composer update-monorepo-config
```

<details>

<summary>What does command <code>update-monorepo-config</code> do? 🤔</summary>

The `update-monorepo-config` command will:

- Regenerate the root `composer.json`, adding the new packages
- Regenerate the root `phpstan.neon`, adding the new packages

</details>

## Development Tools

### Error logs

Access the error logs from the Lando webserver:

```bash
composer log-server-errors
```

For PROD:

```bash
composer log-server-errors-prod
```

### SSH

SSH into the Lando webserver:

```bash
composer ssh-server
```

For PROD:

```bash
composer ssh-server-prod
```

## Debugging

XDebug is already integrated when using VSCode.

Add a breakpoint in the source code and then, in the `Run and Debug` tab, press on `Start Debugging` with the corresponding configuration (defined in [`.vscode/launch.json`](.vscode/launch.json)):

- `[Lando webserver] Listen for Xdebug`: For debugging the source code while running the Lando webserver for DEV
- `[PHPUnit] Listen for Xdebug`: For debugging PHPUnit tests

XDebug is enabled but inactive; it must be activated when requesting the webpage (see below).

### Debugging in the Lando webserver for DEV

Activate XDebug for a request by appending parameter `?XDEBUG_TRIGGER=1` to the URL (for any page on the Gato GraphQL plugin, including any page in the wp-admin, the GraphiQL or Interactive Schema public clients, or other).

For instance:

- `https://gatographql.lndo.site/wp-admin/edit.php?page=gatographql&action=execute_query&XDEBUG_TRIGGER=1`
- `https://gatographql.lndo.site/graphiql/?XDEBUG_TRIGGER=1`

### Debugging PHPUnit tests

Activate XDebug by prepending `XDEBUG_TRIGGER=1` before the `phpunit` command to run the unit tests.

For instance:

```bash
XDEBUG_TRIGGER=1 vendor/bin/phpunit layers/GatoGraphQLForWP/phpunit-packages/gatographql/tests/Unit/Faker/WPFakerFixtureQueryExecutionGraphQLServerTest.php
```

## Monorepo Commands

Retrieve the list of all Composer commands available in the monorepo:

```bash
composer list
```

<details>

<summary>View all monorepo commands</summary>

| `composer` command | Description |
| --- | --- |
| `analyse` | Run PHPStan static analysis of the code |
| `build-js` | Build all JS packages, blocks and editor scripts for all plugins in the Gato GraphQL - Extension Starter repo |
| `build-server` | Initialize the Lando webserver with the 'Gato GraphQL' demo site, for development. To be executed only the first time |
| `build-server-prod` | Initialize the Lando webserver with the 'Gato GraphQL' demo site, for production. To be executed only the first time |
| `check-style` | Validate PSR-12 coding standards (via phpcs) |
| `debug` | Run and debug PHPUnit tests |
| `delete-settings` | Delete the plugin settings from the DB |
| `deoptimize-autoloader` | Removes the optimization of the Composer autoloaders for all the plugins installed in the webserver |
| `destroy-server` | Destroy the Lando webserver with the 'Gato GraphQL' demo site |
| `destroy-server-prod` | Destroy the Lando webserver with the 'Gato GraphQL' demo site for PROD |
| `disable-caching` | Disable caching for the 'Gato GraphQL' in DEV |
| `disable-restrictive-defaults` | Do not use restrictive default values for the Settings |
| `enable-caching` | Enable caching for the 'Gato GraphQL' in DEV |
| `enable-restrictive-defaults` | Use restrictive default values for the Settings |
| `fix-style` | Fix PSR-12 coding standards (via phpcbf) |
| `import-data` | Imports pre-defined data into the DB (posts, users, CPTs, etc) |
| `improve-code-quality` | Improve code quality (via Rector) |
| `init-server` | Alias of 'start-server |
| `init-server-prod` | Runs the init-server-prod script as defined in composer.json |
| `install-deps-build-js` | Install all dependencies from npm to build the JS packages, blocks and editor scripts for all plugins in the Gato GraphQL - Extension Starter repo |
| `install-site` | Installs the WordPress site |
| `install-site-prod` | Installs the WordPress site in the PROD server |
| `integration-test` | Execute integration tests (PHPUnit) |
| `integration-test-prod` | Execute integration tests (PHPUnit) against the PROD webserver |
| `log-server-errors` | Show (on real time) the errors from the Lando webserver |
| `log-server-errors-prod` | Show (on real time) the errors from the Lando webserver for PROD |
| `log-server-warnings` | Show (on real time) the warnings from the Lando webserver |
| `log-server-warnings-prod` | Show (on real time) the warnings from the Lando webserver for PROD |
| `merge-monorepo` | Create the monorepo's composer.json file, containing all dependencies from all packages |
| `merge-phpstan` | Generate a single PHPStan config for the monorepo, invoking the config for the PHPStan config for all packages |
| `preview-code-downgrade` | Run Rector in 'dry-run' mode to preview how the all code (i.e. src/ + vendor/ folders) will be downgraded to PHP 7.2 |
| `preview-src-downgrade` | Run Rector in 'dry-run' mode to preview how the src/ folder will be downgraded to PHP 7.2 |
| `preview-vendor-downgrade` | Run Rector in 'dry-run' mode to preview how the vendor/ folder will be downgraded to PHP 7.2 |
| `propagate-monorepo` | Propagate versions from the monorepo's composer.json file to all packages |
| `purge-cache` | Purge the cache for the 'Gato GraphQL' in DEV |
| `rebuild-app-and-server` | Update the App dependencies (from Composer) and rebuild the Lando webserver |
| `rebuild-server` | Rebuild the Lando webserver |
| `rebuild-server-prod` | Rebuild the Lando webserver for PROD |
| `release-major` | Release a new 'major' version (MAJOR.xx.xx) (bump version, commit, push, tag, revert to 'dev-master', commit, push) |
| `release-minor` | Release a new 'minor' version (xx.MINOR.xx) (bump version, commit, push, tag, revert to 'dev-master', commit, push) |
| `release-patch` | Release a new 'patch' version (xx.xx.PATCH) (bump version, commit, push, tag, revert to 'dev-master', commit, push) |
| `remove-unused-imports` | Remove unused `use` imports |
| `reset-db` | Resets the WordPress database |
| `reset-db-prod` | Resets the WordPress database in the PROD server |
| `server-info` | Retrieve information from the Lando webserver |
| `server-info-prod` | Retrieve information from the Lando webserver for PROD |
| `ssh-server` | SSH into the Lando webserver with the 'Gato GraphQL' demo site |
| `ssh-server-prod` | SSH into the Lando webserver for PROD with the 'Gato GraphQL' demo site |
| `start-server` | Start the Lando webserver with the 'Gato GraphQL' demo site, for development |
| `start-server-prod` | Start the Lando webserver for PROD with the 'Gato GraphQL' demo site, for development |
| `stop-server` | Stop the Lando webserver |
| `stop-server-prod` | Stop the Lando webserver for PROD |
| `stopping-integration-test` | Runs the stopping-integration-test script as defined in composer.json |
| `stopping-integration-test-prod` | Execute integration tests (PHPUnit) against the PROD webserver, stopping as soon as there's an error or failure |
| `stopping-unit-test` | Runs the stopping-unit-test script as defined in composer.json |
| `unit-test` | Execute unit tests (PHPUnit) |
| `update-deps` | Update the Composer dependencies for the 'Gato GraphQL' plugins |
| `update-monorepo-config` | Update the monorepo's composer.json and phpstan.neon files, with data from all packages |
| `use-default-restrictive-defaults` | Remove the set value, use the default one |
| `validate-monorepo` | Validate that version constraints for dependencies are the same for all packages |

</details>

<!-- ## Installing the Gato GraphQL plugin in WordPress

_For alternative installation methods, check [Installing Gato GraphQL](docs/installing-gatographql-for-wordpress.md)._

Download [the latest release of the plugin][latest-release-url] as a .zip file.

Then, in the WordPress admin:

- Go to `Plugins => Add New`
- Click on `Upload Plugin`
- Select the .zip file
- Click on `Install Now` (it may take a few minutes)
- Once installed, click on `Activate`

Requirements:

- WordPress 5.4+
- PHP 7.2+ -->

## Extending the GraphQL schema

Create an extension for Gato GraphQL using [`GatoGraphQL/ExtensionStarter`](https://github.com/GatoGraphQL/ExtensionStarter).

## Development

<!-- - [Setting-up the development environment](docs/development-environment.md)
- [Running tests](docs/running-tests.md) -->

### Supported PHP features

Check the list of [Supported PHP features](docs/supported-php-features.md).

### Gutenberg JS builds

Compiled JavaScript code (such as all files under a block's `build/` folder) is added to the repo, but only as compiled for production, i.e. after running `npm run build`.

Code compiled for development, i.e. after running `npm start`, cannot be commited/pushed to the repo.

<details>

<summary>Architectural resources</summary>

### PHP Architecture

Articles explaining how the plugin is "downgraded", using PHP 8.1 for development but deployable to PHP 7.2 for production:

1. [Transpiling PHP code from 8.0 to 7.x via Rector](https://blog.logrocket.com/transpiling-php-code-from-8-0-to-7-x-via-rector/)
2. [Coding in PHP 7.4 and deploying to 7.1 via Rector and GitHub Actions](https://blog.logrocket.com/coding-in-php-7-4-and-deploying-to-7-1-via-rector-and-github-actions/)
3. [Tips for transpiling code from PHP 8.0 down to 7.1](https://blog.logrocket.com/tips-transpiling-code-from-php-8-0-to-7-1/)
4. [Including both PHP 7.1 and 8.0 code in the same plugin … or not?](https://blog.logrocket.com/including-php-7-1-and-8-0-code-same-plugin-or-not/)

Service container implementation:

- [Building extensible PHP apps with Symfony DI](https://blog.logrocket.com/building-extensible-php-apps-with-symfony-di/)

Explanation of how the codebase is split into granular packages, to enable CMS-agnosticism:

1. [Abstracting WordPress Code To Reuse With Other CMSs: Concepts (Part 1)](https://www.smashingmagazine.com/2019/11/abstracting-wordpress-code-cms-concepts/)
2. [Abstracting WordPress Code To Reuse With Other CMSs: Implementation (Part 2)](https://www.smashingmagazine.com/2019/11/abstracting-wordpress-code-reuse-with-other-cms-implementation/)

Description of how the plugin is scoped:

- [Gato GraphQL is now scoped, thanks to PHP-Scoper!](https://gatographql.com/blog/gatographql-is-now-scoped-thanks-to-php-scoper/)

### GraphQL by PoP documentation

Gato GraphQL is powered by the CMS-agnostic GraphQL server [GraphQL by PoP](https://graphql-by-pop.com).

Technical information on how the GraphQL server works:

- [GraphQL by PoP documentation](https://graphql-by-pop.com/docs/getting-started/intro.html).

Description of how a GraphQL server using server-side components works:

- [Implementing a GraphQL server with components in PHP](https://www.wpkube.com/implementing-graphql-server/)

These articles explain the concepts, design and implementation of GraphQL by PoP:

1. [Designing a GraphQL server for optimal performance](https://blog.logrocket.com/designing-graphql-server-optimal-performance/)
2. [Simplifying the GraphQL data model](https://blog.logrocket.com/simplifying-the-graphql-data-model/)
3. [Schema-first vs code-first development in GraphQL](https://blog.logrocket.com/code-first-vs-schema-first-development-graphql/)
4. [Speeding-up changes to the GraphQL schema](https://blog.logrocket.com/speeding-up-changes-to-the-graphql-schema/)
5. [Versioning fields in GraphQL](https://blog.logrocket.com/versioning-fields-graphql/)
6. [GraphQL directives are underrated](https://blog.logrocket.com/graphql-directives-are-underrated/)
7. [Treating GraphQL directives as middleware](https://blog.logrocket.com/treating-graphql-directives-as-middleware/) 
8. [Creating an @export GraphQL directive](https://blog.logrocket.com/creating-an-export-graphql-directive/)
9. [Adding directives to the schema in code-first GraphQL servers](https://blog.logrocket.com/adding-directives-schema-code-first-graphql-servers/)
10. [Coding a GraphQL server in JavaScript vs. WordPress](https://blog.logrocket.com/coding-a-graphql-server-in-javascript-vs-wordpress/)
11. [Supporting opt-in nested mutations in GraphQL](https://blog.logrocket.com/supporting-opt-in-nested-mutations-in-graphql/)
12. [HTTP caching in GraphQL](https://blog.logrocket.com/http-caching-graphql/)

### Gutenberg

These articles explain the integration with Gutenberg (the WordPress editor).

1. [Adding a Custom Welcome Guide to the WordPress Block Editor](https://css-tricks.com/adding-a-custom-welcome-guide-to-the-wordpress-block-editor/)
2. [Using Markdown and Localization in the WordPress Block Editor](https://css-tricks.com/using-markdown-and-localization-in-the-wordpress-block-editor/)

</details>

## Monorepo documentation

`GatoGraphQL/GatoGraphQL` is a monorepo containing the several layers required for Gato GraphQL. Check [Monorepo_README.md](Monorepo_README.md) for documentation of the different projects.

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

## Release notes

- **[2.5](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.5/en.md)** (current)
- [2.4](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.4/en.md)
- [2.3](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.3/en.md)
- [2.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.2/en.md)
- [2.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.1/en.md)
- [2.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/2.0/en.md)
- [1.5](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.5/en.md)
- [1.4](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.4/en.md)
- [1.3](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.3/en.md)
- [1.2](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.2/en.md)
- [1.1](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.1/en.md)
- [1.0](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/1.0/en.md)
- [0.10](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.10/en.md)
- [0.9](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.9/en.md)
- [0.8](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.8/en.md)
- [0.7](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.7/en.md)
- [0.6](layers/GatoGraphQLForWP/plugins/gatographql/docs/release-notes/0.6/en.md)

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

## Downgrading code

To visualize how [Rector](https://github.com/rectorphp/rector) will downgrade the code to PHP 7.2:

```bash
composer preview-code-downgrade
```

## Report issues

To report a bug or request a new feature please do it on the [GatoGraphQL monorepo issue tracker](https://github.com/GatoGraphQL/GatoGraphQL/issues).

## Contributing

We welcome contributions for this package on the [GatoGraphQL monorepo](https://github.com/GatoGraphQL/GatoGraphQL) (where the source code for this package is hosted).

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]

## License

GPLv2 or later. Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-GPL%20(%3E%3D%202)-brightgreen.svg?style=flat-square
[ico-release]: https://img.shields.io/github/release/GatoGraphQL/GatoGraphQL.svg
[ico-downloads]: https://img.shields.io/github/downloads/GatoGraphQL/GatoGraphQL/total.svg

[link-author]: https://github.com/leoloso
[latest-release-url]: https://gatographql.com/releases/latest
