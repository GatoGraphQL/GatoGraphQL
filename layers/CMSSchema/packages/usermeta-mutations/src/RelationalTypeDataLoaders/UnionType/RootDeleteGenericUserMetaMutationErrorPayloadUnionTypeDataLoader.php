<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver $rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
