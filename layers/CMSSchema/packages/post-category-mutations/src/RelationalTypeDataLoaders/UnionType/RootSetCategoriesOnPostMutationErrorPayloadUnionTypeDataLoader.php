<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver(RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver = $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver(): RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver */
            $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver = $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver();
    }
}
