<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
