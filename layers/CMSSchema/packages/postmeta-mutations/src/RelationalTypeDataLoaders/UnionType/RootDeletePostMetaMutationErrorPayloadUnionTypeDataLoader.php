<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootDeletePostMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeletePostMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeletePostMetaMutationErrorPayloadUnionTypeResolver $rootDeletePostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePostMetaMutationErrorPayloadUnionTypeResolver(): RootDeletePostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePostMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeletePostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePostMetaMutationErrorPayloadUnionTypeResolver = $rootDeletePostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePostMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeletePostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
