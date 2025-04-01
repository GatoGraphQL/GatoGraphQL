<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
