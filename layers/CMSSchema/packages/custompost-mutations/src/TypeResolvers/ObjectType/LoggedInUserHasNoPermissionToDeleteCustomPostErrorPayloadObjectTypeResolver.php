<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToDeleteCustomPostErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to delete the requested custom post"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToDeleteCustomPostErrorPayloadObjectTypeDataLoader();
    }
}
