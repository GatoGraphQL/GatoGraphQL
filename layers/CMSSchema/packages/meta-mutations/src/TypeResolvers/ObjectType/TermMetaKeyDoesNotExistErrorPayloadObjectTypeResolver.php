<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\TermMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class TermMetaKeyDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?TermMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = null;

    final protected function getTermMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader(): TermMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader === null) {
            /** @var TermMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader */
            $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(TermMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader = $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
        }
        return $this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'TermMetaKeyDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The taxonomy term doesn\'t have a meta entry with that key"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTermMetaKeyDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
