<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoPSchema\UserRolesWP\TypeResolvers\UserRoleTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractReflectionPropertyFieldResolver;

class UserRoleFieldResolver extends AbstractReflectionPropertyFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(UserRoleTypeResolver::class);
    }

    protected function getTypeClass(): string
    {
        return \WP_Role::class;
    }

    /**
     * Because we can't obtain this data from reflection yet, explicitly define it
     *
     * @see https://github.com/getpop/component-model/issues/1
     */
    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'name' => SchemaDefinition::TYPE_STRING,
            'capabilities' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    /**
     * Because we can't obtain this data from reflection yet, explicitly define it
     *
     * @see https://github.com/getpop/component-model/issues/1
     */
    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'name',
            'capabilities',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    /**
     * Because we can't obtain this data from reflection yet, explicitly define it
     *
     * @see https://github.com/getpop/component-model/issues/1
     */
    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'name' => $this->translationAPI->__('The role name', 'user-roles-wp'),
            'capabilities' => $this->translationAPI->__('Capabilities granted by the role', 'user-roles-wp'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
}
