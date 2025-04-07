<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader $entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader = null;

    final protected function getEntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader(): EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader
    {
        if ($this->entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader === null) {
            /** @var EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader */
            $entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(EntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader::class);
            $this->entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader = $entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader;
        }
        return $this->entityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'EntityMetaEntryHasSameUpdateValueErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The taxonomy term already has the meta entry with the provided update value"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getEntityMetaEntryHasSameUpdateValueErrorPayloadObjectTypeDataLoader();
    }
}
