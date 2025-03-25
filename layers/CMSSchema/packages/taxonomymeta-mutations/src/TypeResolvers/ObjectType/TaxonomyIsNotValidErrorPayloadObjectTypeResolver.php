<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\RelationalTypeDataLoaders\ObjectType\TaxonomyIsNotValidErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class TaxonomyIsNotValidErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?TaxonomyIsNotValidErrorPayloadObjectTypeDataLoader $taxonomyIsNotValidErrorPayloadObjectTypeDataLoader = null;

    final protected function getTaxonomyIsNotValidErrorPayloadObjectTypeDataLoader(): TaxonomyIsNotValidErrorPayloadObjectTypeDataLoader
    {
        if ($this->taxonomyIsNotValidErrorPayloadObjectTypeDataLoader === null) {
            /** @var TaxonomyIsNotValidErrorPayloadObjectTypeDataLoader */
            $taxonomyIsNotValidErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(TaxonomyIsNotValidErrorPayloadObjectTypeDataLoader::class);
            $this->taxonomyIsNotValidErrorPayloadObjectTypeDataLoader = $taxonomyIsNotValidErrorPayloadObjectTypeDataLoader;
        }
        return $this->taxonomyIsNotValidErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'TaxonomyIsNotValidErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested taxonomy does not exist"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTaxonomyIsNotValidErrorPayloadObjectTypeDataLoader();
    }
}
