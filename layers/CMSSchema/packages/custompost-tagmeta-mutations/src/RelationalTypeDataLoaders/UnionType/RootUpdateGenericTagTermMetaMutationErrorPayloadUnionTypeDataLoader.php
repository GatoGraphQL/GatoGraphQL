<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver $rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateGenericTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
