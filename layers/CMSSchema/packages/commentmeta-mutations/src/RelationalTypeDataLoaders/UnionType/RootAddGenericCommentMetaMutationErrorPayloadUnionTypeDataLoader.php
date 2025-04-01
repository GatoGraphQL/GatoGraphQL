<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
