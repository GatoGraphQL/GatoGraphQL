<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\UserRolesWP\TypeResolvers\Object\UserRoleTypeResolver;

trait RolesFieldResolverTrait
{
    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'roles':
                return UserRoleTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
