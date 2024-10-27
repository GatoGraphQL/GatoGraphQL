<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver(): RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver */
            $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver();
    }
}
