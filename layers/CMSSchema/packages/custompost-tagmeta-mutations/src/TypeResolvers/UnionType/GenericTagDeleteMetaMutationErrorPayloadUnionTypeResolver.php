<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\RelationalTypeDataLoaders\UnionType\GenericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericTagDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractTagDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader $genericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): GenericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $genericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericTagDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
