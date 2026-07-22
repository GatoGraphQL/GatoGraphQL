<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToCreateUsersErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user does not have permission to create users"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToCreateUsersErrorPayloadObjectTypeDataLoader();
    }
}
