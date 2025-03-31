<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\EntityMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class EntityMetaKeyDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?EntityMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader $termMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getEntityMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader(): EntityMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->termMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var EntityMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader */
            $termMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(EntityMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->termMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader = $termMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->termMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'EntityMetaKeyDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The taxonomy term doesn\'t have a meta entry with that key"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getEntityMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
