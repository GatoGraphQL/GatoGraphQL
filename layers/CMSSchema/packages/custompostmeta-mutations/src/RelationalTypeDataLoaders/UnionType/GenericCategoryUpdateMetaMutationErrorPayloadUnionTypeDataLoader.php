<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver $genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver(): GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = $genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
