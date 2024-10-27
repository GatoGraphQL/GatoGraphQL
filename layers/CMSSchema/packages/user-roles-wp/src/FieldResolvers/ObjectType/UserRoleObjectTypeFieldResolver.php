<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesWP\FieldResolvers\ObjectType;

use PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;
use PoPCMSSchema\UserRoles\Module;
use PoPCMSSchema\UserRoles\ModuleConfiguration;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractReflectionPropertyObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use WP_Role;

class UserRoleObjectTypeFieldResolver extends AbstractReflectionPropertyObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserRoleObjectTypeResolver::class,
        ];
    }

    protected function getTypeClass(): string
    {
        return WP_Role::class;
    }

    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        $sensitiveFieldNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserCapabilityAsSensitiveData()) {
            $sensitiveFieldNames[] = 'capabilities';
        }
        return $sensitiveFieldNames;
    }

    /**
     * Because we can't obtain this data from reflection yet, explicitly define it
     *
     * @see https://github.com/getpop/component-model/issues/1
     */
    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name' => $this->getStringScalarTypeResolver(),
            'capabilities' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'name' => SchemaTypeModifiers::NON_NULLABLE,
            'capabilities' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * Because we can't obtain this data from reflection yet, explicitly define it
     *
     * @see https://github.com/getpop/component-model/issues/1
     */
    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('The role name', 'user-roles-wp'),
            'capabilities' => $this->__('Capabilities granted by the role', 'user-roles-wp'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'capabilities':
                /** @var WP_Role */
                $userRole = $object;
                $capabilities = array_keys(array_filter($userRole->capabilities));
                sort($capabilities);
                return $capabilities;
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
