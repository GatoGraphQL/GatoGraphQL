<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver $rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver(): RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
