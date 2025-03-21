# Gato GraphQL AI Development Guidelines

## Project Overview

Gato GraphQL is a WordPress plugin that provides a GraphQL server for WordPress. It allows to query, transform, and mutate any piece of data from the WordPress site, via GraphQL queries. It supports to integrate any 3rd-party WordPress plugin, adding fields and mutations to the GraphQL schema to query its data.

## Repo Organization

### Monorepo

- Monorepo hosting multiple WordPress plugins:
  - Gato GraphQL
  - Gato GraphQL integrations
  - Testing utilities
- Each plugin is composed of Composer packages, hosted in the same monorepo

### Monorepo layers

- All plugins/packages are grouped into "layers"
- Dependency graph across layers: `assets/img/dependency-graph.svg`

### Monorepo folders

- Plugins: `layers/{layer_name}/plugins/{plugin_name}`
- Plugin packages: `layers/{layer_name}/packages/{package_name}`
- Testing plugins: `layers/{layer_name}/phpunit-plugins/{plugin_name}`
- Testing plugin packages: `layers/{layer_name}/phpunit-packages/{package_name}`

### Development helper files

- Integrations stubs: `stubs/wpackagist-plugin/{integration-plugin}/stubs.php`

### Configuration files

- Configuration to generate plugins: `src/Config/Symplify/MonorepoBuilder/DataSources/PluginDataSource.php`
- Configuration to generate root `composer.json`: `src/Config/Symplify/MonorepoBuilder/DataSources/DataToAppendAndRemoveDataSource.php`
- VSCode mappings: `.vscode/launch.json`
- Lando webserver config to override volumes (point to local source): `webservers/gatographql/.lando.upstream.yml`

#### Rector

- Downgrade source code
  - Config file: `config/rector/plugins/{integration-plugin}/`
  - Source code: `src/Config/Rector/Downgrade/Configurators/Plugins/{Integration-plugin}ContainerConfigurationService.php`

### Gato GraphQL plugin

- Folder: `layers/GatoGraphQLForWP/plugins/gatographql/`
- Main file: `gatographql.php`

## Development Standards

### Plugin and Package Organization

- Source code: `src/`
- Main file: `Module.php`
- Unit Tests: `tests/Unit/`
- Symfony DependencyInjection configuration: `config/`
  - GraphQL schema services: `schema-services.yaml`
  - All other services: `services.yaml`
- Conditional logic (if another package is installed): `src/ConditionalOnModule/{depended_package_name}`

#### Plugin-specific Organization

- Main file: `gatographql-{plugin_name}.php`
- Integration Tests: `tests/Integration/`
- Module Resolvers: `src/ModuleResolvers/`
- Symfony DependencyInjection configuration for Module services: `config/module-services.yaml`
- Module documentation: `docs/modules/`

#### Package-specific Organization

##### GraphQL schema resolvers

- Object Type Resolvers: `src/TypeResolvers/ObjectType/`
- Custom Scalar Resolvers: `src/TypeResolvers/ScalarType/`
- Enum Resolvers: `src/TypeResolvers/EnumType/`
- Interface Resolvers: `src/TypeResolvers/InterfaceType/`
- Input Object Resolvers: `src/TypeResolvers/InputObjectType/`
- Union Type Resolvers: `src/TypeResolvers/UnionType/`
- Resolvers to add Object Types to Union Types: `src/ObjectTypeResolverPickers/`
- Object Field Resolvers: `src/FieldResolvers/ObjectType/`
- Interface Field Resolvers: `src/FieldResolvers/InterfaceType/`
- Mutation Resolvers: `src/MutationResolvers/`
- DataLoader Resolvers: `src/RelationalTypeDataLoaders/`
- Directive Resolvers: `src/DirectiveResolvers/`

### Tests

- Based on fixtures, containing:
  - A GraphQL query to execute against the GraphQL server
  - A dictionary of GraphQL variables, as a `.var.json` file (Optional)
  - The expected response in JSON

### Coding Standards

- Follow Coding Standards as defined in `.cursor/rules/php-wordpress.mdc`
- Dependency injection via Symfony DependencyInjection
- PHPStan for static analysis
- PHPCS for code style enforcement
- Use type hints and PHPDoc blocks for all new code
- Maintain PHPStan baseline in `phpstan-baseline.neon`
- Maintain PHPCS baseline in `phpcs.baseline.xml`

### Version Control

- Branch naming: `feature/`, `fix/`, `enhancement/` prefixes
- Commit messages should reference ticket numbers when applicable
- Keep commits atomic and focused

### Testing

- PHPUnit for unit testing
- PHPUnit with Lando for integration tests
- Test new plugin integrations thoroughly
- Test against supported WordPress versions (latest - 2)
- Test against supported WooCommerce versions when applicable

## AI Assistant Guidelines

### General Approach

