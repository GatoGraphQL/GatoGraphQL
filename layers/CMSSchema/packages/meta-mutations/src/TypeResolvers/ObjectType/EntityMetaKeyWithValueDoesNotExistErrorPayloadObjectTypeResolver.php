<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader $termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader(): EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader */
            $termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(EntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader = $termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'EntityMetaKeyWithValueDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The taxonomy term doesn\'t have a meta entry with that key"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getEntityMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
