<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToDeleteMediaItemErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user has no permission to delete the media item"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToDeleteMediaItemErrorPayloadObjectTypeDataLoader();
    }
}
