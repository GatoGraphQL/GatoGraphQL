<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToEditUser = null;

    final protected function getLoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToEditUser === null) {
            /** @var LoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToEditUser = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToEditUser = $loggedInUserHasNoPermissionToEditUser;
        }
        return $this->loggedInUserHasNoPermissionToEditUser;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToEditUserErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit the user"', 'user-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditUserErrorPayloadObjectTypeDataLoader();
    }
}
