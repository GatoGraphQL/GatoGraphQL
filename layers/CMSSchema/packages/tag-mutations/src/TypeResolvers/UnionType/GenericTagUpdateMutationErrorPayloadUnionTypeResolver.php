<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractTagUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMutations\RelationalTypeDataLoaders\UnionType\GenericTagUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericTagUpdateMutationErrorPayloadUnionTypeResolver extends AbstractTagUpdateMutationErrorPayloadUnionTypeResolver
{
    private ?GenericTagUpdateMutationErrorPayloadUnionTypeDataLoader $genericTagUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setGenericTagUpdateMutationErrorPayloadUnionTypeDataLoader(GenericTagUpdateMutationErrorPayloadUnionTypeDataLoader $genericTagUpdateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->genericTagUpdateMutationErrorPayloadUnionTypeDataLoader = $genericTagUpdateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getGenericTagUpdateMutationErrorPayloadUnionTypeDataLoader(): GenericTagUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericTagUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericTagUpdateMutationErrorPayloadUnionTypeDataLoader */
            $genericTagUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericTagUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericTagUpdateMutationErrorPayloadUnionTypeDataLoader = $genericTagUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericTagUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericTagUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericTagUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
