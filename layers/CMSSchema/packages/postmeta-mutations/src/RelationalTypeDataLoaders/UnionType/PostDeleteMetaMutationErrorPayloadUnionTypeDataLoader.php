<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostDeleteMetaMutationErrorPayloadUnionTypeResolver $postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostDeleteMetaMutationErrorPayloadUnionTypeResolver(): PostDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver = $postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCustomPostDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
