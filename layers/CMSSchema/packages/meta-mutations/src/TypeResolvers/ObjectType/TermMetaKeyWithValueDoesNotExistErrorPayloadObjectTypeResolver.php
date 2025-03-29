<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MetaMutations\RelationalTypeDataLoaders\ObjectType\TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader $termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getTermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader(): TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader */
            $termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader = $termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->termMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'TermMetaKeyWithValueDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The taxonomy term doesn\'t have a meta entry with that key"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getTermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
