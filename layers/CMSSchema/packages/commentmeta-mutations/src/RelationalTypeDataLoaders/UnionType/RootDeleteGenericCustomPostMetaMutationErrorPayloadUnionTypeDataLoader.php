<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
