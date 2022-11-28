<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLoginUserMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootLoginUserMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootLoginUserMutationErrorPayloadUnionTypeResolver $rootLoginUserMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootLoginUserMutationErrorPayloadUnionTypeResolver(RootLoginUserMutationErrorPayloadUnionTypeResolver $rootLoginUserMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootLoginUserMutationErrorPayloadUnionTypeResolver = $rootLoginUserMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootLoginUserMutationErrorPayloadUnionTypeResolver(): RootLoginUserMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootLoginUserMutationErrorPayloadUnionTypeResolver */
        return $this->rootLoginUserMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootLoginUserMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootLoginUserMutationErrorPayloadUnionTypeResolver();
    }
}
