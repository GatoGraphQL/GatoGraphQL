<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver $rootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver(RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver $rootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver = $rootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver(): RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver = $rootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver();
    }
}
