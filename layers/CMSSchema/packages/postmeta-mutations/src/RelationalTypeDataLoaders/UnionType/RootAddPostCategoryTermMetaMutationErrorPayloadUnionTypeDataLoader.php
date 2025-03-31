<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
