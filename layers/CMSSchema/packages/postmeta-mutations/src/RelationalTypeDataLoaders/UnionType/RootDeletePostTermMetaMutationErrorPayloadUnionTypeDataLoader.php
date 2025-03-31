<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver $rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver(): RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver = $rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
