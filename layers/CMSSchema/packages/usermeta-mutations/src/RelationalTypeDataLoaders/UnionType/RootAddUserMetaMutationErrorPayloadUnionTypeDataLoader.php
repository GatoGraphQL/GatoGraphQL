<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootAddUserMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootAddUserMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddUserMetaMutationErrorPayloadUnionTypeResolver $rootAddUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddUserMetaMutationErrorPayloadUnionTypeResolver(): RootAddUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddUserMetaMutationErrorPayloadUnionTypeResolver = $rootAddUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
