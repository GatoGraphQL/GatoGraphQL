<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostDeleteMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostDeleteMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostDeleteMutationErrorPayloadUnionTypeResolver $postDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostDeleteMutationErrorPayloadUnionTypeResolver(): PostDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostDeleteMutationErrorPayloadUnionTypeResolver */
            $postDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->postDeleteMutationErrorPayloadUnionTypeResolver = $postDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postDeleteMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
