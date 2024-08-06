<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\RootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver $rootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver(RootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver $rootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver = $rootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver(): RootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver */
            $rootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver = $rootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver();
    }
}
