<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateMediaItemMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver $rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateMediaItemMutationErrorPayloadUnionTypeResolver(): RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver */
            $rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateMediaItemMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver = $rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateMediaItemMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateMediaItemMutationErrorPayloadUnionTypeResolver();
    }
}
