<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\RootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver $rootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
