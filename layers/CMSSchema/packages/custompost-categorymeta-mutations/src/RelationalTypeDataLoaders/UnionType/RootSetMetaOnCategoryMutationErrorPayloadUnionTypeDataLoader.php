<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver $rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver(): RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver */
            $rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver = $rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver();
    }
}
