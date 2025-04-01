<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType\RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver $rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver(): RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver = $rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver();
    }
}
