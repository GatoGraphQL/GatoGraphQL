<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;

trait RolesObjectTypeFieldResolverTrait
{
    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'roles':
                return UserRoleObjectTypeResolver::class;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
