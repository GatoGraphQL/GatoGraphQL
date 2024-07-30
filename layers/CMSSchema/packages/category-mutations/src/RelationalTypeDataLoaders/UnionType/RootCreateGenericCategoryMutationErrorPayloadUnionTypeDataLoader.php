<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver(RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver(): RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver */
            $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver();
    }
}
