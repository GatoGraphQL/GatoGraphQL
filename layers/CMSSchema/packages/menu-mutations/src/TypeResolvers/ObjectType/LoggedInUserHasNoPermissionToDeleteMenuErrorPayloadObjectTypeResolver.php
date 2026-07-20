<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToDeleteMenuErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user has no permission to delete the menu"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToDeleteMenuErrorPayloadObjectTypeDataLoader();
    }
}
