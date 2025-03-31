<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver $rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddPostTermMetaMutationErrorPayloadUnionTypeResolver(): RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver = $rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddPostTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddPostTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
