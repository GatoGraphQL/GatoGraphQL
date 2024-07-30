<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver(RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver();
    }
}
