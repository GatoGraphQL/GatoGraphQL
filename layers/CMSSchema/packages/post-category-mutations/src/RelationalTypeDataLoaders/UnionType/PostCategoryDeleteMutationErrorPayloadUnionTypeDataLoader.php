<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\PostCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostCategoryDeleteMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostCategoryDeleteMutationErrorPayloadUnionTypeResolver $postCategoryDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostCategoryDeleteMutationErrorPayloadUnionTypeResolver(): PostCategoryDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCategoryDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostCategoryDeleteMutationErrorPayloadUnionTypeResolver */
            $postCategoryDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostCategoryDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->postCategoryDeleteMutationErrorPayloadUnionTypeResolver = $postCategoryDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCategoryDeleteMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostCategoryDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
