<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver $rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
