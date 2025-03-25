<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
