<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver $rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetPostTermMetaMutationErrorPayloadUnionTypeResolver(): RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver = $rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetPostTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetPostTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
