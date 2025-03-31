<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
