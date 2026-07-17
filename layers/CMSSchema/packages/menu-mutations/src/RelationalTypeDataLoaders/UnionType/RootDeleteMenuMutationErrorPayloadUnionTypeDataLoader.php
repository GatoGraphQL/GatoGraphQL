<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\RootDeleteMenuMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteMenuMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteMenuMutationErrorPayloadUnionTypeResolver $rootDeleteMenuMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteMenuMutationErrorPayloadUnionTypeResolver(): RootDeleteMenuMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteMenuMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteMenuMutationErrorPayloadUnionTypeResolver */
            $rootDeleteMenuMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteMenuMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteMenuMutationErrorPayloadUnionTypeResolver = $rootDeleteMenuMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteMenuMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteMenuMutationErrorPayloadUnionTypeResolver();
    }
}
