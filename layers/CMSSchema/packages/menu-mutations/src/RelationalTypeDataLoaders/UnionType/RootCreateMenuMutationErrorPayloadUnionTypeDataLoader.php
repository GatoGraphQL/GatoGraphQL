<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\RootCreateMenuMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreateMenuMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreateMenuMutationErrorPayloadUnionTypeResolver $rootCreateMenuMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootCreateMenuMutationErrorPayloadUnionTypeResolver(): RootCreateMenuMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateMenuMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateMenuMutationErrorPayloadUnionTypeResolver */
            $rootCreateMenuMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateMenuMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateMenuMutationErrorPayloadUnionTypeResolver = $rootCreateMenuMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateMenuMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreateMenuMutationErrorPayloadUnionTypeResolver();
    }
}
