<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostUpdateMetaMutationErrorPayloadUnionTypeResolver $postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostUpdateMetaMutationErrorPayloadUnionTypeResolver(): PostUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver = $postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
