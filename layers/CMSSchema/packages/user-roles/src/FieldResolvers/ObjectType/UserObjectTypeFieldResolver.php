<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\FieldResolvers\ObjectType;

use PoPCMSSchema\UserRoles\Module;
use PoPCMSSchema\UserRoles\ModuleConfiguration;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;

class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        if ($this->userRoleTypeAPI === null) {
            /** @var UserRoleTypeAPIInterface */
            $userRoleTypeAPI = $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
            $this->userRoleTypeAPI = $userRoleTypeAPI;
        }
        return $this->userRoleTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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

    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        $sensitiveFieldNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserRoleAsSensitiveData()) {
            $sensitiveFieldNames[] = 'roles';
            $sensitiveFieldNames[] = 'hasRole';
            $sensitiveFieldNames[] = 'hasAnyRole';
        }
        if ($moduleConfiguration->treatUserCapabilityAsSensitiveData()) {
            $sensitiveFieldNames[] = 'capabilities';
            $sensitiveFieldNames[] = 'hasCapability';
            $sensitiveFieldNames[] = 'hasAnyCapability';
        }
        return $sensitiveFieldNames;
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

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $user = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'roles':
                return $this->getUserRoleTypeAPI()->getUserRoles($user);
            case 'capabilities':
                return $this->getUserRoleTypeAPI()->getUserCapabilities($user);
            case 'hasRole':
                $userRoles = $this->getUserRoleTypeAPI()->getUserRoles($user);
                return in_array($fieldDataAccessor->getValue('role'), $userRoles);
            case 'hasAnyRole':
                $userRoles = $this->getUserRoleTypeAPI()->getUserRoles($user);
                return !empty(array_intersect($fieldDataAccessor->getValue('roles'), $userRoles));
            case 'hasCapability':
                $userCapabilities = $this->getUserRoleTypeAPI()->getUserCapabilities($user);
                return in_array($fieldDataAccessor->getValue('capability'), $userCapabilities);
            case 'hasAnyCapability':
                $userCapabilities = $this->getUserRoleTypeAPI()->getUserCapabilities($user);
                return !empty(array_intersect($fieldDataAccessor->getValue('capabilities'), $userCapabilities));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
