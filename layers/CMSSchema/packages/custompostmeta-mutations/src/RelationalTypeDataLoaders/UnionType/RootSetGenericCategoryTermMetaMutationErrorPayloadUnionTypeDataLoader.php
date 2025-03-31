<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver $rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver(): RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver = $rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
