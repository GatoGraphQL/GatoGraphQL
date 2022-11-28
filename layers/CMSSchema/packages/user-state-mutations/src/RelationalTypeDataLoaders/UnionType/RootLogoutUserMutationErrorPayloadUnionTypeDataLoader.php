<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootLogoutUserMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootLogoutUserMutationErrorPayloadUnionTypeResolver $rootLogoutUserMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootLogoutUserMutationErrorPayloadUnionTypeResolver(RootLogoutUserMutationErrorPayloadUnionTypeResolver $rootLogoutUserMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootLogoutUserMutationErrorPayloadUnionTypeResolver = $rootLogoutUserMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootLogoutUserMutationErrorPayloadUnionTypeResolver(): RootLogoutUserMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootLogoutUserMutationErrorPayloadUnionTypeResolver */
        return $this->rootLogoutUserMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootLogoutUserMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootLogoutUserMutationErrorPayloadUnionTypeResolver();
    }
}
