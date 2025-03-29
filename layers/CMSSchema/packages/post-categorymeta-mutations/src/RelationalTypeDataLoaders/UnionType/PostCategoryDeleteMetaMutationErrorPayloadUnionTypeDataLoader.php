<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver $postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver(): PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver = $postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
