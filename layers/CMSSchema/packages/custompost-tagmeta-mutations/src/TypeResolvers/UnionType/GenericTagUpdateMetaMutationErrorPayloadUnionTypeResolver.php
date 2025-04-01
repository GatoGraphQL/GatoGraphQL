<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\RelationalTypeDataLoaders\UnionType\GenericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericTagUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractTagUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader $genericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): GenericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $genericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericTagUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
