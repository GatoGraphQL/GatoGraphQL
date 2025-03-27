<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = null;

    final protected function getTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader(): TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader
    {
        if ($this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader === null) {
            /** @var TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader */
            $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader::class);
            $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
        }
        return $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'TermMetaAlreadyHasSingleEntryErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The taxonomy term already has the single meta entry"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader();
    }
}
