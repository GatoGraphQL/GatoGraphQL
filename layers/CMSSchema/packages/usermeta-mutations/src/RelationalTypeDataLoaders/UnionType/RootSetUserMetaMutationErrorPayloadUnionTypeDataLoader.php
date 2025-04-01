<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootSetUserMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetUserMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetUserMetaMutationErrorPayloadUnionTypeResolver $rootSetUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetUserMetaMutationErrorPayloadUnionTypeResolver(): RootSetUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetUserMetaMutationErrorPayloadUnionTypeResolver = $rootSetUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
