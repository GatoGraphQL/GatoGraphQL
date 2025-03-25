<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType\GenericCategorySetMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCategorySetMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCategorySetMetaMutationErrorPayloadUnionTypeResolver $genericCategorySetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCategorySetMetaMutationErrorPayloadUnionTypeResolver(): GenericCategorySetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategorySetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategorySetMetaMutationErrorPayloadUnionTypeResolver */
            $genericCategorySetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategorySetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategorySetMetaMutationErrorPayloadUnionTypeResolver = $genericCategorySetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategorySetMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCategorySetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
