<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver(RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver();
    }
}
