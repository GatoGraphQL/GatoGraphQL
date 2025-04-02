<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\ObjectType\UserDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserDoesNotExistErrorPayloadObjectTypeDataLoader $userDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getUserDoesNotExistErrorPayloadObjectTypeDataLoader(): UserDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->userDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var UserDoesNotExistErrorPayloadObjectTypeDataLoader */
            $userDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(UserDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->userDoesNotExistErrorPayloadObjectTypeDataLoader = $userDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->userDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The user does not exist"', 'user-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
