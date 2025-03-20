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

### Monorepo files

- Integrations stubs: `stubs/wpackagist-plugin/{integration-plugin}/stubs.php`

### Configuration files

- Configuration to generate plugins: `src/Config/Symplify/MonorepoBuilder/DataSources/PluginDataSource.php`
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
  - Module services: `module-services.yaml`
  - All other services: `services.yaml`
- Conditional logic (if another package is installed): `src/ConditionalOnModule/{depended_package_name}`

#### Plugin-specific Organization

- Main file: `gatographql-{plugin_name}.php`
- Module Resolvers: `src/ModuleResolvers/`
- Integration Tests: `tests/Integration/`

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
  - A GraphQL query to execute againt the GraphQL server
  - A dictionary of GraphQL variables, as a .var.json file(Optional)
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

## Extending the GraphQL Schema - Source code examples

### GraphQL spec elements

#### Type Resolver

- `User` type: `layers/CMSSchema/packages/users/src/TypeResolvers/ObjectType/UserObjectTypeResolver.php`

#### Field Resolver

- Fields `name`, `displayName`, and others, for the `User` type: `layers/CMSSchema/packages/users/src/FieldResolvers/ObjectType/UserObjectTypeFieldResolver.php`

#### Mutation Resolver

- Mutation `createPost`: `layers/CMSSchema/packages/post-mutations/src/FieldResolvers/ObjectType/RootObjectTypeFieldResolver.php`
- Mutation resolvers for `createPost`:
  - `layers/CMSSchema/packages/post-mutations/src/MutationResolvers/CreatePostMutationResolver.php`
  - `layers/CMSSchema/packages/post-mutations/src/MutationResolvers/PayloadableCreatePostMutationResolver.php`

##### Bulk Mutation Resolver

- Bulk mutation `createPosts`: `layers/CMSSchema/packages/post-mutations/src/FieldResolvers/ObjectType/RootObjectTypeFieldResolver.php`
- Bulk mutation resolvers for `createPosts`:
  - `layers/CMSSchema/packages/post-mutations/src/MutationResolvers/CreatePostBulkOperationMutationResolver.php`
  - `layers/CMSSchema/packages/post-mutations/src/MutationResolvers/PayloadableCreatePostBulkOperationMutationResolver.php`

#### Custom Scalar Resolver

- Custom scalar `Email`: `layers/Schema/packages/schema-commons/src/TypeResolvers/ScalarType/EmailScalarTypeResolver.php`

#### Enum Resolver

- Enum `CommentTypeEnum`: `layers/CMSSchema/packages/comments/src/TypeResolvers/EnumType/CommentTypeEnumTypeResolver.php`

#### Interface Resolver

- Interface `CustomPostInterfaceTypeResolver`: `layers/CMSSchema/packages/customposts/src/TypeResolvers/InterfaceType/CustomPostInterfaceTypeResolver.php`

#### Union Type-Resolver

- Union type `CustomPostUnionTypeResolver`: `layers/CMSSchema/packages/customposts/src/TypeResolvers/UnionType/CustomPostUnionTypeResolver.php`

### Resolvers to add Object Types to Union Types

- Adding `GenericCustomPost` to `CustomPostUnion`: `layers/CMSSchema/packages/customposts/src/ObjectTypeResolverPickers/CustomPostUnionGenericCustomPostObjectTypeResolverPicker.php`

#### Input Object Resolver

- Input Object `UserSortInput`: `layers/CMSSchema/packages/users/src/TypeResolvers/InputObjectType/UserSortInputObjectTypeResolver.php`

#### Oneof Input Object Resolver

- Oneof Input Object `UserByInput`: `layers/CMSSchema/packages/users/src/TypeResolvers/InputObjectType/UserByOneofInputObjectTypeResolver.php`

#### Object DataLoader Resolver

- Resolving `User` objects: `layers/CMSSchema/packages/users/src/RelationalTypeDataLoaders/ObjectType/UserObjectTypeDataLoader.php`

#### Union DataLoader Resolver

- Resolving `CustomPost` unions: `layers/CMSSchema/packages/customposts/src/RelationalTypeDataLoaders/ObjectType/CustomPostObjectTypeDataLoader.php`

#### Directive Resolver

- Directive `@skip`: `layers/Engine/packages/engine/src/DirectiveResolvers/SkipFieldDirectiveResolver.php`

### Custom Gato GraphQL elements

#### Global Field Resolver

- Field `__typename`: `layers/GraphQLByPoP/packages/graphql-server/src/FieldResolvers/ObjectType/GlobalObjectTypeFieldResolver.php`

#### Enum String Resolver

- Enum String `CustomPostEnumString` `layers/CMSSchema/packages/customposts/src/TypeResolvers/EnumType/CustomPostEnumStringScalarTypeResolver.php`

#### Error Payload Union Type Resolver

- Error Payload Union Type `RootAddCommentToCustomPostMutationErrorPayloadUnion`: `layers/CMSSchema/packages/comment-mutations/src/TypeResolvers/UnionType/RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver.php`

#### Nested Mutations Resolver

- Mutation `Post.update`: `layers/CMSSchema/packages/post-mutations/src/FieldResolvers/ObjectType/PostObjectTypeFieldResolver.php`

#### Field Arguments to filter data

- Field `Root.comments`: `layers/CMSSchema/packages/comments/src/FieldResolvers/ObjectType/RootObjectTypeFieldResolver.php`

#### Custom Post Type - Type Resolver

- Custom Post Type `Page`: `layers/CMSSchema/packages/pages/src/TypeResolvers/ObjectType/PageObjectTypeResolver.php`

#### Custom Post Type - Field Resolver

- Field `CustomPost.author`: `layers/CMSSchema/packages/users/src/ConditionalOnModule/CustomPosts/FieldResolvers/ObjectType/CustomPostObjectTypeFieldResolver.php`

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

### Common Tasks

#### Creating Integrations

1. Check for similar plugins
2. Check packages used by those plugins
3. Replicate folders/files and adapt as needed

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
  - [Gato GraphQL Architecture](https://gatographql.com/architecture)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [WordPress Documentation Standards](https://developer.wordpress.org/coding-standards/inline-documentation-standards/php/)
