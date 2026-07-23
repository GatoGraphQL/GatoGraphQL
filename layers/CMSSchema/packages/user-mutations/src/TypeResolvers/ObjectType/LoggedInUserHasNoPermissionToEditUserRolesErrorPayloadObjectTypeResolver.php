<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoPermissionToEditUserRolesErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user does not have permission to edit the user roles"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditUserRolesErrorPayloadObjectTypeDataLoader();
    }
}
