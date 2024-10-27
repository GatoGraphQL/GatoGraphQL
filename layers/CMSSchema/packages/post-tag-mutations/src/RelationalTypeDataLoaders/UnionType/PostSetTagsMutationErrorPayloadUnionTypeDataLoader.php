<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostSetTagsMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostSetTagsMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostSetTagsMutationErrorPayloadUnionTypeResolver $postSetTagsMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostSetTagsMutationErrorPayloadUnionTypeResolver(): PostSetTagsMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postSetTagsMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostSetTagsMutationErrorPayloadUnionTypeResolver */
            $postSetTagsMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostSetTagsMutationErrorPayloadUnionTypeResolver::class);
            $this->postSetTagsMutationErrorPayloadUnionTypeResolver = $postSetTagsMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postSetTagsMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostSetTagsMutationErrorPayloadUnionTypeResolver();
    }
}
