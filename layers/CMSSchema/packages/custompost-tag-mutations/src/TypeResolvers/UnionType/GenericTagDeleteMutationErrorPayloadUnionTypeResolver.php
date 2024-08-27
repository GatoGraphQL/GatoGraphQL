<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractTagDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMutations\RelationalTypeDataLoaders\UnionType\GenericTagDeleteMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericTagDeleteMutationErrorPayloadUnionTypeResolver extends AbstractTagDeleteMutationErrorPayloadUnionTypeResolver
{
    private ?GenericTagDeleteMutationErrorPayloadUnionTypeDataLoader $genericTagDeleteMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setGenericTagDeleteMutationErrorPayloadUnionTypeDataLoader(GenericTagDeleteMutationErrorPayloadUnionTypeDataLoader $genericTagDeleteMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->genericTagDeleteMutationErrorPayloadUnionTypeDataLoader = $genericTagDeleteMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getGenericTagDeleteMutationErrorPayloadUnionTypeDataLoader(): GenericTagDeleteMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericTagDeleteMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericTagDeleteMutationErrorPayloadUnionTypeDataLoader */
            $genericTagDeleteMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericTagDeleteMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericTagDeleteMutationErrorPayloadUnionTypeDataLoader = $genericTagDeleteMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericTagDeleteMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericTagDeleteMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericTagDeleteMutationErrorPayloadUnionTypeDataLoader();
    }
}
