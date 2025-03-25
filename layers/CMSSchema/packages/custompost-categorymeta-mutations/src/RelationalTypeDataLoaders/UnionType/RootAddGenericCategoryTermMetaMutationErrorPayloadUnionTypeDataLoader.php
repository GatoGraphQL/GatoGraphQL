<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
