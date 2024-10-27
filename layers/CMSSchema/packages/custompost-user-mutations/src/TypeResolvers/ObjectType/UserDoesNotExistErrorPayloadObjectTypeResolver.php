<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostUserMutations\RelationalTypeDataLoaders\ObjectType\UserDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?UserDoesNotExistErrorPayloadObjectTypeDataLoader $customPostDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getUserDoesNotExistErrorPayloadObjectTypeDataLoader(): UserDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var UserDoesNotExistErrorPayloadObjectTypeDataLoader */
            $customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(UserDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested media item does not exist"', 'custompost-user-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
