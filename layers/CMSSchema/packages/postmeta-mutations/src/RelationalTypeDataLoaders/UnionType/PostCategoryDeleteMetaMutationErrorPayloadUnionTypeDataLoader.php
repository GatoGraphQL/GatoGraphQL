<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostDeleteMetaMutationErrorPayloadUnionTypeResolver $postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostDeleteMetaMutationErrorPayloadUnionTypeResolver(): PostDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
