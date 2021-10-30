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

    final public function setUserRoleTypeDataLoader(UserRoleTypeDataLoader $userRoleTypeDataLoader): void
    {
        $this->userRoleTypeDataLoader = $userRoleTypeDataLoader;
    }
    final protected function getUserRoleTypeDataLoader(): UserRoleTypeDataLoader
    {
        return $this->userRoleTypeDataLoader ??= $this->instanceManager->getInstance(UserRoleTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'UserRole';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('User roles', 'user-roles');
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
