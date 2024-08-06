<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver $genericCategoryDeleteMutationErrorPayloadUnionTypeResolver = null;

    final public function setGenericCategoryDeleteMutationErrorPayloadUnionTypeResolver(GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver $genericCategoryDeleteMutationErrorPayloadUnionTypeResolver): void
    {
        $this->genericCategoryDeleteMutationErrorPayloadUnionTypeResolver = $genericCategoryDeleteMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getGenericCategoryDeleteMutationErrorPayloadUnionTypeResolver(): GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategoryDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver */
            $genericCategoryDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategoryDeleteMutationErrorPayloadUnionTypeResolver = $genericCategoryDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategoryDeleteMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCategoryDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
