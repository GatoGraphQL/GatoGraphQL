# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

`GatoGraphQL/GatoGraphQL` is a monorepo of ~150 Composer packages that compiles into **one** distributable WordPress plugin: a GraphQL server for WordPress.

The stack is layered from a CMS-agnostic component framework up to WordPress specifics, wired at runtime by a DI-container module graph, and compiled by CI into a PHP-7.4-downgraded, PHP-Scoper-prefixed `.zip`.

Orientation docs: `README.md` (the product), `Monorepo_README.md` (what each layer is), `DEVELOPMENT.md` (full dev setup), `docs/development-environment.md` (Lando, caching, debugging), `instructions.md` (long-form development guidelines).

## Architecture

### The layers stack

`assets/img/dependency-graph.svg` is the visual reference. Dependency direction, bottom → top:

| Layer | Vendor prefix | What it is |
| --- | --- | --- |
| `Backbone` | `pop-backbone/*` | Zero-dependency scaffolding (CMS-agnostic hooks). The true floor. |
| `Engine` | `getpop/*` | The PoP framework: `root` (App, AppLoader, DI, Module system) → `component-model` (resolver abstractions, data loading) → `engine` (query execution). Plus `*-wp` bindings. |
| `API` | `pop-api/*` | Transport/endpoints on top of Engine. Response formats: `api-rest`, `api-mirrorquery`, `api-graphql`. |
| `GraphQLByPoP` | `graphql-by-pop/*` | GraphQL-the-spec on top of API: server, introspection, spec error handling, and the GraphiQL/Voyager clients. |
| `Schema` | `pop-schema/*` | CMS-agnostic, **domain-agnostic** building blocks: scalars (`Email`, `URL`), common directives, logger. No posts, no users. |
| `CMSSchema` | `pop-cms-schema/*` | The **abstract CMS domain model** — customposts, users, comments, taxonomies, media, menus, meta + mutations — defined against interfaces. |
| `WPSchema` | `pop-wp-schema/*` | WordPress-only features with no CMS-agnostic counterpart: `blocks`, `pagebuilder`, `multisite`, `site`. |
| `GatoGraphQLForWP` | `gatographql/*` | `plugins/gatographql/` is THE plugin. Also `phpunit-packages/` + `phpunit-plugins/`. |
| `GatoGraphQLStandaloneForWP` | `gatographql-standalone/*` | The PHP-Scoper-prefixed standalone variant. |

Four things here are counter-intuitive:

- **The direction is Engine → API → GraphQLByPoP**, not Engine → GraphQL → API. GraphQL is a format layered onto a generic API layer — a legacy of PoP being a general component framework where GraphQL was one of several response formats.
- **`Schema` is not the CMS schema.** `pop-schema/*` is scalars/directives/logger. The CMS domain model is `CMSSchema`.
- **WordPress code lives in three tiers, not just `WPSchema`**: `Engine/packages/engine-wp` (framework bindings), `CMSSchema/packages/*-wp` (implementations of abstract contracts), `WPSchema/packages/*` (WP-only features).
- **The `-wp` twin / satisfying-module handshake.** `pop-cms-schema/users` declares `requiresSatisfyingModule(): true`, so it stays **disabled** until `pop-cms-schema/users-wp` declares `getSatisfiedModuleClasses(): [Users\Module::class]`. A package's `isEnabled()` can therefore be false for a reason that isn't in its own code.

### Two unrelated things are called "Module"

This is the single biggest source of confusion.

| | **PoP Module** | **Gato Module** |
| --- | --- | --- |
| Where | `{package}/src/Module.php` | `plugins/gatographql/src/ModuleResolvers/*` |
| What | a class extending `PoP\Root\Module\AbstractModule` | a **string** slug, e.g. `…\schema-users` |
| Audience | the DI container | the end user, via wp-admin checkboxes |
| Registry | `App::getModule()` / `AppLoader` | `SystemModuleRegistryFacade::isModuleEnabled($slug)` |
| State | code + env vars | persisted in WP options |
| Count | one per package (~150) | one per user-visible feature |

A `ModuleResolver` is a descriptor provider for the admin UI (`getName()`, `getDescription()`, `getSettings()`, `getDependedModuleLists()`, `isEnabledByDefault()`, …) and resolves *many* slugs via `getModulesToResolve()`.

