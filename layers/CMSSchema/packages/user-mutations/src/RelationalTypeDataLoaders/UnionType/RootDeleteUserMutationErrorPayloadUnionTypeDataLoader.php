<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootDeleteUserMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteUserMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteUserMutationErrorPayloadUnionTypeResolver $rootDeleteUserMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteUserMutationErrorPayloadUnionTypeResolver(): RootDeleteUserMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteUserMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteUserMutationErrorPayloadUnionTypeResolver */
            $rootDeleteUserMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteUserMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteUserMutationErrorPayloadUnionTypeResolver = $rootDeleteUserMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteUserMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteUserMutationErrorPayloadUnionTypeResolver();
    }
}
