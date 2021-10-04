<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\UserRolesWP\RelationalTypeDataLoaders\ObjectType\UserRoleTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class UserRoleObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected UserRoleTypeDataLoader $userRoleTypeDataLoader;

    #[Required]
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
        return $this->userRoleTypeDataLoader;
    }
}
