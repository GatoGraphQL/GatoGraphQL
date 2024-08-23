<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType\RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver(RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver = $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver(): RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver = $rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetCategoriesOnCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
