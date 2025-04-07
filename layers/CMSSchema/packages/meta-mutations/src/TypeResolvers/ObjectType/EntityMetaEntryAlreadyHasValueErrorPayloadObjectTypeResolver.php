<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader $entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader = null;

    final protected function getEntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader(): EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader
    {
        if ($this->entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader === null) {
            /** @var EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader */
            $entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(EntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader::class);
            $this->entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader = $entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader;
        }
        return $this->entityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'EntityMetaEntryAlreadyHasValueErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The taxonomy term already has the meta entry with the provided update value"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getEntityMetaEntryAlreadyHasValueErrorPayloadObjectTypeDataLoader();
    }
}
