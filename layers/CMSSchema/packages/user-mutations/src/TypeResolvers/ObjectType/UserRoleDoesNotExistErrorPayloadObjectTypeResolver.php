<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\UserRoleDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserRoleDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserRoleDoesNotExistErrorPayloadObjectTypeDataLoader $userRoleDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getUserRoleDoesNotExistErrorPayloadObjectTypeDataLoader(): UserRoleDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->userRoleDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var UserRoleDoesNotExistErrorPayloadObjectTypeDataLoader */
            $userRoleDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(UserRoleDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->userRoleDoesNotExistErrorPayloadObjectTypeDataLoader = $userRoleDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->userRoleDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserRoleDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user role does not exist"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserRoleDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
