<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CommentReplyMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentReplyMutationErrorPayloadUnionTypeResolver extends AbstractCommentMutationErrorPayloadUnionTypeResolver
{
    private ?CommentReplyMutationErrorPayloadUnionTypeDataLoader $commentReplyMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getCommentReplyMutationErrorPayloadUnionTypeDataLoader(): CommentReplyMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->commentReplyMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var CommentReplyMutationErrorPayloadUnionTypeDataLoader */
            $commentReplyMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(CommentReplyMutationErrorPayloadUnionTypeDataLoader::class);
            $this->commentReplyMutationErrorPayloadUnionTypeDataLoader = $commentReplyMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->commentReplyMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentReplyMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when replying to a comment (using nested mutations)', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentReplyMutationErrorPayloadUnionTypeDataLoader();
    }
}
