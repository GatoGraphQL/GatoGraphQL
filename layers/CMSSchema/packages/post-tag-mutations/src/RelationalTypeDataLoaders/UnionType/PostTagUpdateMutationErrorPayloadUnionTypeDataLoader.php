<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostTagUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostTagUpdateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostTagUpdateMutationErrorPayloadUnionTypeResolver $postTagUpdateMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostTagUpdateMutationErrorPayloadUnionTypeResolver(): PostTagUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postTagUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostTagUpdateMutationErrorPayloadUnionTypeResolver */
            $postTagUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostTagUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->postTagUpdateMutationErrorPayloadUnionTypeResolver = $postTagUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postTagUpdateMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostTagUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
