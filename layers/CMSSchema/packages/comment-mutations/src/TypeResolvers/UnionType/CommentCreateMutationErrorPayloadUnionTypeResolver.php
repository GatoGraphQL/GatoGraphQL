<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CommentCreateMutationErrorPayloadUnionTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\UnionType\AbstractErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentCreateMutationErrorPayloadUnionTypeResolver extends AbstractErrorPayloadUnionTypeResolver
{
    private ?CommentCreateMutationErrorPayloadUnionTypeDataLoader $commentCreateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setCommentCreateMutationErrorPayloadUnionTypeDataLoader(CommentCreateMutationErrorPayloadUnionTypeDataLoader $commentCreateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->commentCreateMutationErrorPayloadUnionTypeDataLoader = $commentCreateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getCommentCreateMutationErrorPayloadUnionTypeDataLoader(): CommentCreateMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CommentCreateMutationErrorPayloadUnionTypeDataLoader */
        return $this->commentCreateMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(CommentCreateMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CommentCreateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding a comment', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentCreateMutationErrorPayloadUnionTypeDataLoader();
    }
}
