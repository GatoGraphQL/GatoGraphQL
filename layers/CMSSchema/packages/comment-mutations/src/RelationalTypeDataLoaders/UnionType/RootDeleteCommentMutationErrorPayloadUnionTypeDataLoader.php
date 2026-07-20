<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootDeleteCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteCommentMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteCommentMutationErrorPayloadUnionTypeResolver $rootDeleteCommentMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteCommentMutationErrorPayloadUnionTypeResolver(): RootDeleteCommentMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteCommentMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteCommentMutationErrorPayloadUnionTypeResolver */
            $rootDeleteCommentMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteCommentMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteCommentMutationErrorPayloadUnionTypeResolver = $rootDeleteCommentMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteCommentMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteCommentMutationErrorPayloadUnionTypeResolver();
    }
}
