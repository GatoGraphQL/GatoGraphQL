<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\RootUpdateMenuMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateMenuMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateMenuMutationErrorPayloadUnionTypeResolver $rootUpdateMenuMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateMenuMutationErrorPayloadUnionTypeResolver(): RootUpdateMenuMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateMenuMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateMenuMutationErrorPayloadUnionTypeResolver */
            $rootUpdateMenuMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateMenuMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateMenuMutationErrorPayloadUnionTypeResolver = $rootUpdateMenuMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateMenuMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateMenuMutationErrorPayloadUnionTypeResolver();
    }
}
