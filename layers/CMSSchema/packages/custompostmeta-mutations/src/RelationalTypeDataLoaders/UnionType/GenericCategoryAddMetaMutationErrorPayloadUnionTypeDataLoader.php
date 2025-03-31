<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver $genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver(): GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver */
            $genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver = $genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCategoryAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
