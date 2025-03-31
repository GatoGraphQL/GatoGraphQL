<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = null;

    final protected function getEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader(): EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader
    {
        if ($this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader === null) {
            /** @var EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader */
            $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(EntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader::class);
            $this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
        }
        return $this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'EntityMetaAlreadyHasSingleEntryErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The taxonomy term already has the single meta entry"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getEntityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader();
    }
}
