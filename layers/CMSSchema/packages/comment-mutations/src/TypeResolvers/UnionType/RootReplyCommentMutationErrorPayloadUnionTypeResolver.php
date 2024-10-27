<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootReplyCommentMutationErrorPayloadUnionTypeResolver extends AbstractCommentMutationErrorPayloadUnionTypeResolver
{
    private ?RootReplyCommentMutationErrorPayloadUnionTypeDataLoader $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootReplyCommentMutationErrorPayloadUnionTypeDataLoader(): RootReplyCommentMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootReplyCommentMutationErrorPayloadUnionTypeDataLoader */
            $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootReplyCommentMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootReplyCommentMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when replying to a comment', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootReplyCommentMutationErrorPayloadUnionTypeDataLoader();
    }
}
