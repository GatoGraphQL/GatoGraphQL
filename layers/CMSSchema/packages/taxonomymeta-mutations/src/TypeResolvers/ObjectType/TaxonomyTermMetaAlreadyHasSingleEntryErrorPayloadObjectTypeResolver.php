<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\RelationalTypeDataLoaders\ObjectType\TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = null;

    final protected function getTaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader(): TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader
    {
        if ($this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader === null) {
            /** @var TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader */
            $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(TaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader::class);
            $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
        }
        return $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'TaxonomyTermMetaAlreadyHasSingleEntryErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The taxonomy term already has the single meta entry"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTaxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader();
    }
}
