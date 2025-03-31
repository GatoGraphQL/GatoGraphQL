<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostUpdateMetaMutationErrorPayloadUnionTypeResolver $postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostUpdateMetaMutationErrorPayloadUnionTypeResolver(): PostUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver = $postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
