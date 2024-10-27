<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver(): RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver */
            $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver();
    }
}
