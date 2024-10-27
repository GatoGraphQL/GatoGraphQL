<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentReplyMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentReplyMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CommentReplyMutationErrorPayloadUnionTypeResolver $commentReplyMutationErrorPayloadUnionTypeResolver = null;

    final protected function getCommentReplyMutationErrorPayloadUnionTypeResolver(): CommentReplyMutationErrorPayloadUnionTypeResolver
    {
        if ($this->commentReplyMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CommentReplyMutationErrorPayloadUnionTypeResolver */
            $commentReplyMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CommentReplyMutationErrorPayloadUnionTypeResolver::class);
            $this->commentReplyMutationErrorPayloadUnionTypeResolver = $commentReplyMutationErrorPayloadUnionTypeResolver;
        }
        return $this->commentReplyMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCommentReplyMutationErrorPayloadUnionTypeResolver();
    }
}
