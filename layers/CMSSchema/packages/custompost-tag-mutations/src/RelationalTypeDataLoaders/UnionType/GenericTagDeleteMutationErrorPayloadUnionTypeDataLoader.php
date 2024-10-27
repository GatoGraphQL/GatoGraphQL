<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType\GenericTagDeleteMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericTagDeleteMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericTagDeleteMutationErrorPayloadUnionTypeResolver $genericTagDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericTagDeleteMutationErrorPayloadUnionTypeResolver(): GenericTagDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericTagDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericTagDeleteMutationErrorPayloadUnionTypeResolver */
            $genericTagDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericTagDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->genericTagDeleteMutationErrorPayloadUnionTypeResolver = $genericTagDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericTagDeleteMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericTagDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
