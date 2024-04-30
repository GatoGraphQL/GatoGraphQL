<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setGenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver(GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver = $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getGenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver(): GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver */
            $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver = $genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCustomPostUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
