<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver $rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver(): RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver = $rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
