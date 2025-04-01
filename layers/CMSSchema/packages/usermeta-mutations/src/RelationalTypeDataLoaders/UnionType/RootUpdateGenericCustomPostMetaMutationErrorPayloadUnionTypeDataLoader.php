<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver $rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateGenericUserMetaMutationErrorPayloadUnionTypeResolver();
    }
}
