<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\RootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdatePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
