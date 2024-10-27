<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver $rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver(): RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver = $rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdatePostCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
