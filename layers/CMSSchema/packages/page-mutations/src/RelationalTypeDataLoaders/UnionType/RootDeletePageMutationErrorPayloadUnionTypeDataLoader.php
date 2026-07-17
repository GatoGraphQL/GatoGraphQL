<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\RootDeletePageMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeletePageMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeletePageMutationErrorPayloadUnionTypeResolver $rootDeletePageMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePageMutationErrorPayloadUnionTypeResolver(): RootDeletePageMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePageMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePageMutationErrorPayloadUnionTypeResolver */
            $rootDeletePageMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePageMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePageMutationErrorPayloadUnionTypeResolver = $rootDeletePageMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePageMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeletePageMutationErrorPayloadUnionTypeResolver();
    }
}
