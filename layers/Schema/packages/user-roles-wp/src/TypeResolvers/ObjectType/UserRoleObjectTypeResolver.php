<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\UserRolesWP\RelationalTypeDataLoaders\ObjectType\UserRoleTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class UserRoleObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?UserRoleTypeDataLoader $userRoleTypeDataLoader = null;

    public function setUserRoleTypeDataLoader(UserRoleTypeDataLoader $userRoleTypeDataLoader): void
    {
        $this->userRoleTypeDataLoader = $userRoleTypeDataLoader;
    }
    protected function getUserRoleTypeDataLoader(): UserRoleTypeDataLoader
    {
        return $this->userRoleTypeDataLoader ??= $this->instanceManager->getInstance(UserRoleTypeDataLoader::class);
    }

    //#[Required]
    final public function autowireUserRoleObjectTypeResolver(
        UserRoleTypeDataLoader $userRoleTypeDataLoader,
    ): void {
        $this->userRoleTypeDataLoader = $userRoleTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserRole';
    }

    public function getTypeDescription(): ?string
    {
        return $this->translationAPI->__('User roles', 'user-roles');
    }

    public function getID(object $object): string | int | null
    {
        $role = $object;
        return $role->name;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserRoleTypeDataLoader();
    }
}
