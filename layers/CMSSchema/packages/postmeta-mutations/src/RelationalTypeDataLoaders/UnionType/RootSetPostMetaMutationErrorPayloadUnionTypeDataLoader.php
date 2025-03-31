<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootSetPostMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetPostMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetPostMetaMutationErrorPayloadUnionTypeResolver $rootSetPostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetPostMetaMutationErrorPayloadUnionTypeResolver(): RootSetPostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetPostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetPostMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetPostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetPostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetPostMetaMutationErrorPayloadUnionTypeResolver = $rootSetPostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetPostMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetPostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
