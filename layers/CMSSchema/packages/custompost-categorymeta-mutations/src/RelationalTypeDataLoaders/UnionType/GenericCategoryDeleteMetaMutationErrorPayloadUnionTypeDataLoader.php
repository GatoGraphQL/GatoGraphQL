<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver $genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver(): GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
