<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\TypeResolvers;

use PoPSchema\UserRolesWP\TypeDataLoaders\UserRoleTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class UserRoleTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserRole';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('User roles', 'user-roles');
    }

    public function getID(object $resultItem): string | int
    {
        $role = $resultItem;
        return $role->name;
    }

    public function getTypeDataLoaderClass(): string
    {
        return UserRoleTypeDataLoader::class;
    }
}
