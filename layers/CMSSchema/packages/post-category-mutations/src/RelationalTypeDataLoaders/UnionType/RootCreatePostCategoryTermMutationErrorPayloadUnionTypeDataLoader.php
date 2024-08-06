<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver(RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver = $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver(): RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver = $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
