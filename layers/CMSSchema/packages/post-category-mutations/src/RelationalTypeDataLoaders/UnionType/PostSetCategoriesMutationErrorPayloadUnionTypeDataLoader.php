<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\PostSetCategoriesMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostSetCategoriesMutationErrorPayloadUnionTypeResolver $postSetCategoriesMutationErrorPayloadUnionTypeResolver = null;

    final public function setPostSetCategoriesMutationErrorPayloadUnionTypeResolver(PostSetCategoriesMutationErrorPayloadUnionTypeResolver $postSetCategoriesMutationErrorPayloadUnionTypeResolver): void
    {
        $this->postSetCategoriesMutationErrorPayloadUnionTypeResolver = $postSetCategoriesMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getPostSetCategoriesMutationErrorPayloadUnionTypeResolver(): PostSetCategoriesMutationErrorPayloadUnionTypeResolver
    {
        /** @var PostSetCategoriesMutationErrorPayloadUnionTypeResolver */
        return $this->postSetCategoriesMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(PostSetCategoriesMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostSetCategoriesMutationErrorPayloadUnionTypeResolver();
    }
}
