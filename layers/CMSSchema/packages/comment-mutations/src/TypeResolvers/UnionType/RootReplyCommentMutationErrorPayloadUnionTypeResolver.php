<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\UnionType\AbstractErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootReplyCommentMutationErrorPayloadUnionTypeResolver extends AbstractErrorPayloadUnionTypeResolver
{
    private ?RootReplyCommentMutationErrorPayloadUnionTypeDataLoader $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootReplyCommentMutationErrorPayloadUnionTypeDataLoader(RootReplyCommentMutationErrorPayloadUnionTypeDataLoader $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootReplyCommentMutationErrorPayloadUnionTypeDataLoader(): RootReplyCommentMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootReplyCommentMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(RootReplyCommentMutationErrorPayloadUnionTypeDataLoader::class);
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
