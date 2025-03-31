<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver $rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
