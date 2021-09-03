<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\UserRolesWP\TypeResolvers\UserRoleTypeResolver;

trait RolesFieldResolverTrait
{
    public function getSchemaFieldType(RelationalTypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'roles' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'roles':
                return UserRoleTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
