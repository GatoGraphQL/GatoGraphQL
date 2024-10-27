<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader $userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader = null;

    final protected function getUserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader(): UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader
    {
        if ($this->userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader === null) {
            /** @var UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader */
            $userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(UserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader::class);
            $this->userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader = $userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader;
        }
        return $this->userHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserHasNoPermissionToUploadFilesForOtherUsersErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user has no permission to upload files for other users"', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserHasNoPermissionToUploadFilesForOtherUsersErrorPayloadObjectTypeDataLoader();
    }
}
