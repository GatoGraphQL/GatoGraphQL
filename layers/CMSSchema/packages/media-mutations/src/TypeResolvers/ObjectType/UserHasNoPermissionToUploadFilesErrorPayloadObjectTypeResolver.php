<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeDataLoader $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader = null;

    final public function setUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeDataLoader(UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeDataLoader $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader): void
    {
        $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader = $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader;
    }
    final protected function getUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeDataLoader(): UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeDataLoader
    {
        if ($this->commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader === null) {
            /** @var UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeDataLoader */
            $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(UserHasNoPermissionToUploadFilesErrorPayloadObjectTypeDataLoader::class);
            $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader = $commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader;
        }
        return $this->commentAuthorEmailIsMissingErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserHasNoPermissionToUploadFilesErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user has no permission to upload files"', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserHasNoPermissionToUploadFilesErrorPayloadObjectTypeDataLoader();
    }
}