1. Always check existing integration patterns before creating new ones
2. Prioritize maintaining backward compatibility
3. Follow established hook naming conventions
4. Use existing helper functions and utilities
5. Reference services from the container

### Code Generation

When asking AI to generate code:

1. **Interacting with Services**

```php
// Define the service in the class
private ?{ServiceInterface} ${service} = null;

final protected function get{Service}(): {ServiceInterface}
{
   if ($this->{service} === null) {
      /** @var {ServiceInterface} */
      ${service} = $this->instanceManager->getInstance({ServiceInterface}::class);
      $this->{service} = ${service};
   }
   return $this->{service};
}

// Use service
${service} = $this->get{Service}();
${service}->doSomething();
```

2. **Creating schema-services.yaml**

```yaml
services:
    _defaults:
        public: true
        autowire: true

    # Service definitions...

```

3. **Creating services.yaml**

```yaml
services:
    _defaults:
        public: true
        autowire: true
        autoconfigure: true

    # Service definitions...

```

### Common Tasks

#### Creating a package in Gato GraphQL

1. Plan and propose: Given the requirements, check for similar packages already included in Gato GraphQL
2. Create folders, files, and code for the packages
3. Implement the logic
4. Execute `composer update-monorepo` to regenerate `composer.json`
5. Add the new packages to the VSCode mapping
6. Add the new packages to the Lando webserver config

#### Creating a module in Gato GraphQL

1. Follow all the steps from common task "Creating a package in Gato GraphQL"
2. Add a new module for Gato GraphQL
3. Add the module documentation
4. Create integration tests
5. Execute `composer init-server` to start the Lando webserver

#### Creating an Integration with a 3rd-party Plugin

1. Plan and propose: Given the requirements, check for a similar plugin in the monorepo, and propose how to create the new integration, and how it will extend the GraphQL schema
2. Create folders, files, and code for plugin and packages for the integration
3. Execute `composer require --dev "wpackagist-plugin/{plugin}"` to add the 3rd-party plugin in `composer.json`
4. Execute `composer update-monorepo` to regenerate `composer.json`
5. Add the new plugin and packages to the VSCode mapping
6. Add the new plugin and packages to the Lando webserver config
7. Create the Rector config files to downgrade the plugin
8. Update the configuration in `PluginDataSource` to generate the new plugin
9. Update the configuration in `DataToAppendAndRemoveDataSource` to remove the 3rd-party plugin from `require-dev`
10. Implement the logic to extend the GraphQL schema for the integration
11. Add a new module for Gato GraphQL
12. Add the module documentation
13. Create integration tests
14. Execute `composer init-server` to start the Lando webserver

### Security Practices

- Sanitize all inputs
- Validate API responses
- Use nonces for admin actions
- Follow WordPress security best practices
- Never store sensitive data unencrypted

## Documentation Standards

### Code Comments

- Use PHPDoc blocks for classes and methods
- Document filter/action hooks
- Explain complex logic
- Mark deprecated functions

### Commit Messages

```
type(scope): description

[optional body]

[optional footer]
```

### Pull Request Template

- Description of changes
- Testing instructions
- Screenshots if UI changes
- Related issues/tickets

## AI Development Workflow

1. **Analysis Phase**
   - Review existing code patterns
   - Check integration examples
   - Verify dependencies
   - Initialize/update `.cursor/scratchpad.md` with task details

2. **Scratchpad Management**
   - Location: Always use `.cursor/scratchpad.md`
   - Format:
     ```
     current_task: "Task description"
     status: in_progress|complete|blocked
     steps:
     [ ] Step 1
     [X] Completed step
     reflections:
     - Key learnings
     - Potential improvements
     - Technical considerations
     ```
   - Update status and steps as work progresses
   - Document blockers and solutions
   - Track important decisions

3. **Implementation Phase**
   - Generate code following standards
   - Add proper error handling
   - Include logging statements
   - Add type hints and documentation

4. **Review Phase**
   - Code style compliance
   - Security review
   - Documentation completeness
   - Performance optimization

## Troubleshooting Guide for AI

1. **API Issues**
   - Check API credentials
   - Verify endpoint URLs
   - Review rate limits
   - Check for SSL issues

2. **Integration Problems**
   - Verify plugin compatibility
   - Check hook priorities
   - Review field mappings
   - Check for conflicts

3. **Performance Issues**
   - Review query optimization
   - Check caching implementation
   - Verify async operations
   - Monitor memory usage

## Resources

### Internal

- `readme.txt` - Plugin documentation

### External

- Gato GraphQL Documentation:
  - [Gato GraphQL Guides](https://gatographql.com/guides)
  - [Gato GraphQL Extensions reference](https://gatographql.com/extensions-reference)
  - [Gato GraphQL Queries library](https://gatographql.com/library)
  - [Gato GraphQL Schema tutorial](https://gatographql.com/tutorial)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [WordPress Documentation Standards](https://developer.wordpress.org/coding-standards/inline-documentation-standards/php/)