**The bridge** is `PluginInitializationConfiguration::getModuleClassesToSkipIfModuleDisabled()`, mapping user slug → PoP Module classes. When the user disables a Gato Module, those classes land in `$skipSchemaModuleClasses`.

**Crucially: disabling a Gato Module does NOT disable the PoP Module.** The module still initializes and its `services.yaml` still loads — only its schema services are loaded *un-autoconfigured*, so they're never collected into the schema. `isEnabled()` still returns `true`. Chasing `resolveEnabled()` to explain why a type vanished is the classic wrong turn; the answer is `skipSchema`.

A second bridge, `getEnvironmentConstantsFromSettingsMapping()`, maps a settings field → a PoP Module env var.

The schema is **per-request, not per-site**: `getSchemaModuleClassesToSkip()` returns `[]` for the block-editor endpoint and is filterable per admin endpoint group, so different endpoints on one site expose different schemas.

### `services.yaml` vs `schema-services.yaml`

Every package's `Module::initializeContainerServices()` calls:

- `initServices()` → `config/services.yaml` — infrastructure (TypeAPIs, helpers). Always loaded.
- `initSchemaServices()` → `config/schema-services.yaml` — the resolvers that materialize the schema. Loaded with **`$autoconfigure = !$skipSchema`**.

That flag is the whole trick: when skipping, the services still exist in the container but aren't tagged, so they never reach the registries that assemble the schema. Building the GraphQL schema is expensive, so a user who disables "Users" shouldn't pay for the `User` type.

Both loaders no-op when the container is cached — so `Module.php` code runs only on a cache miss.

`src/ConditionalOnModule/{X}/` (mirrored by `config/ConditionalOnModule/{X}/`) holds cross-package integration that materializes only if module X is also enabled. It composes (`ConditionalOnModule/CustomPosts/ConditionalOnModule/API`) and is guarded by `try { … } catch (ComponentNotExistsException) {}`. This is how `CustomPost.author` exists in the **users** package without `customposts` and `users` depending on each other.

### Fields declare their type — types never list their fields

The most disorienting inversion in the codebase, and it's load-bearing:

```php
// UserObjectTypeFieldResolver.php
public function getObjectTypeResolverClassesToAttachTo(): array
{
    return [UserObjectTypeResolver::class];
}
public function getFieldNamesToResolve(): array
{
    return ['name', 'displayName', 'email', /* … */];
}
```

`UserObjectTypeResolver` contains **no field list at all** — only `getTypeName()`, `getTypeDescription()`, `getID()`, `getRelationalTypeDataLoader()`. The schema is assembled at runtime by the container collecting every autoconfigured field resolver and grouping by its attach-to declaration (~337 files implement this).

**So you cannot find a type's fields by reading its resolver.** Grep for the type's class inside `getObjectTypeResolverClassesToAttachTo()` across all layers.

This is what makes the schema Open/Closed: any package (or third-party extension) adds fields to any type without touching it. The same inversion applies to unions — an `ObjectTypeResolverPicker` attaches to the union, so members are contributed, not listed.

Resolver taxonomy: **ObjectTypeResolver** (type identity) · **ObjectTypeFieldResolver** (fields + args, declares its attach-to) · **RelationalTypeDataLoader** (batch-loads objects by ID — the N+1 solution) · **ObjectTypeResolverPicker** (for a union: which member handles this object) · **UnionTypeResolver** · **InputObjectTypeResolver** (`*Oneof…` for `@oneOf`).

Mutations come in two tiers throughout: `XMutationResolver` (throws/returns errors) and `PayloadableXMutationResolver` (returns a payload union with typed error objects). Both exist because users choose via `MutationPayloadTypeOptions` / `MutationSchemes`.

### One plugin from many packages

`plugins/gatographql/composer.json` requires ~60 monorepo packages by Composer name; transitive deps pull in the rest; **the plugin's `vendor/` ships inside the `.zip`**. The boundary between "monorepo package" and "vendor dependency" is erased at build time.

In **dev**, `vendor/` entries are **symlinks back into `layers/`** (`monorepo-builder symlink-local-package`) — editing `layers/Engine/packages/engine/src/…` *is* editing the plugin's vendor, no reinstall.

`src/PluginSkeleton/` is a reusable plugin framework shipped inside the plugin — `AbstractPlugin`, `AbstractMainPlugin`, `AbstractExtension`, the `*InitializationConfiguration` hierarchy, and `PluginLifecycleHooks`. **The main plugin is also the extension SDK.**

