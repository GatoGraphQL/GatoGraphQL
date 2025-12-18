<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\ObjectType\UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader $userHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader = null;

    final protected function getUserHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader(): UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader
    {
        if ($this->userHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader === null) {
            /** @var UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader */
            $userHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(UserHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader::class);
            $this->userHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader = $userHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader;
        }
        return $this->userHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserHasNoPermissionToCreateMenusErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user has no permission to create menus"', 'menu-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserHasNoPermissionToCreateMenusErrorPayloadObjectTypeDataLoader();
    }
}
