<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\UserRoles\Facades\UserRoleTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'roles',
            'capabilities',
            'hasRole',
            'hasAnyRole',
            'hasCapability',
            'hasAnyCapability',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'roles',
            'capabilities',
            'hasRole',
            'hasAnyRole',
            'hasCapability',
            'hasAnyCapability',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'roles' => $this->instanceManager->getInstance(StringScalarTypeResolver::class),
            'capabilities' => $this->instanceManager->getInstance(StringScalarTypeResolver::class),
            'hasRole' => $this->instanceManager->getInstance(BooleanScalarTypeResolver::class),
            'hasAnyRole' => $this->instanceManager->getInstance(BooleanScalarTypeResolver::class),
            'hasCapability' => $this->instanceManager->getInstance(BooleanScalarTypeResolver::class),
            'hasAnyCapability' => $this->instanceManager->getInstance(BooleanScalarTypeResolver::class),
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'roles'
                => SchemaTypeModifiers::NON_NULLABLE
                | SchemaTypeModifiers::IS_ARRAY
                | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            'capabilities'
                => SchemaTypeModifiers::NON_NULLABLE
                | SchemaTypeModifiers::IS_ARRAY,
            'hasRole',
            'hasAnyRole',
            'hasCapability',
            'hasAnyCapability'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'roles' => $this->translationAPI->__('User roles', 'user-roles'),
            'capabilities' => $this->translationAPI->__('User capabilities', 'user-roles'),
            'hasRole' => $this->translationAPI->__('Does the user have a specific role?', 'user-roles'),
            'hasAnyRole' => $this->translationAPI->__('Does the user have any role from a provided list?', 'user-roles'),
            'hasCapability' => $this->translationAPI->__('Does the user have a specific capability?', 'user-roles'),
            'hasAnyCapability' => $this->translationAPI->__('Does the user have any capability from a provided list?', 'user-roles'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'hasRole':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'role',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('User role to check against', 'user-roles'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'hasAnyRole':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'roles',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('User roles to check against', 'user-roles'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                            SchemaDefinition::ARGNAME_IS_ARRAY => true,
                            SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY => true,
                        ],
                    ]
                );
            case 'hasCapability':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'capability',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('User capability to check against', 'user-roles'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
            case 'hasAnyCapability':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'capabilities',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('User capabilities to check against', 'user-roles'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                            SchemaDefinition::ARGNAME_IS_ARRAY => true,
                            SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }


    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
        $user = $object;
        switch ($fieldName) {
            case 'roles':
                return $userRoleTypeAPI->getUserRoles($user);
            case 'capabilities':
                return $userRoleTypeAPI->getUserCapabilities($user);
            case 'hasRole':
                $userRoles = $userRoleTypeAPI->getUserRoles($user);
                return in_array($fieldArgs['role'], $userRoles);
            case 'hasAnyRole':
                $userRoles = $userRoleTypeAPI->getUserRoles($user);
                return !empty(array_intersect($fieldArgs['roles'], $userRoles));
            case 'hasCapability':
                $userCapabilities = $userRoleTypeAPI->getUserCapabilities($user);
                return in_array($fieldArgs['capability'], $userCapabilities);
            case 'hasAnyCapability':
                $userCapabilities = $userRoleTypeAPI->getUserCapabilities($user);
                return !empty(array_intersect($fieldArgs['capabilities'], $userCapabilities));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
