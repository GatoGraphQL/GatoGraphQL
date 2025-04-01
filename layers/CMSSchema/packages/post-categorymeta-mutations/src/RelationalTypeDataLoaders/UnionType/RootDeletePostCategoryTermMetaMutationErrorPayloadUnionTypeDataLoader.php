<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
