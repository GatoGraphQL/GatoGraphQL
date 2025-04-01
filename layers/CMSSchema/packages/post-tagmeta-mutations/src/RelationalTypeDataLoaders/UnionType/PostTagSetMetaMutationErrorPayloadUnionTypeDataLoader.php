<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\PostTagSetMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostTagSetMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostTagSetMetaMutationErrorPayloadUnionTypeResolver $postTagSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostTagSetMetaMutationErrorPayloadUnionTypeResolver(): PostTagSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postTagSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostTagSetMetaMutationErrorPayloadUnionTypeResolver */
            $postTagSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostTagSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postTagSetMetaMutationErrorPayloadUnionTypeResolver = $postTagSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postTagSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostTagSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
