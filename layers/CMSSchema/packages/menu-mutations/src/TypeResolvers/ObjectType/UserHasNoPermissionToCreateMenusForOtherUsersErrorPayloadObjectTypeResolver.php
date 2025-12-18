<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\ObjectType\UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader $userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader = null;

    final protected function getUserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader(): UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader
    {
        if ($this->userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader === null) {
            /** @var UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader */
            $userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(UserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader::class);
            $this->userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader = $userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader;
        }
        return $this->userHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserHasNoPermissionToCreateMenusForOtherUsersErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user has no permission to create menus for other users"', 'menu-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserHasNoPermissionToCreateMenusForOtherUsersErrorPayloadObjectTypeDataLoader();
    }
}
