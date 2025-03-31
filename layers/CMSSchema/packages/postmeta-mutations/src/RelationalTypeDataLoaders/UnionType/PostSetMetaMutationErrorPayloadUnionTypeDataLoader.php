<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostSetMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostSetMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostSetMetaMutationErrorPayloadUnionTypeResolver $postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostSetMetaMutationErrorPayloadUnionTypeResolver(): PostSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostSetMetaMutationErrorPayloadUnionTypeResolver */
            $postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = $postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
