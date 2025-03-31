<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