In `gatographql.php`, order matters and is documented in-file: capabilities are required *before* the autoloader (vendor loads on `plugins_loaded`, too late to register them); a memory check bails early; then `PluginApp::initializePlugin()`.

`$pluginVersion` and `$commitHash` are marked **`@gatographql-readonly-code` and rewritten by CI with regex** — copy them verbatim, never reformat.

### Build / release

Configuration is **PHP data-source classes**, not config files: `src/Config/Symplify/MonorepoBuilder/DataSources/` (`PluginDataSource`, `DowngradeRectorDataSource`, `PHPStanDataSource`, `DataToAppendAndRemoveDataSource`, …). Adding a plugin/package means editing a DataSource. The monorepo-builder container is configured in the root `monorepo-builder.php`.

- `merge-monorepo` — merges every package's `require`/`autoload` **up** into the root `composer.json` so the whole repo installs together.
- `merge-phpstan` — same for `phpstan.neon`.
- `propagate-monorepo` — pushes root constraints **down** into each package, keeping ~150 packages in lockstep.
- `validate-monorepo` — asserts the above are current; first step of `composer all`.
- `release-patch|minor|major` — runs the workers in `src/OnDemand/Symplify/MonorepoBuilder/Release/ReleaseWorker/`, which rewrite version strings across ~6 file types (main file, readme stable tag, compiled block markdown, package.json, monorepo metadata) and then restore `-dev`.

The PHP 8.1 → 7.4 downgrade lives in `config/rector/downgrade/` (one config per artifact, each with a `chained-rules/` folder for rules needing a separate second pass), driven by `ci/downgrade/downgrade_code.sh` — which **downgrades `vendor/` too**, not just this repo's source. `before_downgrade_code.sh` / `after_downgrade_code.sh` patch what Rector can't handle.

`.github/workflows/generate_plugins.yml` is the centerpiece: a JSON matrix of plugins → localize composer paths → install → Rector downgrade → `sed` the PHP requirement and commit hash → `--no-dev` install → PHP-Scoper → `scoper-autoload.php` → zip.

**The built artifact barely resembles the source** — PHP 7.4, scoper-prefixed namespaces, everything under one `vendor/`, tests and `composer.*` excluded. Never debug a production stack trace by reading `layers/` directly.

## Common commands

| Task | Command |
| --- | --- |
| All tests | `composer test` |
| Unit tests only | `composer unit-test` |
| Integration tests (needs Lando running) | `composer integration-test` |
| Integration tests against the **built** artifact | `composer integration-test-prod` |
| Stop at first failure | `composer stopping-unit-test` / `stopping-integration-test` |
| Run PHPUnit under Xdebug | `composer debug` |
| PHPStan | `composer analyse` |
| PSR-12 check / autofix | `composer check-style` / `composer fix-style` |
| WordPress-standard check / autofix | `composer check-style-wp` / `composer fix-style-wp` |
| Regenerate root composer.json + phpstan | `composer update-monorepo-config` |
| Propagate version constraints to packages | `composer propagate-monorepo` |
| Validate monorepo consistency | `composer validate-monorepo` |
| Preview the PHP 7.4 downgrade (dry run) | `composer preview-code-downgrade` |
| Build JS (blocks, editor scripts) | `composer build-js` (watch: `npm-start-js`) |
| Build the Lando dev server from scratch | `composer build-server` |
| Start / stop the Lando dev server | `composer start-server` / `stop-server` (`init-server` = start) |
| Reinstall + reseed the site | `composer install-site` |
| Purge the container cache | `composer purge-cache` |
| Tail server errors | `composer log-server-errors` |
| Release | `composer release-patch` / `release-minor` / `release-major` |
| Remove unused `use` imports | `composer remove-unused-imports` |
| Validate + test + analyse + fix style | `composer all` |

Most `*-server` / site scripts delegate to `webservers/gatographql` via `-d`; `*-prod` variants target `webservers/gatographql-for-prod`.

**A new DI service class needs `composer purge-cache`** — the compiled container is cached, so a *new* resolver/type/dataloader 500s with `ServiceNotFoundException` until purged. Editing an *existing* service needs no purge.

## Testing

Run a single test file or filter:

```bash
vendor/bin/phpunit path/to/SomeTest.php
vendor/bin/phpunit --filter SomeTestMethod
```

