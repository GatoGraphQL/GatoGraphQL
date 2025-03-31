<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\RelationalTypeDataLoaders\UnionType\GenericTagSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericTagSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractTagSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericTagSetMetaMutationErrorPayloadUnionTypeDataLoader $genericTagSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericTagSetMetaMutationErrorPayloadUnionTypeDataLoader(): GenericTagSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericTagSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericTagSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericTagSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericTagSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericTagSetMetaMutationErrorPayloadUnionTypeDataLoader = $genericTagSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericTagSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericTagSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericTagSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
