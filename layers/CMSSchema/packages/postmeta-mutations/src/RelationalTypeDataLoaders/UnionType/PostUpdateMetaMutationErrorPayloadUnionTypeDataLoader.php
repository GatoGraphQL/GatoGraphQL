<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostUpdateMetaMutationErrorPayloadUnionTypeResolver $postUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostUpdateMetaMutationErrorPayloadUnionTypeResolver(): PostUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $postUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postUpdateMetaMutationErrorPayloadUnionTypeResolver = $postUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
