<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\PostAddMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class PostAddMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?PostAddMetaMutationErrorPayloadUnionTypeResolver $postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getPostAddMetaMutationErrorPayloadUnionTypeResolver(): PostAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var PostAddMetaMutationErrorPayloadUnionTypeResolver */
            $postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(PostAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver = $postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->postCustomPostAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getPostAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
