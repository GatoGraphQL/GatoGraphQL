<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\RootCreatePageMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreatePageMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreatePageMutationErrorPayloadUnionTypeResolver $rootCreatePageMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootCreatePageMutationErrorPayloadUnionTypeResolver(): RootCreatePageMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreatePageMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreatePageMutationErrorPayloadUnionTypeResolver */
            $rootCreatePageMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreatePageMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreatePageMutationErrorPayloadUnionTypeResolver = $rootCreatePageMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreatePageMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreatePageMutationErrorPayloadUnionTypeResolver();
    }
}
