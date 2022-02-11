<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\FieldResolvers\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\UserRoles\Component;
use PoPCMSSchema\UserRoles\ComponentConfiguration;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setUserRoleTypeAPI(UserRoleTypeAPIInterface $userRoleTypeAPI): void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        return $this->userRoleTypeAPI ??= $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
    }

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
        $adminFieldNames = parent::getAdminFieldNames();
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->treatUserRoleAsAdminData()) {
            $adminFieldNames[] = 'roles';
            $adminFieldNames[] = 'hasRole';
            $adminFieldNames[] = 'hasAnyRole';
        }
        if ($componentConfiguration->treatUserCapabilityAsAdminData()) {
            $adminFieldNames[] = 'capabilities';
            $adminFieldNames[] = 'hasCapability';
            $adminFieldNames[] = 'hasAnyCapability';
        }
        return $adminFieldNames;
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'roles' => $this->getStringScalarTypeResolver(),
            'capabilities' => $this->getStringScalarTypeResolver(),
            'hasRole' => $this->getBooleanScalarTypeResolver(),
            'hasAnyRole' => $this->getBooleanScalarTypeResolver(),
            'hasCapability' => $this->getBooleanScalarTypeResolver(),
            'hasAnyCapability' => $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
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
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'roles' => $this->__('User roles', 'user-roles'),
            'capabilities' => $this->__('User capabilities', 'user-roles'),
            'hasRole' => $this->__('Does the user have a specific role?', 'user-roles'),
            'hasAnyRole' => $this->__('Does the user have any role from a provided list?', 'user-roles'),
            'hasCapability' => $this->__('Does the user have a specific capability?', 'user-roles'),
            'hasAnyCapability' => $this->__('Does the user have any capability from a provided list?', 'user-roles'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'hasRole' => [
                'role' => $this->getStringScalarTypeResolver(),
            ],
            'hasAnyRole' => [
                'roles' => $this->getStringScalarTypeResolver(),
            ],
            'hasCapability' => [
                'capability' => $this->getStringScalarTypeResolver(),
            ],
            'hasAnyCapability' => [
                'capabilities' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['hasRole' => 'role'] => $this->__('User role to check against', 'user-roles'),
            ['hasAnyRole' => 'roles'] => $this->__('User roles to check against', 'user-roles'),
            ['hasCapability' => 'capability'] => $this->__('User capability to check against', 'user-roles'),
            ['hasAnyCapability' => 'capabilities'] => $this->__('User capabilities to check against', 'user-roles'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['hasRole' => 'role'],
            ['hasCapability' => 'capability']
                => SchemaTypeModifiers::MANDATORY,
            ['hasAnyRole' => 'roles'],
            ['hasAnyCapability' => 'capabilities']
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY | SchemaTypeModifiers::MANDATORY,
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        array $options = []
    ): mixed {
        $user = $object;
        switch ($fieldName) {
            case 'roles':
                return $this->getUserRoleTypeAPI()->getUserRoles($user);
            case 'capabilities':
                return $this->getUserRoleTypeAPI()->getUserCapabilities($user);
            case 'hasRole':
                $userRoles = $this->getUserRoleTypeAPI()->getUserRoles($user);
                return in_array($fieldArgs['role'], $userRoles);
            case 'hasAnyRole':
                $userRoles = $this->getUserRoleTypeAPI()->getUserRoles($user);
                return !empty(array_intersect($fieldArgs['roles'], $userRoles));
            case 'hasCapability':
                $userCapabilities = $this->getUserRoleTypeAPI()->getUserCapabilities($user);
                return in_array($fieldArgs['capability'], $userCapabilities);
            case 'hasAnyCapability':
                $userCapabilities = $this->getUserRoleTypeAPI()->getUserCapabilities($user);
                return !empty(array_intersect($fieldArgs['capabilities'], $userCapabilities));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
