<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\UserRolesWP\RelationalTypeDataLoaders\ObjectType\UserRoleObjectTypeDataLoader;
use WP_Role;

class UserRoleObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?UserRoleObjectTypeDataLoader $userRoleObjectTypeDataLoader = null;

    final protected function getUserRoleObjectTypeDataLoader(): UserRoleObjectTypeDataLoader
    {
        if ($this->userRoleObjectTypeDataLoader === null) {
            /** @var UserRoleObjectTypeDataLoader */
            $userRoleObjectTypeDataLoader = $this->instanceManager->getInstance(UserRoleObjectTypeDataLoader::class);
            $this->userRoleObjectTypeDataLoader = $userRoleObjectTypeDataLoader;
        }
        return $this->userRoleObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserRole';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('User roles', 'user-roles');
    }

    public function getID(object $object): string|int|null
    {
        /** @var WP_Role */
        $role = $object;
        return $role->name;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserRoleObjectTypeDataLoader();
    }
}
