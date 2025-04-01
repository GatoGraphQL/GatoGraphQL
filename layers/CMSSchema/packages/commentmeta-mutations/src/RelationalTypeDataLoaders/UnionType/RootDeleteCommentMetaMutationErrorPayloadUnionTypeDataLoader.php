<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver $rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
