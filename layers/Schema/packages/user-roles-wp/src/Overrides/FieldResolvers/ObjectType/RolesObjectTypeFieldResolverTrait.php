<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;

trait RolesObjectTypeFieldResolverTrait
{
    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        switch ($fieldName) {
            case 'roles':
                return UserRoleObjectTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
