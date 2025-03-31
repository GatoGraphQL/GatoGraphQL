<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType\RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver $rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver(): RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver = $rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
