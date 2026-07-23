<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToDeleteUserErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user does not have permission to delete the user"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToDeleteUserErrorPayloadObjectTypeDataLoader();
    }
}
