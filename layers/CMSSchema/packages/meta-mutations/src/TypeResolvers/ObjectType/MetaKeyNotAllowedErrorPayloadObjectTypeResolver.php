<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\MetaKeyNotAllowedErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class MetaKeyNotAllowedErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?MetaKeyNotAllowedErrorPayloadObjectTypeDataLoader $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = null;

    final protected function getMetaKeyNotAllowedErrorPayloadObjectTypeDataLoader(): MetaKeyNotAllowedErrorPayloadObjectTypeDataLoader
    {
        if ($this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader === null) {
            /** @var MetaKeyNotAllowedErrorPayloadObjectTypeDataLoader */
            $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(MetaKeyNotAllowedErrorPayloadObjectTypeDataLoader::class);
            $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
        }
        return $this->taxonomyTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MetaKeyNotAllowedErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "Access to the meta key is not allowed"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMetaKeyNotAllowedErrorPayloadObjectTypeDataLoader();
    }
}
