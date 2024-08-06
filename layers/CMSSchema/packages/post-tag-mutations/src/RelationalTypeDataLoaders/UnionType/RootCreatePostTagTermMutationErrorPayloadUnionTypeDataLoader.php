<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreatePostTagTermMutationErrorPayloadUnionTypeResolver(RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver = $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreatePostTagTermMutationErrorPayloadUnionTypeResolver(): RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver */
            $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver = $rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreatePostTagTermMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreatePostTagTermMutationErrorPayloadUnionTypeResolver();
    }
}
