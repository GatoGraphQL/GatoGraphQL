<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
