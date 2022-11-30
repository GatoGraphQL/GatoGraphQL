<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver(RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver(): RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver();
    }
}
