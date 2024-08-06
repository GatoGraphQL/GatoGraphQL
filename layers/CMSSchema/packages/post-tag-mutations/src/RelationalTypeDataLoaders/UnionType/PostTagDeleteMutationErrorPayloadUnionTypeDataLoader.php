<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostTagDeleteMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostTagDeleteMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostTagDeleteMutationErrorPayloadUnionTypeResolver $postTagDeleteMutationErrorPayloadUnionTypeResolver = null;

    final public function setPostTagDeleteMutationErrorPayloadUnionTypeResolver(PostTagDeleteMutationErrorPayloadUnionTypeResolver $postTagDeleteMutationErrorPayloadUnionTypeResolver): void
    {
        $this->postTagDeleteMutationErrorPayloadUnionTypeResolver = $postTagDeleteMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getPostTagDeleteMutationErrorPayloadUnionTypeResolver(): PostTagDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postTagDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostTagDeleteMutationErrorPayloadUnionTypeResolver */
            $postTagDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostTagDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->postTagDeleteMutationErrorPayloadUnionTypeResolver = $postTagDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postTagDeleteMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostTagDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
