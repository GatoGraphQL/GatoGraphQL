<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
