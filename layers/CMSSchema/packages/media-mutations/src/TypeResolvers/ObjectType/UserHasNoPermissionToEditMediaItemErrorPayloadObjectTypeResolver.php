<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader = null;

    final public function setUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader(UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader): void
    {
        $this->userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader = $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader;
    }
    final protected function getUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader(): UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader
    {
        if ($this->userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader === null) {
            /** @var UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader */
            $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(UserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader::class);
            $this->userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader = $userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader;
        }
        return $this->userHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserHasNoPermissionToEditMediaItemErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user has no permission to upload files for other users"', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserHasNoPermissionToEditMediaItemErrorPayloadObjectTypeDataLoader();
    }
}
