# CMS Schema

This layer contains packages with definitions concerning data entities: their CMS-agnostic business logic, and their implementation for some specific CMS.

## Type of data entities

The PoP schema is a superset of the GraphQL schema, and it involves the same elements:

- types (such as posts, users, comments, etc)
- fields
- interfaces
- enums
- custom scalars
- directives

In addition, data entities may support extra features. For instance: 

- [Fields are composable](../API#composable-fields) and can be added to every type, enabling to produces [global fields](../API#operators-and-helpers)
- [Directives are composable](../API#composable-directives), so they can modify the behavior of another directive
- [Directives can accept "expressions"](../API#directive-expressions), which are dynamic variables created on runtime, during the query resolution

## CMS-agnosticism

The `Schema` layer is CMS-agnostic, so the same data entity can work on any CMS. It involves packages with the contracts, and also with the implementation of the contracts for some specific CMS. For instance, all packages ending in `-wp` provide an implementation for WordPress.

In addition, this layer is application-agnostic, independent from both the `API` and `SiteBuilder` layers: a data entity has a single source of truth of code, which can interact with any API (whether it is REST or GraphQL or any other one) or with the engine when rendering the website.

### Package architecture convention

The code for some component is divided into 2 separate packages:

- A CMS-agnostic package: it contains the business code and generic contracts, but no CMS-specific code (eg: [`posts`](packages/posts) package)
- A CMS-specific package, containing the implementation of the contracts (eg: [`posts-wp`](packages/posts-wp), implementing contracts for WordPress)

## Overview: Defining type and field resolvers

The schema is created following the [code-first approach](https://graphql-by-pop.com/docs/architecture/code-first.html). Fields are dynamically "subscribed" to types, and may or may not be added to the schema depending on the context.

This is how a `User` type is satisfied:

```php
class UserObjectTypeResolver extends AbstractObjectTypeResolver
{
  public function getTypeName(): string
  {
    return 'User';
  }

  public function getTypeDescription(): ?string
  {
    return $this->getTranslationAPI()->__('Representation of a user', 'users');
  }

  public function getID(object $user)
  {
    return $this->userService->getUserID($user);
  }

  public function getRelationalTypeDataLoader(): string
  {
    return $this->instanceManager->getInstance(UserTypeDataLoader::class);
  }
}
```

Please notice how the `TypeResolver` does not indicate which are its fields. It also does not load the objects from the database, but instead delegates this task to a `TypeDataLoader`. And it doesn't know how to retrieve the ID for the user; that will be done for a CMS-specific service.

Adding fields to the type is done via a `ObjectTypeFieldResolver`:

```php
class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
  public function getClassesToAttachTo(): array
  {
    return [
      UserObjectTypeResolver::class,
    ];
  }

  public function getFieldNamesToResolve(): array
  {
    return [
      'username',
      'email',
      'url',
    ];
  }

  public function getFieldDescription(
    RelationalTypeResolverInterface $relationalTypeResolver,
    string $fieldName
  ): ?string {
    $descriptions = [
      'username' => $this->getTranslationAPI()->__("User's username handle", "users"),
      'email' => $this->getTranslationAPI()->__("User's email", "users"),
      'url' => $this->getTranslationAPI()->__("URL of the user's profile in the website", "users"),
    ];
    return $descriptions[$fieldName];
  }

  public function getSchemaFieldType(
    RelationalTypeResolverInterface $relationalTypeResolver,
    string $fieldName
  ): ?string {
    $types = [
      'username' => SchemaDefinition::TYPE_STRING,
      'email' => SchemaDefinition::TYPE_EMAIL,
      'url' => SchemaDefinition::TYPE_URL,
    ];
    return $types[$fieldName];
  }

  public function resolveValue(
    RelationalTypeResolverInterface $relationalTypeResolver,
    object $user,
    string $fieldName,
    array $fieldArgs = []
  ) {
    switch ($fieldName) {
      case 'username':
        return $this->userService->getUserLogin($user);

      case 'email':
        return $this->userService->getUserEmail($user);

      case 'url':
        return $this->userService->getUserProfileURL($user);
    }

    return null;
  }
}
```

The definition of a field for the schema, and its resolution, is split into a multitude of functions from the `ObjectTypeFieldResolver`: 

- `getFieldDescription`
- `getSchemaFieldType`
- `resolveValue`
- `getSchemaFieldArgs`
- `isSchemaFieldResponseNonNullable`
- `getImplementedInterfaceTypeFieldResolverClasses`
- `getFieldTypeResolverClass`
- `getFieldMutationResolverClass`

Finally, the functions from the `UserService` must be resolved for the particular CMS. This is how it's done for WordPress:

```php
class UserService implements UserServiceInterface
{
  public function getUserID($user)
  {
    return $user->ID;
  }
  public function getUserLogin($user)
  {
    return $user->user_login;
  }
  public function getUserEmail($user)
  {
    return $user->user_email;
  }
  public function getUserProfileURL($user)
  {
    return $user->user_url;
  }
}
```

This code is more legible than if all functionality is satisfied through a single function, or through a configuration array, making it easier to implement and maintain the resolvers.
