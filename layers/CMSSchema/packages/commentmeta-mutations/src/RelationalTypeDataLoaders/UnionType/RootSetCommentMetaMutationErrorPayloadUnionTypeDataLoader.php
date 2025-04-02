<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootSetCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetCommentMetaMutationErrorPayloadUnionTypeResolver $rootSetCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootSetCommentMetaMutationErrorPayloadUnionTypeResolver(): RootSetCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootSetCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootSetCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootSetCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootSetCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootSetCommentMetaMutationErrorPayloadUnionTypeResolver = $rootSetCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootSetCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
