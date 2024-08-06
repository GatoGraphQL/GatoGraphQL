<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver(RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
    }
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
