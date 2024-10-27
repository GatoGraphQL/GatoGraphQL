<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\GenericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostUpdateMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader $genericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader(): GenericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader */
            $genericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader = $genericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a custom post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
