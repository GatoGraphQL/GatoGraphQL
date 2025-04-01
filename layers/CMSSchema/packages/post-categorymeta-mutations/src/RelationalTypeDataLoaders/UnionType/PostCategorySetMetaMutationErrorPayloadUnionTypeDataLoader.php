<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType\PostCategorySetMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostCategorySetMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostCategorySetMetaMutationErrorPayloadUnionTypeResolver $postCategorySetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostCategorySetMetaMutationErrorPayloadUnionTypeResolver(): PostCategorySetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategorySetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostCategorySetMetaMutationErrorPayloadUnionTypeResolver */
            $postCategorySetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostCategorySetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategorySetMetaMutationErrorPayloadUnionTypeResolver = $postCategorySetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategorySetMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostCategorySetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