`--filter` matches across the **whole monorepo**, not one package — a loose filter runs far more than intended. Prefer the test file path.

There is **one testsuite** (`all`) globbing `layers/**/{packages,phpunit-packages,phpunit-plugins,plugins}/**/tests`, so a new package's `tests/` is discovered with zero config.

**Unit vs integration is decided by the substring `Integration` in the class's FQCN** — `unit-test` filters `/^((?!Integration).)*$/`, `integration-test` filters `Integration`. No testsuite enforces this: move a test out of the `\Integration\` namespace and it silently becomes a unit test running against no webserver.

Integration tests issue **real HTTP requests** against a Lando WordPress install (`gatographql.lndo.site`). `phpunit.xml.dist` hard-codes the domain and credentials for five roles (admin/editor/author/contributor/subscriber) — tests assert role-based access control, so the site must have all five seeded.

The fixture pattern (`FixtureEndpointWebserverRequestTestCaseTrait`) means **adding a test = adding two files, no PHP**: drop `my-query.gql` + `my-query.json` (the expected response) into a `fixture-*/` folder and it's collected automatically.

- `foo.var.json` — GraphQL variables.
- `foo:{name}.json` — expected response when run with `operationName = name`.
- `foo:{0,1,…}.json` — **numeric** means re-executing the *same* query and expecting a *different* response (setUp/tearDown don't re-run), to test statefulness like caching or mutation side effects.
- `*.disabled.gql` — excluded from discovery.
- A missing `.json` is a hard failure, not a skip.

The `.json` files are effectively golden/approval files — expect large mechanical diffs when the schema changes.

## Extending the GraphQL schema

`.cursor/rules/core.mdc` lists a canonical example file for every resolver kind (type, field, mutation, bulk mutation, scalar, enum, interface, union, union picker, input object, oneof input, object/union dataloader, directive, global field, enum string, error-payload union, nested mutation, filtering args, custom post type). Read it when doing schema work, and copy the cited file.

Two caveats about that rule file:

- **Its paths are prefixed `submodules/GatoGraphQL/…` and do not resolve here — strip the prefix.** `submodules/GatoGraphQL/layers/CMSSchema/packages/users/…` → `layers/CMSSchema/packages/users/…`.
- It contains commented-out blocks copy-pasted from an unrelated WP Fusion ruleset (`wpf_log()`, `wp_fusion()->crm->…`) — ignore them. Its "use `wp_error` for WordPress-style errors" line also contradicts the actual codebase, which uses the `FeedbackItemProvider` / Payloadable error-type system. Only the resolver-example table is authoritative.

The examples are starting points, not exhaustive — explore sibling resolvers in the same package, or analogous ones in another `layers/CMSSchema/packages/*`, when one doesn't fit.

## Conventions

`instructions.md` and `.cursor/rules/*.mdc` define the rules. Worth surfacing:

- **PHP 8.1+ in source**, downgraded to 7.4 for distribution. Don't rely on features newer than 8.1, and don't write code that breaks the downgrade. `declare(strict_types=1);` is mandatory.
- **PSR-1 / PSR-4 / PSR-12**, enforced by PHPCS. PascalCase classes, camelCase methods.
- **Backward compatibility is a hard requirement.**
- **Explicit types everywhere** — return types on every method, type hints on parameters and properties.
- **Never prefix function calls or constants with a leading backslash.** In namespaced files, `use function foo;` and call `foo()`. In global-namespace files (plugin entry files), call bare with **no** `use function` import — that's a no-op there and emits a warning.
- **Extend via WordPress hooks**; never modify core.
- **Changelog** — a non-trivial change gets an entry in all three of `layers/GatoGraphQLForWP/plugins/gatographql/`: `CHANGELOG.md`, `readme.txt`, and the latest version's `docs/release-notes/{version}/en.md`.

## Workflow directives

`.cursor/rules/task-directives.mdc` defines prompt prefixes. They are project conventions, not Claude Code skills — treat them as plain instructions:

- **`TASK: …`** — update `.cursor/scratchpad.md` with a plan, branch `feature/…` or `fix/…` off `master`, work the steps, then add changelog/readme entries.
- **`CHANGELOG: …`** — add the entry to the three files above, using the latest PR number in this repo **+ 1** as the reference, under the latest version.
- **`PHPSTAN`** — run `composer analyse` and fix every error.
