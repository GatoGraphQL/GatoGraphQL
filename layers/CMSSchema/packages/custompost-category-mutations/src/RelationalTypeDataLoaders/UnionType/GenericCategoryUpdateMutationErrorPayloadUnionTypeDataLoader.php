<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCategoryUpdateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver $genericCategoryUpdateMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCategoryUpdateMutationErrorPayloadUnionTypeResolver(): GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategoryUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver */
            $genericCategoryUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategoryUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategoryUpdateMutationErrorPayloadUnionTypeResolver = $genericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategoryUpdateMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCategoryUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
