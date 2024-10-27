<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdatePostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdatePostMutationErrorPayloadUnionTypeResolver $rootUpdatePostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdatePostMutationErrorPayloadUnionTypeResolver(): RootUpdatePostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver = $rootUpdatePostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdatePostMutationErrorPayloadUnionTypeResolver();
    }
}
